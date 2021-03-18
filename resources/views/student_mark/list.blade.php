@extends('layouts.app')

@section('title', 'Student Marks')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Marks</h1>
        <a href="javascript:;" data-toggle="modal" data-target="#studentMarkModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Add Student Marks</a>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Student Marks List</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="studentMarks-list" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="20%">Student</th>
                                    <th width="10%">Maths</th>
                                    <th width="10%">History</th>
                                    <th width="10%">Science</th>
                                    <th width="10%">Term</th>
                                    <th width="10%">Total Marks</th>
                                    <th width="10%">Created On</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Add/Edit Modal-->
    <div class="modal fade" id="studentMarkModal" tabindex="-1" role="dialog" aria-labelledby="studentMark-modal" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentMark-modal">Manage Student</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="studentMark-form" action="{{ route('studentMark.store') }}" method="POST" data-parsley-validate="parsley">
                    <div class="modal-body">
                    <div class="alert alert-danger" role="alert" style="display:none" ></div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label class="form-label">Student</label>
                                <select class="form-control" name="student_id" data-parsley-required>
                                    <option value="">Select Student</option>
                                    @if($students->count())
                                    @foreach($students as $key => $student)
                                        <option value="{{ $key }}">{{ $student }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="tel" name="phone" class="form-control" placeholder="Student's Phone" data-parsley-required data-parsley-type="number" value="" data-parsley-trigger="change"> -->
                                <span class="invalid-feedback error_msg" role="alert" id="studentMark-student_id"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">Term</label>
                                <select class="form-control" name="term_id" data-parsley-required>
                                    <option value="">Select Term</option>
                                    @if($terms->count())
                                    @foreach($terms as $key => $term)
                                        <option value="{{ $key }}">{{ $term }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="tel" name="phone" class="form-control" placeholder="Student's Phone" data-parsley-required data-parsley-type="number" value="" data-parsley-trigger="change"> -->
                                <span class="invalid-feedback error_msg" role="alert" id="studentMark-term_id"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Student's Name" data-parsley-required value="" data-parsley-trigger="change">
                            <span class="invalid-feedback error_msg" role="alert" id="student-name"></span>
                        </div> -->
                        
                        <hr />
                        <h6>Subject Marks</h6>

                        <div class="row">
                            <div class="form-group col-md-3" id="mark-maths">
                                <label class="form-label">Maths</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mark" name="maths" placeholder="00" min="0" max="50" data-parsley-required value="" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="digits" value="0" data-parsley-errors-container="#mark-maths">
                                    <div class="input-group-text">/ 50</div>
                                </div>
                                <span class="invalid-feedback error_msg" role="alert" id="studentMark-maths"></span>
                            </div>
                            <div class="form-group col-md-3" id="mark-history">
                                <label class="form-label">History</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mark" name="history" placeholder="00" value="" min="0" max="50" data-parsley-required value="0" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="digits" data-parsley-errors-container="#mark-history">
                                    <div class="input-group-text">/ 50</div>
                                </div>
                                <span class="invalid-feedback error_msg" role="alert" id="studentMark-history"></span>
                            </div>
                            <div class="form-group col-md-3" id="mark-container">   
                                <label class="form-label">Science</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mark" name="science" placeholder="00" value="" min="0" max="50" data-parsley-required value="0" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="digits" data-parsley-errors-container="#mark-container">
                                    <div class="input-group-text">/ 50</div>
                                </div>
                                <span class="invalid-feedback error_msg" role="alert" id="studentMark-science"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label"><strong>Total</strong></label>
                                <div class="input-group">
                                    <input type="text" name="total" readonly class="form-control" id="totalMark" placeholder="00" value="0">
                                    <div class="input-group-text"><strong>/ 150</strong></div>
                                </div>
                                <span class="invalid-feedback error_msg" role="alert" id="student-name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="delete-modal" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentMark-modal">Confirm Delete</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to delete?</strong>
                    <p class="error-message"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary confirm-delete" href="javascript:;">
                        Confirm
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $studentList= $('#studentMarks-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("studentMark.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'student.name', name: 'student.name' },
                    { data: 'maths', name: 'maths' },
                    { data: 'history', name: 'history' },
                    { data: 'science', name: 'science' },
                    { data: 'term.name', name: 'term.name'},
                    { data: 'total', name: 'total'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'action', orderable: false}
                ]
            });

            $('table').on('click','.delete-button', function(e){
                var deleteUrl = $(this).data('href');
                // console.log(deleteUrl);
                $('.confirm-delete').click(function(){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                        type: 'DELETE',
                        dataType : 'JSON', 
                        url : deleteUrl,
                        success: function(response){ 
                            $('#studentMarks-list').DataTable().ajax.reload();
                            $('.error-message').html('').hide();
                            $('#confirmDelete').modal('hide');
                            toastr.success("Student deleted successfully", "Success");  
                        },
                        error: function(response) {
                            console.log(response);
                            $('.error-message').html(response.message).show();
                        }
                    }); 
                });
            });
        });

        $('.mark').on('change', function(e){
            var totalMark = 0;
            $('.mark').each(function(){
                if($.isNumeric($(this).val()) && $(this).val()<=50)
                    totalMark += parseInt($(this).val());
            });
            $('#totalMark').val(totalMark);
        });

        $('#studentMark-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function(e) {
            event.preventDefault();
            $('.alert').html('').hide();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: $('#studentMark-form').attr('method'),
                url : $('#studentMark-form').attr('action'),
                data: $('#studentMark-form').serialize(),
                success: function(response){ 
                    $('#studentMark-form').attr('method','POST');
                    $('#studentMark-form').attr('action','{{ route('studentMark.store') }}');
                    $('#studentMark-form input[name=history]').val('');
                    $('#studentMark-form input[name=maths]').val('');
                    $('#studentMark-form input[name=science]').val('');
                    $('#studentMark-form select[name=student_id]').val('');
                    $('#studentMark-form select[name=term_id]').val('');
                    $('#studentMarkModal').modal('hide');
                    $('#studentMarks-list').DataTable().ajax.reload();
                    if($('#studentMark-form').attr('method')=='PUT'){
                        toastr.success("Student mark saved successfully", "Success"); 
                    } else {
                        toastr.success("Student mark saved successfully", "Success"); 
                    }
                }, 
                error: function(response) {
                    console.log(response);
                    $('.error_msg').html('').hide();
                    if(response.responseJSON.message){
                        $('.alert').html(response.responseJSON.message).show();
                    }
                    $.each(response.responseJSON.errors, function(key,value) {
                        $('#studentMark-'+key).html(value).show();
                    });
                }
            }); 
        });

        function getStudentMark(studentMarkId){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: 'GET',
                url : '{{ url('studentMark') }}'+'/'+studentMarkId+'/edit',
                success: function(response){ 
                    $('#studentMark-form').attr('method','PUT');
                    $('#studentMark-form').attr('action','{{ url('studentMark') }}'+'/'+studentMarkId);
                    $('#studentMark-form input[name=maths]').val(response.maths);
                    $('#studentMark-form input[name=science]').val(response.science);
                    $('#studentMark-form input[name=history]').val(response.history);
                    $('#studentMark-form select[name=term_id]').val(response.term_id);
                    $('#studentMark-form select[name=student_id]').val(response.student_id);
                    var totalMark = response.maths+response.science+response.history;
                    $('#totalMark').val(totalMark);
                    $('#studentMarkModal').modal('show');
                }
            }); 
        }
    </script>
@endpush

@push('styles')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .parsley-errors-list li{
            color: red;
            font-weight: 300;
            list-style-type: none;
        }
        .parsley-errors-list{
            padding-inline-start: 0;
        }
    </style>
@endpush