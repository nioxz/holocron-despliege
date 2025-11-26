<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'dni',
        'birthdate',
        'job_title',
        'email',
        'password',
        'role',          // Rol de SST (Supervisor/Trabajador)
        'company_id',    // <--- IMPORTANTE: El ID de la empresa a la que pertenece
        'is_warehouse_admin', // Rol de Almacén (lo usaremos después)
    ];

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

    // --- RELACIONES ---

    // Un usuario PERTENECE A una empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}