@extends('layouts.app')

@section('title', 'Terms')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Terms</h1>
        <a href="javascript:;" data-toggle="modal" data-target="#termModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Add Term</a>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Terms List</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="terms-list" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="70%">Name</th>
                                    <!-- <th>Contact</th> -->
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
    <div class="modal fade" id="termModal" tabindex="-1" role="dialog" aria-labelledby="term-modal" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="term-modal">Manage Term</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="term-form" action="{{ route('term.store') }}" method="POST" data-parsley-validate="parsley">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Term Name" data-parsley-required value="" data-parsley-trigger="change">
                            <span class="invalid-feedback error_msg" role="alert" id="term-name"></span>
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
                    <h5 class="modal-title" id="term-modal">Confirm Delete</h5>
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
            $termList= $('#terms-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("term.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
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
                            $('#terms-list').DataTable().ajax.reload();
                            $('.error-message').html('').hide();
                            $('#confirmDelete').modal('hide');
                            toastr.success("Term deleted successfully", "Success");  
                        },
                        error: function(response) {
                            console.log(response);
                            $('.error-message').html(response.message).show();
                        }
                    }); 
                });
            });
        });

        $('#term-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function(e) {
            event.preventDefault();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: $('#term-form').attr('method'),
                url : $('#term-form').attr('action'),
                data: $('#term-form').serialize(),
                success: function(response){ 
                    $('#term-form').attr('method','POST');
                    $('#term-form').attr('action','{{ route('term.store') }}');
                    $('#term-form input[name=name]').val('');
                    $('#term-form input[name=phone]').val('');
                    $('#termModal').modal('hide');
                    $('#terms-list').DataTable().ajax.reload();
                    if($('#term-form').attr('method')=='PUT'){
                        toastr.success("Term saved successfully", "Success"); 
                    } else {
                        toastr.success("Term saved successfully", "Success"); 
                    }
                    // toastr.success("Term added successfully", "Success"); 
                }, 
                error: function(response) {
                    console.log(response);
                    $('.error_msg').html('').hide();
                    $.each(response.responseJSON.errors, function(key,value) {
                        $('#term-'+key).html(value).show();
                    });
                }
            }); 
        });

        function getTerm(termId){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                dataType : 'JSON', 
                type: 'GET',
                url : '{{ url('term') }}'+'/'+termId+'/edit',
                success: function(response){ 
                    $('#term-form').attr('method','PUT');
                    $('#term-form').attr('action','{{ url('term') }}'+'/'+termId);
                    $('#term-form input[name=name]').val(response.name);
                    $('#term-form input[name=phone]').val(response.phone);
                    $('#termModal').modal('show');
                    // toastr.success("Coupon  deleted successfully", "Success"); 
                }
            }); 
        }
    </script>
@endpush

@push('styles')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush