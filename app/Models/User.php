<?php

namespace App\Models;

// 1. IMPORT NATIN ANG INTERFACE PARA SA EMAIL VERIFICATION
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 2. DAGDAGAN NG "implements MustVerifyEmail"
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // TAMA ITO, dapat laging nandito ang role
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'password' => 'hashed',
        ];
    }

    /**
     * OPTIONAL: Helper function para i-check kung Admin ang user
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * OPTIONAL: Helper function para i-check kung Cashier
     */
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    /**
     * OPTIONAL: Helper function para i-check kung Chef
     */
    public function isChef(): bool
    {
        return $this->role === 'chef';
    }

    /**
     * OPTIONAL: Helper function para i-check kung Waiter
     */
    public function isWaiter(): bool
    {
        return $this->role === 'waiter';
    }
}