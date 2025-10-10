<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'force_password_change',
        'two_factor_attempts',
        'status',
        'max_discount_allowed',
        'first_login',
        'last_login_at',
        'login_attempts',
        'locked_until',
        'phone',
        'notes',
    ];

    protected $casts = [
        'two_factor_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'force_password_change' => 'boolean',
        'first_login' => 'boolean',
        'max_discount_allowed' => 'decimal:2',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Helper methods
    public function isOwner()
    {
        return $this->role && $this->role->name === 'Owner';
    }

    public function isManager()
    {
        return $this->role && $this->role->name === 'Manager';
    }

    public function isStaff()
    {
        return $this->role && $this->role->name === 'Staff';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function canAccessSuppliers()
    {
        return $this->isOwner();
    }

    public function canSeeCosts()
    {
        return $this->isOwner();
    }

    public function canAdjustStock()
    {
        return $this->isManager() || $this->isOwner();
    }

    public function canVoidDocuments()
    {
        return $this->isManager() || $this->isOwner();
    }

    public function canChangeSettings()
    {
        return $this->isOwner();
    }

    // Auto-set discount limits based on role
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->role) {
                switch ($user->role->name) {
                    case 'Staff':
                        $user->max_discount_allowed = 10.00;
                        break;
                    case 'Manager':
                        $user->max_discount_allowed = 25.00;
                        break;
                    case 'Owner':
                        $user->max_discount_allowed = 100.00; // Unlimited
                        break;
                }
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('role_id') && $user->role) {
                switch ($user->role->name) {
                    case 'Staff':
                        $user->max_discount_allowed = 10.00;
                        break;
                    case 'Manager':
                        $user->max_discount_allowed = 25.00;
                        break;
                    case 'Owner':
                        $user->max_discount_allowed = 100.00; // Unlimited
                        break;
                }
            }
        });
    }
}
