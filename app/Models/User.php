<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'password',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        if (auth()->user()->type === 'admin') {
            return true;
        }

        return false;
    }

    // redirect route function for different user types
    public function getRedirectRoute(): string
    {
        return match ($this->type) {
            'customer' => 'products/list',
            'supplier' => 'dashboard',
            'manager' => 'dashboard',
        };
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'id');
    }

    public function orderRequests()
    {
        return $this->hasMany(OrderRequest::class, 'customer_id', 'id');
    }

    public function installations()
    {
        return $this->hasMany(Installation::class, 'manager_id', 'id');
    }

    public function maintmaintenanceLog()
    {
        return $this->hasMany(MaintenanceLog::class, 'manager_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
}
