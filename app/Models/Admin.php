<?php

namespace App\Models;

use App\Models\Traits\ClassData;
use App\Models\Traits\Mediable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles, Mediable, ClassData;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pos'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Arreglo de atributos para el manejo de eventos en el Subscriber de Eloquent. que se lanza cuando se elimine el modelo
    protected $relations = [
        'medias' => [
            'constraint' => false,
            'operation' => 'delete'
        ]
    ];

    public function setPasswordAttribute($value)
    {
        if (@$this->attributes['password'] !== $value)
            $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
