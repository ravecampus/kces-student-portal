<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\TeacherAdvisory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class StudentController extends Controller
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
        $searchValue = $request->search;
        $query = Student::with('account')->where('deleted',$archive)->orderBy($columns[$column], $dir);
    
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
        $request->validate([
            'lrn'=>'required|unique:students,lrn',
            'first_name'=>'required|string',
            'middle_name'=>'required|string',
            'last_name'=>'required|string',
        ]);
        $student = Student::create([
            'lrn'=>$request->lrn,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'middle_name'=>$request->middle_name,
            'suffix'=>$request->suffix,
            'sex'=>$request->sex,
            'religion'=>$request->religion,
            'civil_status'=>$request->civil_status,
            'address'=>$request->address,
            'nationality'=>$request->nationality,
            'age'=>$request->age,
            'birthdate'=>Carbon::parse($request->birthdate)->format('Y-m-d'),
            'birthplace'=>$request->birthplace,

        ]);

        // TeacherAdvisory::create([
        //     'advisory_id'=>$request->advisory_id,
        //     'student_id'=>$student->id
        // ]);

        return response()->json($student, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $student = Student::where('user_id', $id)->firstOrFail();
        $student = Student::with('account')
        ->join('teacher_advisory','teacher_advisory.student_id','=',"students.id")
        ->join('advisory','advisory.id','=',"teacher_advisory.advisory_id")
        ->where('deleted',0)
        ->where('students.user_id',$id)->select(
            ['teacher_advisory.id as teacher_advisory_id',
            'students.*',
            'advisory.section_id',
            'advisory.school_year_id',
            ]
            )->firstOrFail();
        return response()->json($student, 200);
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
            'lrn'=>'required',
            'first_name'=>'required|string',
            'middle_name'=>'required|string',
            'last_name'=>'required|string',
        ]);
        $student = Student::find($id);
            $student->lrn = $request->lrn;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->middle_name = $request->middle_name;
            $student->suffix = $request->suffix;
            $student->sex = $request->sex;
            $student->religion = $request->religion;
            $student->civil_status = $request->civil_status;
            $student->address = $request->address;
            $student->nationality = $request->nationality;
            $student->age = $request->age;
            $student->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
            $student->birthplace =$request->birthplace;
            $student->save();
            return response()->json($student,200);

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
                'role' => 1,
                'password' => bcrypt($request->password)
            ])->id;

            $stud = Student::find($request->student_id);
            $stud->user_id = $user;
            $stud->save();
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
                'role' => 1,
                'password' => bcrypt($request->password)
            ])->id;

            $stud = Student::find($request->student_id);
            $stud->user_id = $user;
            $stud->save();
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
        // $cred = ['username'=>$user->username, 'password'=>$request->password];
        // Auth::attempt($cred);

        return response()->json($user, 200);
        
    }
}
