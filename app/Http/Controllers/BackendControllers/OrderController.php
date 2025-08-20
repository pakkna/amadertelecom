<?php

namespace App\Http\Controllers\BackendControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order_list()
    {
        // $getOrtder = Order::with('operator', 'category')->orderBy('created_at', 'DESC')->get();

        return view('dashboard.order.order_list');
    }
    public function pending_order_list()
    {
        // $getOrtder = Order::with('operator', 'category')->orderBy('created_at', 'DESC')->get();

        return view('dashboard.order.pending_order_list');
    }
    public function order_completed()
    {
        // $getOrtder = Order::with('operator', 'category')->orderBy('created_at', 'DESC')->get();

        return view('dashboard.order.order_completed');
    }
}
