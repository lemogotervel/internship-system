<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'coordinator_code',
        'is_active',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password'=>'hashed',
        'is_active'=>'boolean',
        'email_verified_at' => 'datetime',
    ];

     public function getNameAttribute(): string
    {
        return $this->username;
    }
 
     public function supervisor()   { return $this->hasOne(Supervisor::class); }
 
    public function isStudent()                { return $this->role === 'student'; }
    public function isAcademicSupervisor()     { return $this->role === 'academic_supervisor'; }
    public function isProfessionalSupervisor() { return $this->role === 'professional_supervisor'; }
    public function isCoordinator()            { return $this->role === 'coordinator'; }
 
    public function canViewTasks(): bool
    {
        return in_array($this->role, [
            'academic_supervisor',
            'coordinator',
            'professional_supervisor',
            'student',
        ]);
    }
}
