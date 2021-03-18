<?php

namespace App\Http\Controllers;

use App\Models\StudentMark;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class StudentMarkController extends Controller
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
            $query = StudentMark::with('student','term')->select('student_marks.*');  
            return $datatables->eloquent($query)
                ->addColumn('created_at', function (StudentMark $studentMark) {
                    return Carbon::parse($studentMark->created_at)->format('M d, Y h:i A');
                })
                ->addColumn('action', function (StudentMark $studentMark) {
                    return '<a data-toggle="modal" href="javascript" onclick="getStudentMark('.$studentMark->id.')" class="tbl_btn btn_danger" title="Delete"><i class="fa fa-edit"></i></a>
                    <a data-toggle="modal" href="#confirmDelete" data-href="'.route('studentMark.destroy',$studentMark->id ).'" class="tbl_btn btn_danger delete-button" title="Delete"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action','created_at'])
                ->make(true);
        }
        return view('student_mark.list')->with(['terms' => Term::orderBy('name','ASC')->get()->pluck('name','id'), 'students' => Student::get()->pluck('name','id')]);
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
            'student_id'  => 'required|integer',
            'term_id'  => 'required|integer',
            'maths'  => 'required',
            'history'  => 'required',
            'science'  => 'required',
        ]);

        $studentMark = StudentMark::where('student_id',$request->student_id)->where('term_id',$request->term_id)->get();
        if(!$studentMark->count()){
            $total = $request->maths+$request->history+$request->science;
            $request->request->add(['total'=>$total]);

            StudentMark::create($request->all());
            return response()->json('success');
        } else {
            return response()->json(['message'=>'Student marks for the same term exists.'],401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentMark  $studentMark
     * @return \Illuminate\Http\Response
     */
    public function show(StudentMark $studentMark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentMark  $studentMark
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentMark $studentMark)
    {
        return response()->json($studentMark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentMark  $studentMark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentMark $studentMark)
    {
        $this->validate($request, [
            'student_id'  => 'required|integer',
            'term_id'  => 'required|integer',
            'maths'  => 'required',
            'history'  => 'required',
            'science'  => 'required',
        ]);

        $student_mark = StudentMark::where('student_id',$request->student_id)->where('term_id',$request->term_id)->where('id','!=',$studentMark->id)->get();
        
        if(!$student_mark->count()){
            $total = $request->maths+$request->history+$request->science;
            $request->request->add(['total'=>$total]);
            $studentMark->update($request->all());
            return response()->json('success');
        } else {
            return response()->json(['message'=>'Student marks for the same term exists.'],401);
        }
        /* $studentMark->update($request->all());
        return response()->json('success'); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentMark $studentMark
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentMark $studentMark)
    {
        $studentMark->delete();

        return response()->json('success');
    }
}
