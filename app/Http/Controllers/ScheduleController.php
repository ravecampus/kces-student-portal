<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Advisory;
use App\Models\TeacherAdvisory;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $searchValue = $request->search;
        $query = Schedule::join('subjects', 'subjects.id','=', 'schedules.subject_id')
        ->select(['schedules.*','subjects.subject_name']);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('subjects.subject_name', 'like', '%'.$searchValue.'%');
            });
        }
       
        return response()->json($query->get(), 200);
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
            'subject'=>'required',
            'day'=>'required',
            'time_from'=>'required',
            'time_to'=>'required',
        ]);

        $auth = Auth::id();
        $teacher = Teacher::where('user_id', $auth)->first()->id;
        $ad = Advisory::where('teacher_id', $teacher)->first()->id;
        $tad = TeacherAdvisory::where('advisory_id', $ad)->first()->id;
        $from = $request->time_from;
        $to = $request->time_to;
        $sch = Schedule::create([
            'subject_id'=>$request->subject,
            'sday'=>$request->day,
            'stime_from'=>Carbon::createFromTime($from['hours'], $from['minutes'],$from['seconds']),
            'stime_to'=>Carbon::createFromTime($to['hours'], $to['minutes'], $to['seconds']),
            'teacher_advisory_id'=>$tad,
        ]);
        return response()->json($sch, 200);
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
            'subject'=>'required',
            'day'=>'required',
            'time_from'=>'required',
            'time_to'=>'required',
        ]);
        $from = $request->time_from;
        $to = $request->time_to;

        $sch = Schedule::find($id);
        $sch->subject_id  = $request->subject;
        $sch->sday = $request->day;
        $sch->stime_from = Carbon::createFromTime($from['hours'], $from['minutes'],$from['seconds']);
        $sch->stime_to = Carbon::createFromTime($to['hours'], $to['minutes'], $to['seconds']);
        $sch->save();
    
        return response()->json($sch, 200);
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

    public function getSchedule($id){
        $sch = Schedule::join('subjects', 'subjects.id','=', 'schedules.subject_id')
        ->select(['schedules.*','subjects.subject_name'])
        ->where('schedules.teacher_advisory_id', $id)->get();
        return response()->json($sch,200);
    }
}
