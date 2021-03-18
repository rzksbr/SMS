<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Gender;
use App\Models\Teacher;
use App\Models\StudentMark;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class StudentController extends Controller
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
            $query = Student::with('teacher','gender')->select('students.*');  
            return $datatables->eloquent($query)
                /* ->addColumn('age', function (Student $student) {
                    return Carbon::parse($student->dob)->age;
                }) */
                ->addColumn('action', function (Student $student) {
                    return '<a data-toggle="modal" href="javascript" onclick="getStudent('.$student->id.')" class="tbl_btn btn_danger" title="Delete"><i class="fa fa-edit"></i></a>
                    <a data-toggle="modal" href="#confirmDelete" data-href="'.route('student.destroy',$student->id ).'" class="tbl_btn btn_danger delete-button" title="Delete"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('student.list')->with(['teachers' => Teacher::orderBy('name','ASC')->get()->pluck('name','id'), 'genders' => Gender::get()->pluck('name','id')]);
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
            'age' => 'required|integer',
            'gender' => 'required|integer',
            'teacher_id' => 'required|integer',
        ]);

        Student::create($request->all());
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->validate($request, [
            'name'  => 'required',
            'age' => 'required|integer',
            'gender' => 'required|integer',
            'teacher_id' => 'required|integer',
        ]);

        $student->update($request->all());
        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $studentMarks = StudentMark::where('student_id',$student->id)->get();
        if(!$studentMarks->count() ){
            $student->delete();
            return response()->json('success');
        } else {
            return response()->json(['message'=>'You can\'t delete the student as it is connected with student marks. Please delete the student marks to delete.'],401);
        }
    }
}
