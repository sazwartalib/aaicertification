<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        Enrollment::create($request->safe()->except('cf-turnstile-response'));

        return redirect()
            ->route('contact')
            ->with('status', 'Thank you for reaching out. A member of our team will respond within one business day.');
    }
}
