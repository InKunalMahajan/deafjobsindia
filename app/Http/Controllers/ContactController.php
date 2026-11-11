<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('pages.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:254'],
            'topic' => ['required', 'in:job_enquiry,correction,accessibility,partnership,other'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'string', 'max:255'],
        ]);

        // Honeypot: silently accept automated submissions without sending mail.
        if (filled($data['website'] ?? null)) {
            return back()->with('contact_sent', true);
        }

        $recipient = config('services.contact.recipient');
        $mailer = config('mail.default');

        // Never report success if delivery is not configured.
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)
            || in_array($mailer, ['log', 'array'], true)) {
            return back()->withInput($request->except('website'))
                ->withErrors(['contact' => 'Message delivery is temporarily unavailable. Please try again later.']);
        }

        try {
            Mail::to($recipient)->send(new \App\Mail\ContactMessage(
                $data['name'], $data['email'], $data['topic'], $data['message']
            ));
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput($request->except('website'))
                ->withErrors(['contact' => 'We could not send your message. Please try again later.']);
        }

        return redirect()->route('contact')->with('contact_sent', true);
    }
}
