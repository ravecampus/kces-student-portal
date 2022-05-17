<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['created_at', 'first_name', 'middle_name', 'last_name', 'id_number'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $archive = $request->archive;
        $searchValue = $request->search;
        $query = Teacher::with('advise')->with('account')->where('deleted',$archive)->orderBy($columns[$column], $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%')
                ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('id_number', 'like', '%'.$searchValue.'%');
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
        $request->validate([
            'id_number' => 'required|string|unique:teachers,id_number',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            // 'sex' => 'required',
            // 'birthdate' => 'required',
            // 'birthplace' => 'required|string',
        ]);

        $teacher = Teacher::create([
            'id_number'=> $request->id_number,
            'first_name'=> $request->first_name,
            'middle_name'=> $request->middle_name,
            'last_name'=> $request->last_name,
            'suffix'=> $request->suffix,
            'sex'=> $request->sex,
            'birthdate'=>Carbon::parse($request->birthdate)->format('Y-m-d'),
            'birthplace'=> $request->birthplace,
            // 'user_id'=> 0,
        ]);

        return response()->json($teacher, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::with('advise')->with('account')->where('user_id', $id)->firstOrFail();
        return response()->json($teacher, 200);
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
            // 'id_number' => 'required|string|unique:teachers,id_number',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'id_number' => 'required|string',
            // 'sex' => 'required',
            // 'birthdate' => 'required',
            // 'birthplace' => 'required|string',
        ]);

        $teacher = Teacher::find($id);
        
        $teacher->id_number = $request->id_number;
        $teacher->first_name = $request->first_name;
        $teacher->middle_name = $request->middle_name;
        $teacher->last_name = $request->last_name;
        $teacher->suffix = $request->suffix;
        $teacher->sex = $request->sex;
        $teacher->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
        $teacher->birthplace = $request->birthplace;
        $teacher->save();
        
        return response()->json($teacher, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        $teacher->deleted = 1;
        $teacher->save();
        return response()->json($teacher,200);
    }

    public function restore($id){
        $teacher = Teacher::find($id);
        $teacher->deleted = 0;
        $teacher->save();
        return response()->json($teacher,200);
    }

    public function credential(Request $request){

        if(isset($request->account['id'])){
            $request->validate([
                'email' => 'required',
                // 'email' => 'required|string|email:rfc,dns|unique:users,email',
                'username' => 'required',
                'password' => 'required|string|min:6',
            ]);
           $user =  User::find($request->account['id']);
           $user->username = $request->username;
           $user->email = $request->email;
           $user->password = bcrypt($request->password);
            $user->save();
            return response()->json($user,200);
        }else{
            $request->validate([
                
                'email' => 'required|string|email|unique:users,email',
                'username' => 'required||unique:users,username',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'role' => 2,
                'password' => bcrypt($request->password)
            ]);
    
            $teacher = Teacher::find($request->id);
            $teacher->user_id = $user->id;
            $teacher->save();
        }
       return response()->json($user,200);
    }

    public function profile(Request $request){

        if(isset($request->id)){
            $request->validate([
                'email' => 'required',
                // 'email' => 'required|string|email:rfc,dns|unique:users,email',
                'username' => 'required',
                'password' => 'required|string|min:6',
            ]);
           $user =  User::find($request->id);
           $user->username = $request->username;
           $user->email = $request->email;
           $user->password = bcrypt($request->password);
            $user->save();
            return response()->json($user,200);
        }else{
            $request->validate([
                
                'email' => 'required|string|email|unique:users,email',
                'username' => 'required||unique:users,username',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'role' => 2,
                'password' => bcrypt($request->password)
            ]);
    
            $teacher = Teacher::find($request->id);
            $teacher->user_id = $user->id;
            $teacher->save();
        }
       return response()->json($user,200);
    }


    public function passwordChange(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->id);
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();
        $cred = ['username'=>$user->username, 'password'=>$request->password];
        // Auth::attempt($cred);

        return response()->json($user, 200);
        
    }


}
