<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\ServiceEnquiry;
use App\Model\ServiceEnquiryConv;
use App\Model\SupportTicketConv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServiceEnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $enquiries = ServiceEnquiry::orderBy('id', 'desc')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('subject', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $enquiries = ServiceEnquiry::orderBy('id', 'desc');
        }
        $enquiries = $enquiries->with(["product"])->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.service-enquiry.view', compact('enquiries', 'search'));
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $currency = ServiceEnquiry::find($request->id);
            $currency->status = $request->status;
            $currency->save();
        }
    }
    public function single_enquiry($id)
    {
        $enquiries = ServiceEnquiry::where('id', $id)->with(['conversations', 'user'])->get();

        return view('admin-views.service-enquiry.singleView', compact('enquiries'));
    }
    public function download_file($id)
    {
        $enquiry = ServiceEnquiry::where('id', $id)->first();
        //dd(asset("storage/private/enquiry/2022-03-24-623c7c2cc7527.png"));
        return Storage::disk("private")->download("enquiry/file" . $enquiry->file);
    }
    public function replay_submit(Request $request)
    {
        $reply = [
            'admin_message' => $request->replay,
            'admin_id' => $request->adminId,
            'service_enquiry_id' => $request->id,
            'checked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
        ServiceEnquiryConv::insert($reply);

        $enq = ServiceEnquiry::where("id", $request->id)
            ->with(["user:id,cm_firebase_token", "product:id,name"])->first();

        $token = $enq->user->cm_firebase_token;
        $data = [
            'title' => "New Reply on",
            "description" => "New Reply on " . $enq->product->name . " Service",
            "type" => "service"
        ];
        Helpers::send_push_notif_to_device($token, $data);

        return redirect()->back();
    }
}
