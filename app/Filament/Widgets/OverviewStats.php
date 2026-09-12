<?php

namespace App\Filament\Widgets;

use App\Enums\CertificateStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Published courses', Course::query()->where('is_published', true)->count())
                ->description(Course::query()->where('is_published', false)->count().' draft')
                ->color('primary'),

            Stat::make('Pending enrolments', Enrollment::query()->where('status', EnrollmentStatus::Pending)->count())
                ->description(Enrollment::query()->count().' total enquiries')
                ->color('warning'),

            Stat::make('Valid certificates', Certificate::query()->where('status', CertificateStatus::Valid)->count())
                ->description(Certificate::query()->where('status', CertificateStatus::Revoked)->count().' revoked')
                ->color('success'),

            Stat::make('Expiring soon', Certificate::query()->expiringSoon()->count())
                ->description('Valid certificates expiring within 60 days')
                ->color('warning'),
        ];
    }
}
