<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CertificateStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CertificateRequest;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CertificateController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();

        return Inertia::render('Admin/Certificates/Index', [
            'filters' => ['search' => $search, 'status' => $status],
            'statuses' => CertificateStatus::options(),
            'certificates' => Certificate::query()
                ->when($search !== '', fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('certificate_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('ic_number', 'like', "%{$search}%")))
                ->when($status !== '', fn ($query) => $query->where('status', $status))
                ->latest('issued_at')
                ->paginate(15)
                ->withQueryString()
                ->through(fn (Certificate $certificate): array => [
                    'id' => $certificate->id,
                    'certificateNumber' => $certificate->certificate_number,
                    'recipientName' => $certificate->recipient_name,
                    'maskedIcNumber' => $certificate->maskedIcNumber(),
                    'courseTitle' => $certificate->course_title,
                    'issuedAt' => $certificate->issued_at->toFormattedDateString(),
                    'expiresAt' => $certificate->expires_at?->toFormattedDateString(),
                    'isExpiringSoon' => $certificate->isExpiringSoon(),
                    'status' => $certificate->status->value,
                    'statusLabel' => $certificate->status->label(),
                    'statusTone' => $certificate->status->tone(),
                    'grade' => $certificate->grade,
                    'editUrl' => route('admin.certificates.edit', $certificate),
                    'deleteUrl' => route('admin.certificates.destroy', $certificate),
                    'printUrl' => route('certificates.print', $certificate),
                    'publicUrl' => $certificate->publicUrl(),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Certificates/Form', [
            'certificate' => null,
            ...$this->formOptions(),
        ]);
    }

    public function store(CertificateRequest $request): RedirectResponse
    {
        Certificate::create($request->validated());

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Certificate issued.');
    }

    public function edit(Certificate $certificate): Response
    {
        return Inertia::render('Admin/Certificates/Form', [
            'certificate' => [
                ...$certificate->only([
                    'id', 'certificate_number', 'recipient_name', 'ic_number',
                    'course_id', 'course_title', 'grade',
                ]),
                'issued_at' => $certificate->issued_at->toDateString(),
                'expires_at' => $certificate->expires_at?->toDateString(),
                'status' => $certificate->status->value,
                'printUrl' => route('certificates.print', $certificate),
                'publicUrl' => $certificate->publicUrl(),
            ],
            ...$this->formOptions(),
        ]);
    }

    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($request->validated());

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Certificate updated.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Certificate deleted.');
    }

    /**
     * @return array{courses: array<int, array{value: int, label: string}>, statuses: array<int, array{value: string, label: string}>}
     */
    protected function formOptions(): array
    {
        return [
            'courses' => Course::query()
                ->ordered()
                ->get(['id', 'title'])
                ->map(fn (Course $course): array => ['value' => $course->id, 'label' => $course->title])
                ->all(),
            'statuses' => CertificateStatus::options(),
        ];
    }
}
