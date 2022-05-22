<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advisory;
use App\Models\Teacher;
use App\Models\Section;

class AdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adv = Advisory::join('sections', 'sections.id','=', 'advisory.section_id')
            ->join('school_years', 'school_years.id', '=', 'advisory.school_year_id')
            ->select(['advisory.*',
             'sections.level_of',
             'sections.section_name',
             'school_years.sy_name',
             ])->get();

        return response()->json($adv, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
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
            'section' => 'required',
            'school_year' => 'required',
        ]);
        // $filter = Advisory::where('section_id', $request->section)
        // ->where('school_year_id',$request->school_year)->first();

        // if(isset($filter)){
        //     $errors = ['errors'=>['section' => ['Section has been taken!']]];
        //     return response()->json($errors,422);
        // }

        $sub = Advisory::where('section_id', $request->section)
            ->where('school_year_id', $request->school_year)->first();
            
        $sec = Section::find($request->section);
        $teach = Teacher::find($request->id);
        if(!isset($sub)){
            $sub = Advisory::create([
                'section_id'=> $request->section,
                'school_year_id'=> $request->school_year,
                'teacher_id'=> $request->id,
            ]);
        }else{
            $sub->teacher_id =  $request->id;
            $sub->save();
        }

     
        $teach->section_id = $sec->id;
        $teach->level_of = $sec->level_of;
        $teach->advisory_id = $sub->id ;
        $teach->save();
        return response()->json($sub, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $request->validate([
            'section' => 'required',
            'school_year' => 'required',
        ]);

        $sub = Advisory::find($id);
        $sub->section_id = $request->section;
        $sub->school_year_id = $request->school_year;
        $sub->save();

        $sec = Section::find($request->section);
        $teach = Teacher::find($sub->teacher_id);
        $teach->section_id = $sec->id;
        $teach->level_of = $sec->level_of;
        $teach->save();
        return response()->json($sub, 200);
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

    public function getAdvise($id){
        return response()->json(Advisory::where('teacher_id', $id)->first(),200);
    }
}
