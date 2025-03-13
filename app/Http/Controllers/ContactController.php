<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactRequest;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        return view('pages.contact');
    }
    
    /**
     * Store a new contact request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        $validated['status'] = 'pending';
        
        ContactRequest::create($validated);
        
        return redirect()->back()->with('success', 'Ваше сообщение успешно отправлено! Наша команда свяжется с вами в ближайшее время.');
    }
}