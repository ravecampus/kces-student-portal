<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advisory;

class AdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'section_id' => 'required',
            'school_year_id' => 'required',
        ]);
        $sub = Advisory::create([
            'section_id'=> $request->section_id,
            'school_year_id'=> $request->school_year_id,
            'teacher_id'=> $request->id,
        ]);

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
            'section_id' => 'required',
            'school_year_id' => 'required',
        ]);
        $sub = Advisory::find($id);
        $sub->section_id = $request->section_id;
        $sub->school_year_id = $request->school_year_id;
        $sub->save();

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
