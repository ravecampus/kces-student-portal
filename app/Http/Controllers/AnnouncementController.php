<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchValue = $request->search;
        $query = Announcement::where('deleted', 0)
        ->whereDate('expiry_date','>=',Carbon::now()->toDateString());
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('title', 'like', '%'.$searchValue.'%')
                ->orWhere('description', 'like', '%'.$searchValue.'%');
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
            'title'=>'required|string',
            'description'=>'required|string',
            'expiry_date'=>'required|date|after_or_equal:'.Carbon::now()->format('Y-m-d'),
            
        ]);

        $ann = Announcement::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'expiry_date'=>Carbon::parse($request->expiry_date)->format('Y-m-d'),
        ]);

        return response()->json($ann,200);
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
            'title'=>'required|string',
            'description'=>'required|string',
            'expiry_date'=>'required|date|after_or_equal:'.Carbon::now()->format('Y-m-d'),
        ]);

        $ann = Announcement::find($id);
        $ann->title = $request->title;
        $ann->description = $request->description;
        $ann->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
        $ann->save();

        return response()->json($ann,200);
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
}
