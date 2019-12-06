<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use App\Mail\EnquiryMail;

class PagesController extends Controller
{
    public function contactUs()
    {
        return view('pages.contact_us');
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'message.name' => 'required|string|max:40',
            'message.email' => 'required|string|email',
            'message.phone' => 'required|string|max:15',
            'message.subject' => 'required|string|max:80',
            'message.message' => 'required|string|max:240',
        ]);

        //insert into db
        $msg = new ContactMessage;
        $msg->name = $request->message['name'];
        $msg->email = $request->message['email'];
        $msg->phone = $request->message['phone'];
        $msg->title = $request->message['subject'];
        $msg->message = $request->message['message'];
        $msg->save();

        //send email
        // Mail::to('blemadeliveries@gmail.com')->send(new EnquiryMail($msg));
        Mail::to('blemadeliveries@gmail.com')->send(new EnquiryMail($msg));

        return response()->json($msg, 200);
    }

    public function sendEnquiryEmail(Request $req){
        $msg = ContactMessage::findOrFail($req->msg);
        $adminEmail = 'blemadeliveries@gmail.com';
        Mail::to($adminEmail)->send(new ContactEmail($msg));

        return response()->json($msg, 200);
    }

    public function aboutUs()
    {
        return view('pages.about_us');
    }

    public function howItWorks()
    {
        return view('pages.how');
    }

    public function termsAndConditions()
    {
        return view('pages.terms');
    }
}
