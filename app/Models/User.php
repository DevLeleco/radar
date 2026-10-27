<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLE_SYSTEM_ADMIN = 1;

    public const ROLE_CLIENT_USER = 2;

    public const ROLE_TECHNICIAN = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Client::class, 'id_cliente', 'id_cliente');
    }

    public function tecnico()
    {
        return $this->hasOne(\App\Models\Technician::class, 'id_usuario', 'id');
    }

    public function chamados()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'id_usuario', 'id');
    }
}
