<?php

namespace App\Models\Hakim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Exam extends Model
{
    use HasFactory;
    public function studentEnroll()
{
    return $this->belongsTo(StudentEnroll::class, 'student_enroll_id');
}

public function subject()
{
    return $this->belongsTo(Subject::class, 'subject_id');
}
}
