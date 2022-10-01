<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TraceOrder extends Model
{
    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public  static function recordUpdate(Order $order, ?Request $request, $type, $parms = null)
    {
        $user = auth('admin')->user();
        $traceOrder = new TraceOrder;
        switch ($type) {
            case 'order_status':
                $traceOrder->from = $order->order_status;
                $traceOrder->to = $request->order_status;
                break;
            case 'payment_status':
                $traceOrder->from = $order->payment_status;
                $traceOrder->to = $request->payment_status;
                break;
            case 'add_delivery_man':
                $traceOrder->from = $order->delivery_man_id;
                $traceOrder->to = $parms;
                break;

            default:
                return false;
                break;
        }
        $traceOrder->order_id = $order->id ?? null;
        $traceOrder->updated_by = $user->id;
        $traceOrder->type = $type;
        return $traceOrder->save();
    }
}
