<?php

namespace App\Http\Controllers\Hakim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hakim\Semester;
use App\Models\Hakim\Section;
use App\Models\Hakim\Session;
use App\Models\Hakim\Faculty;
use App\Models\Hakim\Program;
use App\Models\Hakim\Grade;
use App\Models\Hakim\StudentEnroll;
use App\Http\Requests\Hakim\FilterRequest;

class StudentEnrollController extends Controller
{
    //
    public function filterEnrollments(Request $request)
    {
    
        // Get the filters from the request
        $semesterId = $request->input('semester_id');
        $sessionId = $request->input('session_id');
        $sectionId =$request->input('section_id');
        $programId = $request->input('program_id');
 
        // Build the query with the filters
         // Build the query with the filters and load relationships
         $query = StudentEnroll::with(['student', 'exams.subject']) // Load exams and subjects
         ->when($semesterId, function ($q) use ($semesterId) {
             $q->where('semester_id', $semesterId);
         })
         ->when($sessionId, function ($q) use ($sessionId) {
            $q->where('session_id', $sessionId);
        })
        ->when($programId, function ($q) use ($programId) {
            $q->where('program_id', $programId);
        })
         ->when($sectionId, function ($q) use ($sectionId) {
             $q->where('section_id', $sectionId);
         });

     // Retrieve all enrollments that match the filters
     $enrollments = $query->get();

       // Group by student and collect their programs, subjects, and scores for each matching condition
       $results = $enrollments->groupBy('student_id')->map(function ($studentEnrollments) {
        $student = $studentEnrollments->first()->student;
        $programs = $studentEnrollments->pluck('program.title')->unique()->implode(', ');

        $subjectsWithScores = $studentEnrollments->flatMap(function ($enrollment) {
            return $enrollment->exams;
        })->groupBy('subject_id')->map(function ($exams, $subjectId) {
            $subject= $exams->first()->subject; // Assuming 'name' is the subject column
            // $subjectCode= $exams->first()->subject;
            $totalScore = $exams->sum('achieve_marks'); // Sum the scores for the same subject
            return [
                'subject' => $subject->title,
                'shortcode'=>$subject->code,
                'total_score' => $totalScore
            ];
        })->values();

         // Calculate total score for all subjects
         $totalScore = $subjectsWithScores->sum('total_score');
         // Count the number of subjects
         $numberOfSubjects = $subjectsWithScores->count();
         // Calculate the grade (average score)
         $averageScore = $numberOfSubjects > 0 ? $totalScore / $numberOfSubjects : 0;
// Find the grade based on the average score
$grade = Grade::where('min_mark', '<=', floor($averageScore))
->where('max_mark', '>=', floor($averageScore))
->first();
            return [
                'student_id' => $student->student_id,
                'student_id' => $student->student_id,
                'student_name' => $student->first_name.' '. $student->last_name,
                'programs' => $programs,
                'subjects' => $subjectsWithScores->toArray(),
               'averageScore' => round($averageScore, 2),
               'remark' => $grade ? $grade->title : 'N/A',
               'cpga' => $grade ? $grade->point : 'N/A',
            ];
        });

        // Convert the result from collection to a simple array
        $resultsArray = $results->values()->toArray();
        $subjectsArray = [];
      
        // Iterate over each student's data to extract subjects
        foreach ($resultsArray as $student) {
            foreach ($student['subjects'] as $subject) {
                if (!in_array(['subject' => $subject['subject'], 'shortcode' => $subject['shortcode']], $subjectsArray)) {
                    $subjectsArray[] = ['subject' => $subject['subject'], 'shortcode' => $subject['shortcode']];
                }
            }
        }
        if(is_null($programId))
        {
            $resultsArray= null;
            $resultsArray=null;
        }
        
        // Return the filtered and grouped results as JSON
        return view('hakim.index',
        [
           'faculties'=>$this->faculty(),
           'semesters'=>$this->semester(),
           'sections'=>$this->section(),
           'sessions'=>$this->session(),
            'status'=>200,
            'subjects' => $subjectsArray,
            'data'=>$resultsArray
        ]);
   
    }
    public function hakim(Request $request)
    {
        $filterEnrollments=$this->filterEnrollments($request);
        return view('hakim.index',
        [
           'faculties'=>$this->faculty(),
           'semesters'=>$this->semester(),
           'sections'=>$this->section(),
           'sessions'=>$this->session(),
        'subjects' =>$filterEnrollments['subject'] ?? null, 
        'data'=>$filterEnrollments['data'] ?? null
    ]);
    }
    public function semester()
    {
       return Semester::all(); // Get all faculties
    }
    public function section()
    {
       return Section::all(); // Get all faculties
    }
    public function session()
    {
       return Session::all(); // Get all faculties
    }
    public function faculty()
{
   return Faculty::all(); // Get all faculties
}
    public function program(Faculty $faculty)
    {
        
     return $program=Program::where('faculty_id',$faculty->id)->get();
       
    }
}
