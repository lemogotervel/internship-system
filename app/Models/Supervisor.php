<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    //
     protected $fillable = ['user_id', 'full_name', 'student_number', 'program', 'phone', 'level'];
 
    public function user()        { return $this->belongsTo(User::class); }
    public function placements()  { return $this->hasMany(Placement::class); }
    public function reports()     { return $this->hasMany(Report::class); }
    public function tasks()       { return $this->hasMany(Task::class); }
    public function evaluations() { return $this->hasMany(Evaluation::class); }
    public function certificates(){ return $this->hasMany(Certificate::class); }
 
    public function activePlacement()
    {
        return $this->placements()->where('status', 'approved')->latest()->first();
}
}