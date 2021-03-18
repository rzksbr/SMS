@extends('layouts.app')

@section('title', 'Teachers')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teachers</h1>
        <a href="javascript:;" data-toggle="modal" data-target="#teacherModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Add Teacher</a>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Teachers List</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="teachers-list" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="50%">Name</th>
                                    <th width="20%">Contact</th>
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
    <div class="modal fade" id="teacherModal" tabindex="-1" role="dialog" aria-labelledby="teacher-modal" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacher-modal">Manage Teacher</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="teacher-form" action="{{ route('teacher.store') }}" method="POST" data-parsley-validate="parsley">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Teacher's Name" data-parsley-required value="" data-parsley-trigger="change">
                            <span class="invalid-feedback error_msg" role="alert" id="teacher-name"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Teacher's Phone" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-required data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="digits" value="">
                            <span class="invalid-feedback error_msg" role="alert" id="teacher-phone"></span>
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
                    <h5 class="modal-title" id="teacher-modal">Confirm Delete</h5>
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
            $teacherList= $('#teachers-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("teacher.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone'},
                    { data: 'action', orderable: false}
                ]
            });

            $('table').on('click','.delete-button', function(e){
                var deleteUrl = $(this).data('href');
                $('.error-message').html('').hide();
                $('.confirm-delete').click(function(){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                        type: 'DELETE',
                        dataType : 'JSON', 
                        url : deleteUrl,
                        success: function(response){ 
                            $('#teachers-list').DataTable().ajax.reload();
                            $('.error-message').html('').hide();
                            $('#confirmDelete').modal('hide');
                            toastr.success("Teacher deleted successfully", "Success");  
                        },
                        error: function(response) {
                            console.log(response);
                            $('.error-message').html(response.responseJSON.message).show();
                        }
                    }); 
                });
            });
        });

        $('#teacher-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function(e) {
            event.preventDefault();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: $('#teacher-form').attr('method'),
                url : $('#teacher-form').attr('action'),
                data: $('#teacher-form').serialize(),
                success: function(response){ 
                    $('#teacher-form').attr('method','POST');
                    $('#teacher-form').attr('action','{{ route('teacher.store') }}');
                    $('#teacher-form input[name=name]').val('');
                    $('#teacher-form input[name=phone]').val('');
                    $('#teacherModal').modal('hide');
                    $('#teachers-list').DataTable().ajax.reload();
                    if($('#teacher-form').attr('method')=='PUT'){
                        toastr.success("Teacher saved successfully", "Success"); 
                    } else {
                        toastr.success("Teacher saved successfully", "Success"); 
                    }
                    // toastr.success("Coupon  deleted successfully", "Success"); 
                }, 
                error: function(response) {
                    console.log(response);
                    $('.error_msg').html('').hide();
                    $.each(response.responseJSON.errors, function(key,value) {
                        $('#teacher-'+key).html(value).show();
                    });
                }
            }); 
        });

        function getTeacher(teacherId){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: 'GET',
                url : '{{ url('teacher') }}'+'/'+teacherId+'/edit',
                success: function(response){ 
                    $('#teacher-form').attr('method','PUT');
                    $('#teacher-form').attr('action','{{ url('teacher') }}'+'/'+teacherId);
                    $('#teacher-form input[name=name]').val(response.name);
                    $('#teacher-form input[name=phone]').val(response.phone);
                    $('#teacherModal').modal('show');
                    // toastr.success("Coupon  deleted successfully", "Success"); 
                }
            }); 
        }
    </script>
@endpush

@push('styles')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .error-message{ 
            font-size:12;
            color: red;
            margin-top: 15px;
        }
    </style>
@endpush