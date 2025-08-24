<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Packages;
use App\Models\Category;
use App\Models\Operator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PackageOrder;


class ApiPackageController extends Controller
{

    public function getPacakgeList(Request $request)
    {
        $packageQuery = Packages::query()
            ->with(['operator', 'category'])
            ->where('packages.status', 1)
            ->when($request->filled('operator'), function ($q) use ($request) {
                $ops = is_array($request->operator)
                    ? $request->operator
                    : array_map('trim', explode(',', $request->operator));

                $q->whereHas('operator', fn ($oq) => $oq->whereIn('name', $ops));
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $cats = is_array($request->category)
                    ? $request->category
                    : array_map('trim', explode(',', $request->category));

                $q->whereHas('category', fn ($cq) => $cq->whereIn('name', $cats));
            })
            ->orderByDesc('packages.created_at');

        $getlist = $packageQuery->get();

        return $this->ResponseJson(false, 'Package List', $getlist, 200);
    }

    public function add_money(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'sender_number'      => 'required|regex:/^[0-9]{11}$/',
            'wallet_type'        => 'required|string|max:50',
            'transaction_number' => 'required|string|unique:transactions,transaction_number',
            'transaction_amount' => 'required|numeric|min:1',
            'user_id' => 'required|numeric|min:1',
            'transaction_date'   => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        }

        // Save to DB
        $transaction = Transactions::create([
            'sender_number'      => $request->sender_number,
            'wallet_type'        => $request->wallet_type,
            'transaction_number' => $request->transaction_number,
            'transaction_amount' => $request->transaction_amount,
            'transaction_date'   => $request->transaction_date,
            'transaction_date'   => $request->transaction_date,
            'user_id'            => $request->user_id,
            'status'             => "Pending",
        ]);

