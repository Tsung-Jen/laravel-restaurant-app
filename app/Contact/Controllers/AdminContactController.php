<?php

namespace App\Contact\Controllers;

use App\Contact\Models\Contact;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);

        return inertia('Admin/Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['read_at' => now()]);

        return back();
    }
}
