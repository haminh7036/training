<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mst_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeActive($query, $status)
    {
        return $query->where('is_active', '=', intval($status));
    }

    public function scopeRole($query, $role)
    {
        return $query->where('group_role', 'like', $role);
    }
}
