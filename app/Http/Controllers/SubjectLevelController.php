<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectLevel;

class SubjectLevelController extends Controller
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
        $request->validate([
            'subject' => 'required',
            'grade' => 'required',
        ]);
        $chk = SubjectLevel::where('subject_id', $request->subject)->where('level_of',$request->grade)->first();
        if(isset($chk)){
            $errors = ['errors'=>['subject' => ['Subject has been added!']]];
            return response()->json($errors,422);
        }
        $sl = SubjectLevel::create([
            'subject_id'=>$request->subject,
            'level_of'=>$request->grade,
        ]);

        return response()->json($sl, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sl = SubjectLevel::join('subjects', 'subjects.id', '=', 'subject_per_level.subject_id')
        ->where('level_of', $id)->select(['subject_per_level.*', 'subjects.subject_name'])->orderBy('subjects.id','asc')
        ->get();
        return response()->json($sl, 200);
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
       $sl = SubjectLevel::find($id);
       $sl->delete();
       return response()->json($sl, 200);
    }
}
