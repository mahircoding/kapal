<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, \Spatie\Permission\Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'whatsapp_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function jobApplication()
    {
        return $this->hasOne(JobApplication::class);
    }

    /**
     * Determine if the user has verified their WhatsApp number.
     */
    public function hasVerifiedWhatsApp(): bool
    {
        return !is_null($this->whatsapp_verified_at);
    }

    /**
     * Mark the given user's WhatsApp as verified.
     */
    public function markWhatsAppAsVerified(): bool
    {
        return $this->forceFill([
            'whatsapp_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Get the WhatsApp number that should be used for verification.
     */
    public function getWhatsAppForVerification(): string
    {
        return $this->whatsapp_number;
    }
}
