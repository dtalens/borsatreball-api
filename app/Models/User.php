<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as AuthUser;


class User extends AuthUser
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','rol','active', 'activation_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password','activation_token',
    ];

    public function isAlumno(){
       return $this->rol == config('role.alumno');
    }
    public function isEmpresa(){
        return $this->rol == config('role.empresa');
    }
    public function isResponsable(){
        return $this->rol == config('role.responsable');
    }
    public function isAdmin(){
        return $this->rol == config('role.administrador');
    }
}
