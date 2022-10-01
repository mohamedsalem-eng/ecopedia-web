<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;
use App\Model\TraceOrder;
use Illuminate\Http\Request;

class TraceOrdersController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $traces = TraceOrder::with(['admin', 'order'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%")
                        ->orWhere('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $traces = TraceOrder::with(['admin', 'order']);
        }

        $deliver_men = DeliveryMan::all(['f_name', 'l_name', 'id']);

        $traces = $traces->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.trace_orders.index', compact('traces', 'search', 'deliver_men'));
    }
}
