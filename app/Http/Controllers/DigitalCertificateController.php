<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;

class DigitalCertificateController extends Controller
{
    /**
     * The public digital certificate reached by scanning the printed QR code.
     *
     * The UUID is unguessable and the page is marked noindex, so the link acts
     * as the credential without exposing recipients to search engines.
     */
    public function show(Certificate $certificate): View
    {
        return view('pages.certificates.show', [
            'certificate' => $certificate->load('course'),
            'state' => $certificate->verificationState(),
        ]);
    }
}
