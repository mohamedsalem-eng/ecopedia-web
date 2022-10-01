<?php

namespace App\CPU;

use App\Model\BusinessSetting;
use App\Model\Currency;
use App\Model\Order;
use App\Model\OrderTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BackEndHelper
{
    public static function currency_to_usd($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find(BusinessSetting::where(['type' => 'system_default_currency'])->first()->value);
            $usd = Currency::where('code', 'USD')->first()->exchange_rate;
            $rate = $default['exchange_rate'] / $usd;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }


    public static function usd_to_currency($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {

            if (session()->has('default')) {
                $default = session('default');
            } else {
                $default = Currency::find(Helpers::get_business_settings('system_default_currency'))->exchange_rate;
                session()->put('default', $default);
            }

            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where('code', 'USD')->first()->exchange_rate;
                session()->put('usd', $usd);
            }

            $rate = $default / $usd;
            $value = floatval($amount) * floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return round($value, 2);
    }

    public static function currency_symbol()
    {
        $currency = Currency::where('id', Helpers::get_business_settings('system_default_currency'))->first();
        return $currency->symbol;
    }



    public static function set_symbol($amount)
    {
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = currency_symbol() . '' . Helpers::convertToArabic($amount);
        } else {
            $string = Helpers::convertToArabic($amount) . '' . currency_symbol();
        }
        return $string;
    }

    public static function currency_code()
    {
        $currency = Currency::where('id', Helpers::get_business_settings('system_default_currency'))->first();
        return $currency->code;
    }
    public static function area_list()
    {
        $area_array = BusinessSetting::where(['type' => 'area_cost_list'])->first();

        if (empty($area_array)) {
            $area_array = collect(include(public_path('misc/area_list.php')));
        } else {
            $area_array = json_decode($area_array->value, true);
        }
        return collect($area_array);
    }

    public static function max_earning()
    {

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'seller_is' => 'admin',
            'order_status'=>'delivered'
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $month) {
            $count = 0;
            foreach ($month as $order) {
                $count += $order['order_amount'];
            }
            if ($count > $max) {
                $max = $count;
            }
        }

        return $max;
    }

    public static function max_orders()
    {
        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'seller_is' => 'admin',
            'order_type'=>'default_type'
        ])->select(
            DB::raw('COUNT(id) as count'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $item) {
            if ($item['count'] > $max) {
                $max = $item['count'];
            }
        }

        return $max;
    }

    public static function cost_per_area($area)
    {
        $area_array = collect(include(public_path('misc/area_list.php')));

        $area = $area_array->wherefirst("id", $area);

        return $cost;
    }
}
