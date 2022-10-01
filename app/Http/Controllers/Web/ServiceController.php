<?php

namespace App\Http\Controllers\Web;

use App\CPU\EnquiryManager;
use App\Http\Controllers\Controller;
use App\Model\ServiceEnquiry;
use Illuminate\Http\Request;

use function App\CPU\translate;

class ServiceController extends Controller
{
    public function submitEnquiry(Request $request)
    {
        $this->validate(
            $request,
            [
                'enquiry_file' => "required|file|max:10240|mimetypes:application/zip,application/x-7z-compressed,application/vnd.rar",
                'product_id' => "required|exists:App\Model\Product,id",
            ],
            [
                'enquiry_file.required' => translate("file_is_required")
            ]
        );
        if (!auth("customer")->id()) {
            return redirect(route("customer.auth.login"));
        }

        $enquiry = new ServiceEnquiry;


        $enquiry->description = $request->description;
        $enquiry->product_id = $request->product_id;
        $enquiry->user_id = auth("customer")->id();
        $enquiry->file = EnquiryManager::upload('enquiry/file', $request->enquiry_file);
        $enquiry->save();


        return back()->with("enquiry_success", translate("we_will_contact_with_you_soon"));
    }
}
