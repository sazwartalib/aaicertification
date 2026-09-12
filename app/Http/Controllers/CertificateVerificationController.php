<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'number' => ['nullable', 'string', 'max:60'],
        ]);

        $number = isset($validated['number'])
            ? trim($validated['number'])
            : null;

        $certificate = null;
        $searched = filled($number);

        if ($searched) {
            $certificate = Certificate::query()
                ->with('course')
                ->whereRaw('UPPER(certificate_number) = ?', [mb_strtoupper($number)])
                ->first();
        }

        return view('pages.verify', [
            'number' => $number,
            'searched' => $searched,
            'certificate' => $certificate,
        ]);
    }
}