        return $this->ResponseJson(false, 'Registration Successful!', $transaction, 200);
    }

    public function transactionHistory(Request $request, $userId)
    {
        try {
            $perPage = (int) $request->query('per_page', 10); // default 10 per page

            $query = Transactions::where('user_id', $userId)
                ->orderBy('transaction_date', 'desc');

            $transactions = $query->paginate($perPage);

            if ($transactions->isEmpty()) {
                return $this->sendError(true, 'No transactions found.', [], 404);
            }

            // Transform each record
            $transactions->getCollection()->transform(function ($t) {
                return [
                    'id'                 => $t->id,
                    'sender_number'      => $t->sender_number,
                    'wallet_type'        => $t->wallet_type,
                    'transaction_number' => $t->transaction_number,
                    'transaction_amount' => $t->transaction_amount,
                    'status'             => $t->status,
                    'transaction_date'   => $t->transaction_date
                        ? Carbon::parse($t->transaction_date)->format('d M Y, h:i A')
                        : '',
                    'transaction_date_h' => $t->transaction_date
                        ? Carbon::parse($t->transaction_date)->diffForHumans()
                        : '',
                    'created_at'         => $t->created_at
                        ? Carbon::parse($t->created_at)->format('d M Y, h:i A')
                        : '',
                    'created_at_h'       => $t->created_at
                        ? Carbon::parse($t->created_at)->diffForHumans()
                        : '',
                    'updated_at'         => $t->updated_at
                        ? Carbon::parse($t->updated_at)->format('d M Y, h:i A')
                        : '',
                    'updated_at_h'       => $t->updated_at
                        ? Carbon::parse($t->updated_at)->diffForHumans()
                        : '',
                ];
            });

            return $this->ResponseJson(false, 'Transaction history fetched successfully!', $transactions, 200);
        } catch (\Exception $e) {
            return $this->sendError(true, 'Something went wrong.', [$e->getMessage()], 500);
        }
    }

    public function order_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_number' => 'required|string|max:255',
            'user_id'        => 'required|integer|min:1',
            'package_id'     => 'required|integer|min:1',
            'order_note'     => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        }

        $data = $validator->validated();

        try {
            $order = DB::transaction(function () use ($data) {
                // 0) 🚫 DUPLICATE CHECK (same user + package + request_number in last 5 min
                $duplicateExists = PackageOrder::where('user_id', $data['user_id'])
                    ->where('package_id', $data['package_id'])
                    ->where('request_number', $data['request_number'])
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->whereNotIn('order_status', ['Canceled', 'Failed']) // treat active/recent as duplicates
                    ->exists();

                if ($duplicateExists) {
                    throw new \DomainException('Please Request After 5 minutes.');
                }

                // 1) Fetch active package (with relations)
                $package = Packages::query()
                    ->with(['operator', 'category'])
                    ->where('packages.status', 1)
                    ->where('packages.id', $data['package_id'])
                    ->first();

                if (!$package) {
                    throw new \RuntimeException('Invalid or inactive package_id.');
                }

                $amount = $package->offer_price ?? null; // adjust column if needed
                if ($amount === null) {
                    throw new \RuntimeException('Package price/amount not found.');
                }

                // 2) Lock the user and check balance
                $user = User::where('id', $data['user_id'])->lockForUpdate()->first();
                if (!$user) {
                    throw new \RuntimeException('User not found.');
                }

                $userBalance = (float) $user->balance;
                $packageCost = (float) $amount;

                if ($userBalance < $packageCost) {
                    throw new \DomainException('Insufficient balance.');
                }

                // 3) Generate order number like ORD1234567
                $orderNumber = $this->generateUniqueOrderNumber();

                // 4) Build order_info JSON
                $orderInfo = [
                    'package'  => $package->toArray(),
                    'operator' => $package->operator ? $package->operator->toArray() : null,
                    'category' => $package->category ? $package->category->toArray() : null,
                ];

                // 5) Create order
                $order = PackageOrder::create([
                    'order_number'   => $orderNumber,
                    'package_id'     => $package->id,
                    'request_number' => $data['request_number'],
                    'order_amount'   => $packageCost,
                    'order_status'   => 'Pending',
                    'order_date'     => now()->toDateString(),
                    'user_id'        => $user->id,
                    'order_info'     => $orderInfo,
                    'order_note'     => $data['order_note'] ?? null,
                ]);

                // 6) Deduct balance and save
                $user->balance = $userBalance - $packageCost;
                $user->save();

                // if (isset($lockKey)) Cache::forget($lockKey); // optional if using lock

                return $order;
            });

            // format response (human dates + null => "")
            $formatted = $this->formatOrderResponse($order);

            return $this->ResponseJson(false, 'Order created successfully!', $formatted, 200);
        } catch (\DomainException $e) {
            return $this->sendError(true, 'Order creation failed.', [$e->getMessage()], 409); // 409 Conflict for duplicate/insufficient
        } catch (\Throwable $e) {
            return $this->sendError(true, 'Order creation failed.', [$e->getMessage()], 500);
        }
    }

    /**
     * Unique order no: ORD + 7 digits (e.g., ORD4646464).
     */
    private function generateUniqueOrderNumber(): string
    {
        do {
            $candidate = 'ORD' . rand(1000000, 9999999);
        } while (PackageOrder::where('order_number', $candidate)->exists());

        return $candidate;
    }

    /**
     * Human-readable dates + nulls to "".
     */
    private function formatOrderResponse($order)
    {
        $data = $order->toArray();

        $data['order_date'] = $order->order_date
            ? Carbon::parse($order->order_date)->format('d M Y, h:i A')
            : '';
        $data['created_at'] = $order->created_at
            ? Carbon::parse($order->created_at)->format('d M Y, h:i A')
            : '';
        $data['updated_at'] = $order->updated_at
            ? Carbon::parse($order->updated_at)->format('d M Y, h:i A')
            : '';

        array_walk_recursive($data, function (&$v) {
            if (is_null($v)) $v = "";
        });

        return $data;
    }

    public function orderHistory(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);

        $query = PackageOrder::where('user_id', $request->user_id)
            ->orderByDesc('created_at');

        if (isset($request->status)) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate($perPage);

        if ($orders->isEmpty()) {
            return $this->sendError(true, 'No orders found.', [], 200);
        }

        // Format each item (human dates + null -> "")
        $orders->getCollection()->transform(function ($order) {
            return $this->formatOrderForApi($order);
        });

        return $this->ResponseJson(false, 'Order history fetched successfully!', $orders, 200);
    }

    /**
     * Format order for API response:
     * - human-readable dates
     * - replace null with ""
     */
    private function formatOrderForApi(PackageOrder $order): array
    {
        $data = $order->toArray();

        // Format dates
        $data['order_date'] = $order->order_date
            ? Carbon::parse($order->order_date)->format('d M Y, h:i A')
            : '';
        $data['created_at'] = $order->created_at
            ? Carbon::parse($order->created_at)->format('d M Y, h:i A')
            : '';
        $data['updated_at'] = $order->updated_at
            ? Carbon::parse($order->updated_at)->format('d M Y, h:i A')
            : '';

        // Nulls -> empty string (deep)
        array_walk_recursive($data, function (&$v) {
            if (is_null($v)) $v = "";
        });

        return $data;
    }

    public function refund_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|integer|min:1',
            'order_id' => 'required|integer|min:1',
            'reason'   => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        }

        $data = $validator->validated();

        try {
            $refund = DB::transaction(function () use ($data) {
                // Lock order to ensure correctness
                $order = PackageOrder::where('id', $data['order_id'])
                    ->where('user_id', $data['user_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    throw new \RuntimeException('Order not found for this user.');
                }

                // Block duplicate pending refund for same order (also DB unique key backs us up)
                $exists = RefundRequest::where('user_id', $data['user_id'])
                    ->where('order_id', $data['order_id'])
                    ->where('status', 'Pending')
                    ->exists();

                if ($exists) {
                    throw new \DomainException('A pending refund request already exists for this order.');
                }

                $amount = isset($data['amount']) && $data['amount'] > 0
                    ? (float) $data['amount']
                    : (float) $order->order_amount;

                // Optionally cap amount to order amount
                if ($amount > (float) $order->order_amount) {
                    throw new \DomainException('Refund amount cannot exceed order amount.');
                }

                // Generate unique refund number: RFD + 7 digits
                $refundNumber = $this->generateRefundNumber();

                $refund = RefundRequest::create([
                    'user_id'       => $data['user_id'],
                    'order_id'      => $data['order_id'],
                    'refund_number' => $refundNumber,
                    'reason'        => $data['reason'],
                    'amount'        => $amount,
                    'status'        => 'Pending',
                ]);

                return $refund;
            });

            // Format for response: human dates + null => ""
            $formatted = $this->formatRefund($refund);

            return $this->ResponseJson(false, 'Refund request created successfully!', $formatted, 200);
        } catch (\DomainException $e) {
            return $this->sendError(true, 'Refund creation failed.', [$e->getMessage()], 409);
        } catch (\Throwable $e) {
            return $this->sendError(true, 'Refund creation failed.', [$e->getMessage()], 500);
        }
    }

    public function refund_list(Request $request, int $userId)
    {
        $status  = $request->query('status');
        $perPage = (int) $request->query('per_page', 10);

        $query = RefundRequest::where('user_id', $userId)
            ->when(!empty($status), fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at');

        $refunds = $query->paginate($perPage);

        if ($refunds->isEmpty()) {
            return $this->sendError(true, 'No refund requests found.', [], 404);
        }

        // map each item for API (human dates + null => "")
        $refunds->getCollection()->transform(fn ($r) => $this->formatRefund($r));

        return $this->ResponseJson(false, 'Refund request list fetched successfully!', $refunds, 200);
    }

    private function generateRefundNumber(): string
    {
        do {
            $candidate = 'RFD' . rand(1000000, 9999999);
        } while (RefundRequest::where('refund_number', $candidate)->exists());

        return $candidate;
    }

    /**
     * Human-readable dates + null => ""
     */
    private function formatRefund(RefundRequest $r): array
    {
        $data = $r->toArray();

        $data['created_at'] = $r->created_at ? Carbon::parse($r->created_at)->format('d M Y, h:i A') : '';
        $data['updated_at'] = $r->updated_at ? Carbon::parse($r->updated_at)->format('d M Y, h:i A') : '';
        $data['processed_at'] = $r->processed_at ? Carbon::parse($r->processed_at)->format('d M Y, h:i A') : '';

        // deep replace nulls with empty string
        array_walk_recursive($data, function (&$v) {
            if (is_null($v)) $v = "";
        });

        return $data;
    }

    public function SpecialOfferList(Request $request, int $userId)
    {
        $status = $request->query('status', 'Active'); // optional filter

        $user = User::find($userId);
        if (!$user) {
            return $this->sendError(true, 'User not found.', [], 404);
        }
        if (!$user->operator_id) {
            return $this->sendError(true, 'User operator not set.', [], 422);
        }

        $now = now();

        // Eager-load package + images + operator (name only)
        $offers = SpecialOffer::with([
            'package:id,package_name,duration,operator_id,category_id,actual_price,offer_price,tag,status,conditions,created_at,updated_at',
            'images:id,offer_id,image_url,caption,sort_order,created_at,updated_at',
            'operator:id,name',
        ])
            ->where('operator_id', $user->operator_id)
            ->when(!empty($status), fn ($q) => $q->where('status', $status))
            // within date window (null means open)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        if ($offers->isEmpty()) {
            return $this->sendError(true, 'No offers found.', [], 404);
        }

        // Transform: human-readable dates + null => ""
        $data = $offers->map(function ($o) {
            $pkg = $o->package;

            $row = [
                'id'           => $o->id,
                'operator_id'  => $o->operator_id,
                'operator'     => $o->operator?->name ?? '',

                'package_id'   => $o->package_id,
                // ⬇️ package fields from packages table
                'package_name' => $pkg->package_name ?? '',
                'duration'     => $pkg->duration ?? '',
                'actual_price' => $pkg->actual_price ?? '',
                'offer_price'  => $pkg->offer_price ?? '',
                'tag'          => $pkg->tag ?? '',
                'conditions'   => $pkg->conditions ?? '',

                // slider images
                'images' => $o->images->map(function ($img) {
                    $ir = [
                        'id'         => $img->id,
                        'image_url'  => $img->image_url,
                        'caption'    => $img->caption ?? '',
                        'sort_order' => $img->sort_order,
                        'created_at' => $img->created_at ? Carbon::parse($img->created_at)->format('d M Y, h:i A') : '',
                        'updated_at' => $img->updated_at ? Carbon::parse($img->updated_at)->format('d M Y, h:i A') : '',
                    ];
                    // deep null cleanup for each image row
                    array_walk_recursive($ir, function (&$v) {
                        if (is_null($v)) $v = "";
                    });
                    return $ir;
                })->values()->all(),

                // offer meta (from special_offers)
                'status'     => $o->status,
                'start_at'   => $o->start_at ? Carbon::parse($o->start_at)->format('d M Y, h:i A') : '',
                'end_at'     => $o->end_at ? Carbon::parse($o->end_at)->format('d M Y, h:i A') : '',
                'created_at' => $o->created_at ? Carbon::parse($o->created_at)->format('d M Y, h:i A') : '',
                'updated_at' => $o->updated_at ? Carbon::parse($o->updated_at)->format('d M Y, h:i A') : '',
            ];

            // deep null cleanup for offer row
            array_walk_recursive($row, function (&$v) {
                if (is_null($v)) $v = "";
            });

            return $row;
        });

        return $this->ResponseJson(false, 'Offers fetched successfully!', $data, 200);
    }
}
