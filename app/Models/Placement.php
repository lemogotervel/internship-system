<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    //
    protected $fillable = [
        'student_id','company_name','company_address','company_supervisor_name',
        'company_supervisor_email','company_supervisor_phone',
        'start_date','end_date','description','status','validated_by','validated_at','rejection_reason'
    ];
    protected $casts = ['start_date'=>'date','end_date'=>'date','validated_at'=>'datetime'];
 
    public function student()     { return $this->belongsTo(Student::class); }
    public function validatedBy() { return $this->belongsTo(User::class, 'validated_by'); }
    public function reports()     { return $this->hasMany(Report::class); }
    public function tasks()       { return $this->hasMany(Task::class); }
    public function evaluations() { return $this->hasMany(Evaluation::class); }
    public function certificate() { return $this->hasOne(Certificate::class); }
    public function supervisors() { return $this->belongsToMany(Supervisor::class, 'supervisor_student')->withPivot('type')->withTimestamps(); }
 
    public function isPending()  { return $this->status === 'pending'; }
    public function isApproved() { return $this->status === 'approved'; }
    public function isRejected() { return $this->status === 'rejected'; } 
}
