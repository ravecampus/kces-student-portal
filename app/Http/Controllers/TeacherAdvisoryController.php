<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherAdvisory;
use App\Models\Student;

class TeacherAdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['created_at', 'first_name', 'middle_name', 'last_name', 'lrn'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $archive = $request->archive;
        $advisory = $request->advisory_id;
        $searchValue = $request->search;
        $query = Student::with('account')
        ->join('teacher_advisory','teacher_advisory.student_id','=',"students.id")
        ->where('deleted',$archive)
        ->where('advisory_id',$advisory)->orderBy('students.'.$columns[$column], $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%')
                ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('lrn', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
      
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
        foreach ($request->students as $value) {
           $chk = TeacherAdvisory::where('advisory_id', $request->adviser)
           ->where('student_id', $value['id'])->first();
           if(!isset($chk)){
                $ta = TeacherAdvisory::create([
                    'advisory_id' => $request->adviser,
                    'student_id' => $value['id'],
                ]);
           }
        }
    

        return response()->json([],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        //
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

    public function getStudent(Request $request){
       $student = Student::with('account')
        ->join('teacher_advisory','teacher_advisory.student_id','=',"students.id")
        ->where('deleted',0)
        ->where('teacher_advisory.advisory_id',$request->id)->select(
            ['teacher_advisory.id as teacher_advisory_id',
            'students.*'
            ]
            )->find($request->student_id);
        return response()->json($student, 200);
    }
}
