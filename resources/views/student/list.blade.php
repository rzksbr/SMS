@extends('layouts.app')

@section('title', 'Students')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students</h1>
        <a href="javascript:;" data-toggle="modal" data-target="#studentModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Add Student</a>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="students-list" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="35%">Name</th>
                                    <th width="10%">Age</th>
                                    <th width="10%">Gender</th>
                                    <th width="25%">Reporting Teacher</th>
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
    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="student-modal" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="student-modal">Manage Student</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="student-form" action="{{ route('student.store') }}" method="POST" data-parsley-validate="parsley">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Student's Name" data-parsley-required value="" data-parsley-trigger="change">
                            <span class="invalid-feedback error_msg" role="alert" id="student-name"></span>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">Age</label>
                                <input type="text" name="age" class="form-control" placeholder="00" min="4" max="50" data-parsley-required value="" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="digits">
                                <span class="invalid-feedback error_msg" role="alert" id="student-age"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-control" name="gender" data-parsley-required>
                                    <option value="">Select</option>
                                    @if($genders->count())
                                    @foreach($genders as $key => $gender)
                                    <option value="{{ $key }}">{{ $gender }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="tel" name="phone" class="form-control" placeholder="Student's Phone" data-parsley-required data-parsley-type="number" value="" data-parsley-trigger="change"> -->
                                <span class="invalid-feedback error_msg" role="alert" id="student-gender"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reporting Teacher</label>
                            <select class="form-control" name="teacher_id" data-parsley-required>
                                <option value="">Select</option>
                                @if($teachers->count())
                                @foreach($teachers as $key => $teacher)
                                    <option value="{{ $key }}">{{ $teacher }}</option>
                                @endforeach
                                @endif
                            </select>
                            <!-- <input type="tel" name="phone" class="form-control" placeholder="Student's Phone" data-parsley-required data-parsley-type="number" value="" data-parsley-trigger="change"> -->
                            <span class="invalid-feedback error_msg" role="alert" id="student-gender"></span>
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
                    <h5 class="modal-title" id="student-modal">Confirm Delete</h5>
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
            $studentList= $('#students-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("student.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'age', name: 'age' },
                    { data: 'gender.name', name: 'gender.name'},
                    { data: 'teacher.name', name: 'teacher.name'},
                    { data: 'action', orderable: false}
                ]
            });

            $('table').on('click','.delete-button', function(e){
                var deleteUrl = $(this).data('href');
                $('.confirm-delete').click(function(){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                        type: 'DELETE',
                        dataType : 'JSON', 
                        url : deleteUrl,
                        success: function(response){ 
                            $('#students-list').DataTable().ajax.reload();
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

        $('#student-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function(e) {
            event.preventDefault();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: $('#student-form').attr('method'),
                url : $('#student-form').attr('action'),
                data: $('#student-form').serialize(),
                success: function(response){ 
                    $('#student-form').attr('method','POST');
                    $('#student-form').attr('action','{{ route('student.store') }}');
                    $('#student-form input[name=name]').val('');
                    $('#student-form input[name=age]').val('');
                    $('#student-form select[name=gender]').val('');
                    $('#student-form select[name=teacher_id]').val('');
                    $('#studentModal').modal('hide');
                    $('#students-list').DataTable().ajax.reload();
                    // console.log($('#student-form').attr('method'));
                    if($('#student-form').attr('method')=='PUT'){
                        toastr.success("Student saved successfully", "Success"); 
                    } else {
                        toastr.success("Student saved successfully", "Success"); 
                    }
                }, 
                error: function(response) {
                    console.log(response);
                    $('.error_msg').html('').hide();
                    $.each(response.responseJSON.errors, function(key,value) {
                        $('#student-'+key).html(value).show();
                    });
                }
            }); 
        });

        function getStudent(studentId){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: 'GET',
                url : '{{ url('student') }}'+'/'+studentId+'/edit',
                success: function(response){ 
                    $('#student-form').attr('method','PUT');
                    $('#student-form').attr('action','{{ url('student') }}'+'/'+studentId);
                    $('#student-form input[name=name]').val(response.name);
                    $('#student-form input[name=age]').val(response.age);
                    $('#student-form select[name=gender]').val(response.gender);
                    $('#student-form select[name=teacher_id]').val(response.teacher_id);
                    $('#studentModal').modal('show');
                    // toastr.success("Coupon  deleted successfully", "Success"); 
                }
            }); 
        }
    </script>
@endpush

@push('styles')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush