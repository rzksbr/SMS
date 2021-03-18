<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class TeacherController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Datatables $datatables)
    {
        if($request->ajax()){
            $query = Teacher::select('teachers.*');  
            return $datatables->eloquent($query)
                ->addColumn('action', function (Teacher $teacher) {
                    return '<a data-toggle="modal" href="javascript" onclick="getTeacher('.$teacher->id.')" class="tbl_btn btn_danger" title="Delete"><i class="fa fa-edit"></i></a>
                    <a data-toggle="modal" href="#confirmDelete" data-href="'.route('teacher.destroy',$teacher->id ).'" class="tbl_btn btn_danger delete-button" title="Delete"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('teacher.list');
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
        $this->validate($request, [
            'name'  => 'required',
            'phone'  => 'required',
        ]);

        Teacher::create($request->all());
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $this->validate($request, [
            'name'  => 'required',
            'phone'  => 'required',
        ]);

        $teacher->update($request->all());
        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $students = Student::where('teacher_id',$teacher->id)->get();
        if(!$students->count()){
            $teacher->delete();
            return response()->json('success');
        } else {
            return response()->json(['message'=>'You can\'t delete the teacher as it is connected with students. Please delete the students or reassign another teacher.'],401);
        }
    }
}
