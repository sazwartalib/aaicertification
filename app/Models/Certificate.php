<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'uuid',
    'certificate_number',
    'recipient_name',
    'ic_number',
    'course_id',
    'course_title',
    'issued_at',
    'expires_at',
    'status',
    'grade',
])]
class Certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expires_at' => 'date',
            'status' => CertificateStatus::class,
        ];
    }

    protected $attributes = [
        'status' => 'valid',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $certificate): void {
            $certificate->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'certificate_number';
    }

    /**
     * Unguessable public URL for the digital certificate, encoded into the printed QR code.
     */
    public function publicUrl(): string
    {
        return route('certificates.show', $this->uuid);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isValid(): bool
    {
        return $this->status === CertificateStatus::Valid
            && (is_null($this->expires_at) || $this->expires_at->isFuture());
    }

    /**
     * The single state shown on the public verification pages.
     *
     * @return 'valid'|'revoked'|'expired'
     */
    public function verificationState(): string
    {
        if ($this->status === CertificateStatus::Revoked) {
            return 'revoked';
        }

        return $this->isExpired() ? 'expired' : 'valid';
    }

    public function isExpired(): bool
    {
        return ! is_null($this->expires_at) && $this->expires_at->isPast();
    }

    /**
     * IC number with all but the last 4 characters masked, safe for public display.
     */
    public function maskedIcNumber(): ?string
    {
        if (is_null($this->ic_number)) {
            return null;
        }

        $visible = substr($this->ic_number, -4);

        return str_repeat('•', max(strlen($this->ic_number) - 4, 0)).$visible;
    }

    public function isExpiringSoon(int $days = 60): bool
    {
        return $this->status === CertificateStatus::Valid
            && ! is_null($this->expires_at)
            && $this->expires_at->isFuture()
            && $this->expires_at->lte(now()->addDays($days));
    }

    #[Scope]
    protected function expiringSoon(Builder $query, int $days = 60): void
    {
        $query->where('status', CertificateStatus::Valid)
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }
}
