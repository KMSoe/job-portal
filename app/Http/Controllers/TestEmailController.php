<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function index()
    {
        Mail::send('recruitment::emails.forgotpasswordmail', ['name' => "Stone", 'reset_link' => "dsf"], function ($message) {
            $message->to("maythuaung415@gmail.com");
            $message->subject('Welcome');
        });

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ]);
    }
}
