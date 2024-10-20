<?php

namespace App\Models\Hakim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class StudentEnroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'student_id',
        'session_id',
        'semester_id',
        'section_id',
    ];
    public function exams()
{
    return $this->hasMany(Exam::class, 'student_enroll_id');
}
public function subject()
{
    return $this->belongsTo(Subject::class, 'subject_id');
}
    public function program(): BelongsTo
    {
        return $this->belongsTo(
            program::class,
            'program_id'
        );
    }
    public function getProgramNameAttribute()
    {
        return $this->program ? $this->program->title : null;
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }
    public function getStudentNameAttribute()
    {
        return $this->student;
    }
    public function session(): BelongsTo
    {
        return $this->belongsTo(
            Session::class,
            'session_id'
        );
    }
    public function getSessionNameAttribute()
    {
        return $this->session;
    }
    public function semester(): BelongsTo
    {
        return $this->belongsTo(
            Semester::class,
            'semester_id'
        );
    }
    public function getSemsterNameAttribute()
    {
        return $this->semester;
    }
    public function section(): BelongsTo
    {
        return $this->belongsTo(
            Section::class,
            'section_id'
        );
    }
    public function getSectionNameAttribute()
    {
        return $this->section;
    }
}
