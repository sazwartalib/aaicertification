<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->brandPasswordResetMail();
    }

    /**
     * Speak in the application's own voice rather than Laravel's defaults.
     */
    protected function brandPasswordResetMail(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Reset your '.config('app.name').' password')
                ->greeting('Hello '.($notifiable->name ?? '').',')
                ->line('We received a request to reset the password for your '.config('app.name').' admin account.')
                ->action('Choose a new password', route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]))
                ->line('This link expires in '.$minutes.' minutes.')
                ->line('If you did not request a password reset, no action is needed — your password stays unchanged.')
                ->salutation('— The '.config('app.name').' team');
        });
    }
}
