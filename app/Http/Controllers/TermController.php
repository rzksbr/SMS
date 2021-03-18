<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\StudentMark;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class TermController extends Controller
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
            $query = Term::select('terms.*');  
            return $datatables->eloquent($query)
                ->addColumn('action', function (Term $term) {
                    return '<a data-toggle="modal" href="javascript" onclick="getTerm('.$term->id.')" class="tbl_btn btn_danger" title="Delete"><i class="fa fa-edit"></i></a>
                    <a data-toggle="modal" href="#confirmDelete" data-href="'.route('term.destroy',$term->id ).'" class="tbl_btn btn_danger delete-button" title="Delete"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('term.list');
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
        ]);

        Term::create($request->all());
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $term)
    {
        return response()->json($term);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        $this->validate($request, [
            'name'  => 'required',
        ]);

        $term->update($request->all());
        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Term $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        $studentMarks = StudentMark::where('term_id',$term->id)->get();
        if(!$studentMarks->count()){
            $term->delete();
            return response()->json('success');
        } else {
            return response()->json(['message'=>'You can\'t delete the term as it is connected with student marks. Please delete the student marks to delete.'],401);
        }
    }
}
