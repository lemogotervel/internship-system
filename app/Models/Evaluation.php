<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //
    protected $fillable = ['student_id', 'placement_id', 'evaluated_by', 'grade', 'mention', 'comments'];
    protected $casts    = ['grade' => 'decimal:2'];
 
    public function student()   { return $this->belongsTo(Student::class); }
    public function placement() { return $this->belongsTo(Placement::class); }
    public function evaluator() { return $this->belongsTo(User::class, 'evaluated_by'); }
}
