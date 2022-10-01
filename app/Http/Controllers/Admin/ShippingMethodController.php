<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\ShippingMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingMethodController extends Controller
{
    public function index_admin()
    {
        $shipping_methods = ShippingMethod::where(['creator_type' => 'admin'])->get();
        return view('admin-views.shipping-method.add-new', compact('shipping_methods'));
    }
    public function area_cost()
    {
        $area_array = BackEndHelper::area_list();
        return view('admin-views.shipping-method.area-cost', compact('area_array'));
    }
    public function store_area_cost(Request $request)
    {
        $list = BusinessSetting::where(['type' => 'area_cost_list'])->first();
        $area_list = collect($request->area_cost)->map(function ($item) {
            $item['cost'] = BackEndHelper::currency_to_usd($item['cost']);
            return $item;
        });

        if (empty($list)) {
            BusinessSetting::create(['type' => 'area_cost_list', 'value' => json_encode($area_list)]);
        } else {
            $list->update(["value" => json_encode($area_list)]);
        }
        /* $area = ['type' => 'area_cost_list', 'value'=> json_encode($area_list)];
        BusinessSetting::upsert([$area],['type',],['value']); */
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|max:200',
            'duration' => 'required',
            'cost'     => 'numeric',
        ]);

        DB::table('shipping_methods')->insert([
            'creator_id'   => auth('admin')->id(),
            'creator_type' => 'admin',
            'title'        => $request['title'],
            'duration'     => $request['duration'],
            'cost'         => BackEndHelper::currency_to_usd($request['cost']),
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        Toastr::success('Successfully added.');
        return back();
    }

    public function status_update(Request $request)
    {
        ShippingMethod::where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function area_update(Request $request)
    {
        ShippingMethod::where(['id' => $request['id']])->update([
            'has_area' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function edit($id)
    {
        if ($id != 1) {
            $method = ShippingMethod::where(['id' => $id])->first();
            return view('admin-views.shipping-method.edit', compact('method'));
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|max:200',
            'duration' => 'required',
            'cost'     => 'numeric',
        ]);

        DB::table('shipping_methods')->where(['id' => $id])->update([
            'creator_id'   => auth('admin')->id(),
            'creator_type' => 'admin',
            'title'        => $request->title,
            'duration'     => $request->duration,
            'cost'         => BackEndHelper::currency_to_usd($request->cost),
            'status'       => 1,
            'updated_at'   => now(),
        ]);

        Toastr::success('Successfully updated.');
        return redirect()->back();
    }

    public function setting()
    {
        return view('admin-views.shipping-method.setting');
    }
    public function shippingStore(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'shipping_method'], [
            'value' => $request['shippingMethod']
        ]);
        Toastr::success('Shipping Method Added Successfully!');
        return back();
    }
    public function delete(Request $request)
    {

        $shipping = ShippingMethod::find($request->id);

        $shipping->delete();
        return response()->json();
    }
}
