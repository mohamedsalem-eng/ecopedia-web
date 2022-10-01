<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class ShippingMethodController extends Controller
{
    public function get_shipping_method_info($id)
    {
        try {
            $shipping = ShippingMethod::find($id);
            return response()->json($shipping, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function shipping_methods_by_seller($id, $seller_is)
    {
        $seller_is = $seller_is == 'admin' ? 'admin' : 'seller';
        return response()->json(Helpers::get_shipping_methods($id, $seller_is), 200);
    }

    public function choose_for_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_group_id' => 'required',
            'id' => 'required'
        ], [
            'id.required' => translate('shipping_id_is_required')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids($request) as $group_id) {
                $request['cart_group_id'] = $group_id;
                $cost = self::insert_into_cart_shipping($request);
            }
        } else {
            $cost = self::insert_into_cart_shipping($request);
        }

        return response()->json(["message" => translate('successfully_added'), "cost" => $cost ?? null]);
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }


        $method  = ShippingMethod::find($request['id']);

        $cost = $method->cost;

        if ($method->has_area) {
            $cost += BackEndHelper::area_list()->firstWhere("id", $request['city'] ?? "")['cost'] ?? 0;
        }

        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $request['id'];
        $shipping['shipping_cost'] = $cost;
        $shipping->save();
        return $cost;
    }

    public function chosen_shipping_methods(Request $request)
    {
        $group_ids = CartManager::get_cart_group_ids($request);
        return response()->json(CartShipping::whereIn('cart_group_id', $group_ids)->get(), 200);
    }
}
