<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskEvaluation extends Model
{
    //
     protected $fillable = ['placement_id', 'student_id', 'created_by', 'title', 'description', 'due_date', 'status'];
    protected $casts    = ['due_date' => 'date'];
 
    public function placement()  { return $this->belongsTo(Placement::class); }
    public function student()    { return $this->belongsTo(Student::class); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function evaluation() { return $this->hasOne(TaskEvaluation::class); }
 
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }
 
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'todo'        => 'bg-slate-100 text-slate-700',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'completed'   => 'bg-green-100 text-green-700',
            default       => 'bg-gray-100 text-gray-700',
        };
     }
}