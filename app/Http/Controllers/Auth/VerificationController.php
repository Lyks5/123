<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        return view('auth.verify');
    }
    
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        
        return redirect()->route('account')->with('verified', true);
    }
    
    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        
        return back()->with('resent', true);
    }
}