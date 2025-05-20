<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    /**
     * Display a listing of contact requests.
     */
    public function index()
    {
        $requests = ContactRequest::paginate(10);
        return view('admin.contact-requests.index', compact('requests'));
    }

    /**
     * Display the specified contact request.
     */
    public function show(ContactRequest $contactRequest)
    {
        return view('admin.contact-requests.show', compact('contactRequest'));
    }

    /**
     * Add a note to the specified contact request.
     */
    public function addNote(Request $request, ContactRequest $contactRequest)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $contactRequest->notes .= "\n" . $validated['notes'];
        $contactRequest->save();

        return redirect()->route('admin.contact-requests.show', $contactRequest)
            ->with('success', 'Примечание успешно добавлено.');
    }

    /**
     * Update the status of the specified contact request.
     */
    public function updateStatus(Request $request, ContactRequest $contactRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,in_progress,resolved,closed'
        ]);

        $contactRequest->update($validated);

        return redirect()->route('admin.contact-requests.index')
            ->with('success', 'Статус обращения успешно обновлен.');
    }

    /**
     * Remove the specified contact request.
     */
    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();
        
        return redirect()->route('admin.contact-requests.index')
            ->with('success', 'Обращение успешно удалено.');
    }
}