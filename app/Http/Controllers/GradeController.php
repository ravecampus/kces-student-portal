<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\GradeStatus;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SubjectLevel;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->advisory_id;

        $chk = GradeStatus::where('advisory_id', $id)->first();
        $auth = Auth::id();

        $teach = Teacher::where('user_id', $auth)->first();
      
        $sub = SubjectLevel::where('level_of', $teach->level_of)->get();
        // dd( count($sub) );
        if(count($sub) <= 0 ){
            $errors = ['errors'=>['grade' => ['Subjects are not set at this grade level! Kindly contact the Administrator!']]];
            return response()->json($errors,422);
        }else{
            if(!$chk){
                $gs = GradeStatus::create([
                    'advisory_id'=>$id
                ]);

            
                foreach ($sub as $key => $value) {
                $g = Grade::where('subject_id', $value->id)->where('grade_status_id', $gs->id)->first();
                    if(!isset($g)){
                        Grade::create([
                            'subject_id' =>$value->subject_id,
                            'grade_status_id' => $gs->id
                        ]);
                    }
                }
            }
            return response()->json([], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grade = GradeStatus::with('grade')->where('advisory_id',$id)->first();
        return response()->json($grade, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gr = Grade::find($id);
        $gr->first = $request->first;
        $gr->second = $request->second;
        $gr->third = $request->third;
        $gr->fourth = $request->fourth;
        $gr->average = $request->average;
        $gr->remarks = $request->remarks;
        $gr->save();

        return response()->json($gr, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function studentGrade($id){
        $stud = Student::find($id);
        $grade = GradeStatus::with('grade')->join('advisory', 'advisory.id','=','grade_status.advisory_id')
        ->where('grade_status.advisory_id',$stud->advisory_id)
        ->select(['grade_status.*', 'advisory.teacher_id','advisory.school_year_id', 'advisory.section_id'])
        ->first();
        return response()->json($grade, 200);
    }

    public function finalgrade($id){
        $gs =  GradeStatus::find($id);
        $gs->status = 1;
        $gs->save();
        return response()->json($gs, 200);
    }
    
}
