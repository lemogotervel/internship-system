<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = ['student_id','placement_id','title','content','file_path','due_date','submitted_at','status','reviewed_by','reviewed_at'];
    protected $casts = ['due_date'=>'date','submitted_at'=>'datetime','reviewed_at'=>'datetime'];
 
    public function student()    { return $this->belongsTo(Student::class); }
    public function placement()  { return $this->belongsTo(Placement::class); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function comments()   { return $this->hasMany(ReportComment::class); }
 
    public function isLate(): bool { return $this->status === 'draft' && $this->due_date->isPast(); }
}
