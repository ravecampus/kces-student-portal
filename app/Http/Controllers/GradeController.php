<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\GradeStatus;
use App\Models\Subject;

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
        $id = $request->id;
        $chk = GradeStatus::where('teacher_advisory_id', $id)->first();
        if(!$chk){
            $gs = GradeStatus::create([
                'teacher_advisory_id'=>$id
            ]);

            $sub = Subject::all();
            foreach ($sub as $key => $value) {
            $g = Grade::where('subject_id', $value->id)->where('grade_status_id', $gs->id)->first();
                if(!isset($g)){
                    Grade::create([
                        'subject_id' =>$value->id,
                        'grade_status_id' => $gs->id
                    ]);
                }
            }
        }
        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grade = GradeStatus::with('grade')->where('teacher_advisory_id',$id)->first();
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
        $grade = GradeStatus::with('grade')->join('teacher_Advisory', 'teacher_Advisory.id','=','grade_status.teacher_advisory_id')
        ->where('teacher_Advisory.student_id', $id)
        ->first();
        return response()->json($grade, 200);
    }
    
}
