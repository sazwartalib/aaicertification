<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Validates a Cloudflare Turnstile response token against the siteverify endpoint.
 *
 * The rule passes silently when no secret key is configured so local and test
 * environments are not blocked by a challenge that cannot be solved.
 */
class Turnstile implements ValidationRule
{
    public static function isEnabled(): bool
    {
        return filled(config('services.turnstile.secret_key'))
            && filled(config('services.turnstile.site_key'));
    }

    /**
     * Validation rules for the challenge token, required only when Turnstile is enabled.
     *
     * @return array<int, string|self>
     */
    public static function rules(): array
    {
        return self::isEnabled()
            ? ['required', 'string', new self]
            : ['nullable'];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isEnabled()) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the security check.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post(config('services.turnstile.verify_url'), [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (\Throwable $exception) {
            Log::warning('Turnstile verification request failed.', ['exception' => $exception->getMessage()]);

            $fail('We could not verify the security check. Please try again.');

            return;
        }

        if ($response->failed() || $response->json('success') !== true) {
            $fail('The security check failed. Please try again.');
        }
    }
}
