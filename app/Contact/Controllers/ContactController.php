<?php

namespace App\Contact\Controllers;

use App\Contact\Mail\ContactMailable;
use App\Contact\Models\Contact;
use App\Contact\Requests\StoreContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact');
    }

    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        Mail::to(config('mail.from.address'))
            ->send(new ContactMailable($contact));

        return redirect()
            ->route('contact.create')
            ->with('success', __('messages.contact_success'));
    }
}
