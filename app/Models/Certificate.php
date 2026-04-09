<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    //
    protected $fillable = ['student_id', 'placement_id', 'uploaded_by', 'file_path', 'original_filename'];
 
    public function student()    { return $this->belongsTo(Student::class); }
    public function placement()  { return $this->belongsTo(Placement::class); }
    public function uploader()   { return $this->belongsTo(User::class, 'uploaded_by'); }
}
