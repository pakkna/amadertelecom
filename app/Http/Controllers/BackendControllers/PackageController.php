<?php

namespace App\Http\Controllers\BackendControllers;

use App\Models\Category;
use App\Models\Operator;
use App\Models\Packages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function package_list()
    {
        $getPackages = Packages::with('operator', 'category')->orderBy('created_at', 'DESC')->get();
        $operators = Operator::all();
        $categories = Category::all();
        return view('dashboard.packages.package_list', compact('getPackages', 'operators', 'categories'));
    }
    public function active_package_list()
    {
        $getPackages = Packages::with('operator', 'category')->orderBy('created_at', 'DESC')->where('packages.status', 1)->get();

        return view('dashboard.packages.active_pacakge', compact('getPackages'));
    }
    public function most_selling_packages()
    {
        $getPackages = Packages::with('operator', 'category')->orderBy('created_at', 'DESC')->where('packages.status', 1)->get();

        return view('dashboard.packages.most_selling_package', compact('getPackages'));
    }

    public function packageStore(Request $request)
    {
        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'duration'       => 'required|string|max:100',
            'operator_id'    => 'required|exists:operators,id',
            'category_id'    => 'required|exists:category,id',
            'actual_price'   => 'required|numeric|min:0',
            'offer_price'    => 'required|numeric|min:0|lte:actual_price',
            'tag'            => 'nullable|string|max:100',
            'status'         => 'required|in:0,1',
        ]);

        $package = new Packages();
        $package->package_name         = $validated['package_name'];
        $package->duration     = $validated['duration'];
        $package->operator_id  = $validated['operator_id'];
        $package->category_id  = $validated['category_id'];
        $package->actual_price = $validated['actual_price'];
        $package->offer_price  = $validated['offer_price'];
        $package->tag          = $validated['tag'] ?? null;
        $package->status       = $validated['status'];
        $package->save();

        return redirect()->back()->with('success', 'Package added successfully!');
    }

    public function packageDestroy($id)
    {
        $package = Packages::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully.');
    }
}
