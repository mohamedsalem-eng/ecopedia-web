<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\EnquiryManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\ServiceEnquiry;
use App\Model\ServiceEnquiryConv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\CPU\translate;

class ServiceController extends Controller
{
    public function submitEnquiry(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'enquiry_file' => "required|file|max:10240|mimetypes:application/zip,application/x-7z-compressed,application/vnd.rar",
                'product_id' => "required|exists:App\Model\Product,id",
            ],
            [
                'enquiry_file.required' => translate("file_is_required")
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if (!$request->user()->id) {
            return response()->json([
                'errors' => ['message' => translate('sign_in_first')]
            ], 404);
        }

        $enquiry = new ServiceEnquiry();


        $enquiry->description = $request->description;
        $enquiry->product_id = $request->product_id;
        $enquiry->user_id = $request->user()->id;
        $enquiry->file = EnquiryManager::upload('enquiry/file', $request->enquiry_file);
        $enquiry->save();

        return response()->json(['message' => translate("we_will_contact_with_you_soon")], 200);
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        $support = new ServiceEnquiryConv();
        $support->service_enquiry_id = $ticket_id;
        $support->admin_id = 1;
        $support->customer_message = $request['message'];
        $support->save();
        return response()->json(['message' => 'Support ticket reply sent.'], 200);
    }

    public function get_support_tickets(Request $request)
    {
        return response()->json(ServiceEnquiry::where('user_id', $request->user()->id)
            ->withCount("notSeen")
            ->with("product:id,name")->get(), 200);
    }
    public function count(Request $request)
    {
        $count = ServiceEnquiry::where('user_id', $request->user()->id)
            ->select("id")
            ->withCount("notSeen")->get()->sum("not_seen_count");
        return response()->json(['message' => $count], 200);
    }

    public function check(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => "required|exists:App\Model\ServiceEnquiry,id",
            ],
        );
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
       ServiceEnquiry::where('user_id', $request->user()->id)
            ->where("id", $request->id)->first()
            ->conversations()->update(["checked" => true]);
        return response()->json(['message' => "Service is successfully seen"], 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {
        ServiceEnquiry::where("id", $ticket_id)->first()
            ->conversations()->update(["checked" => true]);
        return response()->json(ServiceEnquiryConv::where('service_enquiry_id', $ticket_id)->get(), 200);
    }
}
