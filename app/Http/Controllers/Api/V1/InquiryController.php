<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryMail;
use App\Models\Inquiry; 

class InquiryController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // If using a model, store the inquiry in the database
        Inquiry::create($request->all());

        // Send an email
        Mail::to('your@email.com')->send(new InquiryMail($request->all()));

        return response()->json(['message' => 'Inquiry sent successfully']);
    }
}
