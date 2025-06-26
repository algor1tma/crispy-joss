<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'roles',
        'last_login',
    ];
    protected $dates = ['deleted_at', 'last_login'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
    ];

    /**
     * Valid roles for the application
     */
    const VALID_ROLES = ['admin', 'karyawan', 'super'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Soft delete related models when user is soft deleted
            if ($user->karyawan) {
                $user->karyawan->delete();
            }
            if ($user->super) {
                $user->super->delete();
            }
            // if ($user->pelanggan) {
            //     $user->pelanggan->delete();
            // }
        });
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function super()
    {
        return $this->hasOne(Super::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function isAdmin()
    {
        return $this->roles === 'admin';
    }

    public function getProfileData()
    {
        switch ($this->roles) {
            case 'super':
                return $this->super;
            case 'admin':
                return $this->admin;
            case 'karyawan':
                return $this->karyawan;
            default:
                return null;
        }
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->roles === $role;
    }

    /**
     * Check if the user's role is valid
     */
    public function hasValidRole()
    {
        return in_array($this->roles, self::VALID_ROLES);
    }
}
