<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryMail;
use App\Model\Inquiry; 
use App\Model\BusinessSetting;

class InquiryController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string',
            // 'last_name' => 'required|string',
            'email' => 'required|email',
            'mobile_no' => 'required',
            'subject' => 'required|string',
            'message' => 'required|string',

        ]);

        // If using a model, store the inquiry in the database
        Inquiry::create($request->all());
        $adminMail = BusinessSetting::where('key','email_address')->first();
        if(!empty($adminMail)){
            // Send an email
            Mail::to($adminMail['value'])->send(new InquiryMail($request->all()));
        }
        return response()->json(['message' => 'Inquiry sent successfully']);
    }
}
