<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CertificateStatus;
use App\Enums\EnrollmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                [
                    'label' => 'Published courses',
                    'value' => Course::query()->published()->count(),
                    'hint' => Course::query()->count().' total',
                    'icon' => 'courses',
                ],
                [
                    'label' => 'Pending enrolments',
                    'value' => Enrollment::query()->where('status', EnrollmentStatus::Pending)->count(),
                    'hint' => Enrollment::query()->count().' total',
                    'icon' => 'enrollments',
                ],
                [
                    'label' => 'Valid certificates',
                    'value' => Certificate::query()->where('status', CertificateStatus::Valid)->count(),
                    'hint' => Certificate::query()->expiringSoon()->count().' expiring in 60 days',
                    'icon' => 'certificates',
                ],
                [
                    'label' => 'Categories',
                    'value' => Category::query()->count(),
                    'hint' => 'Programme groupings',
                    'icon' => 'categories',
                ],
            ],
            'recentEnrollments' => Enrollment::query()
                ->with('course:id,title,slug')
                ->latest()
                ->take(8)
                ->get()
                ->map(fn (Enrollment $enrollment): array => [
                    'id' => $enrollment->id,
                    'name' => $enrollment->name,
                    'email' => $enrollment->email,
                    'course' => $enrollment->course?->title,
                    'status' => $enrollment->status->value,
                    'statusLabel' => $enrollment->status->label(),
                    'statusTone' => $enrollment->status->tone(),
                    'createdAt' => $enrollment->created_at->diffForHumans(),
                ]),
            'expiringCertificates' => Certificate::query()
                ->expiringSoon()
                ->orderBy('expires_at')
                ->take(8)
                ->get()
                ->map(fn (Certificate $certificate): array => [
                    'id' => $certificate->id,
                    'number' => $certificate->certificate_number,
                    'recipient' => $certificate->recipient_name,
                    'courseTitle' => $certificate->course_title,
                    'expiresAt' => $certificate->expires_at?->toFormattedDateString(),
                ]),
        ]);
    }
}
