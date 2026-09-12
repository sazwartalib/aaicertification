<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;

class CertificatePrintController extends Controller
{
    public function show(Certificate $certificate): View
    {
        return view('certificates.print', [
            'certificate' => $certificate->load('course'),
        ]);
    }
}
