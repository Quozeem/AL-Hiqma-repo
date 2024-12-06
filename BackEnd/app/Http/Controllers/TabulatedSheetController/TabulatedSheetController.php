<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentEnroll;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\Grade;
use Toastr;
use Auth;
use DB;
use App\Exports\TabulatedSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class TabulatedSheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = trans_choice('module_tabulated_sheet', 1);
        $this->route = 'admin.tabulated.sheet';
        $this->view = 'admin.tabulated-sheet';
        $this->path = 'student';
        $this->access = 'admin-tabulated-sheet';

          
        // $this->middleware('permission:'.$this->access.'-complete');
        // $this->middleware('permission:'.$this->access.'-import', ['only' => ['index','import','importStore']]);

    }
   public function import()
   {
    return view($this->view.'.import');
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;
        $data['access'] = $this->access;
        $data['faculties']=$this->faculty();
         $data['semesters']=$this->semester();
         $data['sections']=$this->section();
         $data['sessions']=$this->session();

         if(!empty($request->faculty) || $request->faculty != null){
            $data['selected_faculty'] = $faculty = $request->faculty;
        }
        else{
            $data['selected_faculty'] = '0';
        }

        if(!empty($request->program) || $request->program != null){
            $data['selected_program'] = $program = $request->program;
        }
        else{
            $data['selected_program'] = '0';
        }

        if(!empty($request->session) || $request->session != null){
            $data['selected_session'] = $session = $request->session;
        }
        else{
            $data['selected_session'] = '0';
        }

        if(!empty($request->semester) || $request->semester != null){
            $data['selected_semester'] = $semester = $request->semester;
        }
        else{
            $data['selected_semester'] = '0';
        }

        if(!empty($request->section) || $request->section != null){
            $data['selected_section'] = $section = $request->section;
        }
        else{
            $data['selected_section'] = '0';
        }
 // Student Filter
        if(!empty($request->faculty) && !empty($request->program) && !empty($request->session) && !empty($request->semester) && !empty($request->section)){

        $filterEnrollments=$this->filterEnrollments($request);
        $data['subjects']=$filterEnrollments['subjects'];
        $data['rows']=$filterEnrollments['data'];
        }

          return view($this->view.'.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function semester()
    {
       return Semester::all(); // Get all semester
    }
    public function section()
    {
       return Section::all(); // Get all section
    }
    public function session()
    {
       return Session::all(); // Get all SESSION
    }
    public function faculty()
{
   return Faculty::all(); // Get all faculties
}
    public function program(Faculty $faculty)
    {

     return $program=Program::where('faculty_id',$faculty->id)->get();

    }
    public function filterEnrollments(Request $request)
    {
      // Get the filters from the request
        $semesterId = $request->input('semester');
        $sessionId = $request->input('session');
        $sectionId =$request->input('section');
        $programId = $request->input('program');


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
                'total_score' => $totalScore,
                'credit_hour' => $subject->credit_hour
            ];
        })->values();

          // Initialize CGPA calculation variables
          $totalWeightedPoints = 0;
          $totalCreditHours = 0;

          // Loop through subjects to calculate weighted points and total credit hours
          foreach ($subjectsWithScores as $subjectWithScore) {
              $totalScore = $subjectWithScore['total_score'];
              $creditHour = $subjectWithScore['credit_hour'];

              // Find the grade based on the subject's total score
              $grade = Grade::where('min_mark', '<=', floor($totalScore))
                  ->where('max_mark', '>=', floor($totalScore))
                  ->first();

              // If a grade is found, calculate the weighted points
              if ($grade) {
                  $gradePoint = $grade->point; // Get the grade point from the grade table
                  $weightedPoints = $gradePoint * $creditHour; // Multiply grade point by credit hour

                  // Add to the total weighted points and total credit hours
                  $totalWeightedPoints += $weightedPoints;
                  $totalCreditHours += $creditHour;
              }
          }

          // Calculate the student's CGPA
          $cpga = $totalCreditHours > 0 ? $totalWeightedPoints / $totalCreditHours : 0;

        //compare CPGA WITH THE GRADE TABLE TO GET THE REMARK FOR THE CPGA
        $grade = Grade::where('point', '<=', $cpga)
        ->orderBy('point', 'desc')
        ->first();

          // Calculate total score for all subjects (this can be used for additional reporting if needed)
          $totalScore = $subjectsWithScores->sum('total_score');
          // Count the number of subjects
          $numberOfSubjects = $subjectsWithScores->count();
          // Calculate the average score
          $averageScore = $numberOfSubjects > 0 ? $totalScore / $numberOfSubjects : 0;

          return [
              'student_id' => $student->student_id,
              'student_name' => $student->first_name . ' ' . $student->last_name,
              'programs' => $programs,
              'subjects' => $subjectsWithScores->toArray(),
              'point' => $totalCreditHours,
              'remark' => isset($grade) ? $grade->title : 'N/A',
              'cpga' => round($cpga, 2) // Return the calculated CGPA rounded to 2 decimal places
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

      // Return the filtered and grouped results as JSON
      return [
          'status' => 200,
          'subjects' => $subjectsArray,
          'data' => $resultsArray
      ];
  }


}
