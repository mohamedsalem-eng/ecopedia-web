<?php

namespace App\Http\Controllers\Customer;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\Model\ShippingAddress;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function set_payment_method($name)
    {
        if (auth('customer')->check() || session()->has('mobile_app_payment_customer_id')) {
            session()->put('payment_method', $name);
            return response()->json([
                'status' => 1
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }

    public function set_shipping_method(Request $request)
    {

        if (empty($request->id)) {

            return response()->json([
                'status' => 0
            ]);
        }
        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $request['cart_group_id'] = $group_id;
                $total_cost = self::insert_into_cart_shipping($request);
            }
        } else {
            $total_cost = self::insert_into_cart_shipping($request);
        }


        return response()->json([
            'status' => 1,
            "total_cost" => $total_cost ?? null
        ]);
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }
        $method = ShippingMethod::find($request['id']);
        if (isset($method) == false) {
            return response()->json([
                'status' => 0
            ], 404);
        }
        $address = ShippingAddress::find(session("address_id"), 'city');
        $cost = BackEndHelper::area_list()->firstWhere("id", $address->city)['cost'] ?? 0;

        if ($method->has_area) {
            $total_cost = $cost + ($method->cost ?? 0);
        }else{
            $total_cost = $method->cost ?? 0;
        }
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $method->id;
        $shipping['shipping_cost'] = $total_cost ?? 0;
        $shipping->save();
        return $total_cost;
    }

    public function choose_shipping_address(Request $request)
    {
        $shipping = [];
        $billing = [];
        parse_str($request->shipping, $shipping);
        parse_str($request->billing, $billing);
        $request->merge(['billing_addresss_same_shipping' => true]);

        $area_array = BackEndHelper::area_list();
        if (isset($shipping['save_address']) && $shipping['save_address'] == 'on') {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null || $shipping['country'] == null) {
                return response()->json([
                    'errors' => ['']
                ], 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'city' => $area_array->firstwhere("id", $shipping['city'])['id'] ?? "",
                'customer_id' => auth('customer')->id(),
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'country' => $shipping['country'],
                'zip' => $shipping['zip'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else if ($shipping['shipping_method_id'] == 0) {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null || $shipping['country'] == null) {
                return response()->json([
                    'errors' => ['']
                ], 403);
            }




            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => 0,
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $area_array->firstwhere("id", $shipping['city'])['name'] ?? "",
                'country' => $shipping['country'],
                'zip' => $shipping['zip'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $address_id = $shipping['shipping_method_id'];
        }


        $billing_address_id = $shipping['shipping_method_id'];
        /* if ($request->billing_addresss_same_shipping == 'false') {

            if (isset($billing['save_address_billing']) && $billing['save_address_billing'] == 'on') {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null || $billing['billing_country'] == null) {
                    return response()->json([
                        'errors' => ['']
                    ], 403);
                }


                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'city' => $area_array->firstwhere("id", $billing['billing_city'])['name'] ?? "",
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],

                    'country' => $billing['billing_country'],
                    'zip' => $billing['billing_zip'],
                    'phone' => $billing['billing_phone'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else if (($billing['billing_method_id'] ?? 1) == 0) {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null || $billing['billing_country'] == null) {
                    return response()->json([
                        'errors' => ['']
                    ], 403);
                }


                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'city' => $area_array->firstwhere("id", $billing['billing_city'])['name'] ?? "",
                    'customer_id' => 0,
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address' => $billing['billing_address'],
                    'address_type' => $billing['billing_address_type'],
                    'country' => $billing['billing_country'],
                    'zip' => $billing['billing_zip'],
                    'phone' => $billing['billing_phone'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $billing_address_id = $billing['billing_method_id'];
            }
        } else {
            $billing_address_id = $shipping['shipping_method_id'];
        } */

        session()->put('address_id', $address_id);
        session()->put('billing_address_id', $billing_address_id);

        return response()->json([], 200);
    }
}
