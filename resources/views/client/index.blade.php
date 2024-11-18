@extends('layouts.app')

@section('title', 'Client List')
@section('css')
@include('common.datatable_style')
@endsection
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Clients</h1>
            <div class="d-flex align-items-center justify-content-between bd-highlight">
                <button class="btn btn-sm btn-primary mr-3" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Add Client
                </button>
                <a href="{{ route('users.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i> Export To Excel
                </a>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Client</h6>

            </div>
            <div class="card-body table-responsive">
                <table id="clientTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Enlisted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @include('client.add-model')
    @include('client.edit-model')

@endsection

@section('scripts')
    <script src="{{ asset('admin/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.js') }}"></script>
    @include('common.datatable_js')
    <script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let errorMessages = '';
                @foreach($errors->all() as $error)
                    errorMessages += '{{ $error }}<br>';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Field Error',
                    html: errorMessages,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                });
            });
        </script>
    @endif
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                });
            });
        </script>
    @endif
    <script>
        // ----------------------------------------------------- Datatable
        function showDatatable(){
            var devisData = $('#clientTable').DataTable({
                bAutoWidth: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                ajax: {
                    url: "{{ route('users.clients.get') }}",
                    method: "GET",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columnDefs: [
                    {width: '5%', className: 'text-center', targets: [0] },
                    {className: 'text-center', targets: [4] },
                    {className: 'text-center', targets: [5] },
                    {width: '5%', className: 'text-center', targets: [6] },
                ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'enlisted', name: 'enlisted'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "aaSorting": [],
                language: {
                    emptyTable: 'No available client, Please add a client'
                },
            });
        }
        showDatatable();

        function editClient(id){
            var clientId = $(id).attr('data-id');
            $.ajax({
                url: "{{ route('users.client.get') }}",
                method: "GET",
                data: {
                    id: clientId,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#user-edit-form').find('[name="id"]').val(data.id);
                    $('#user-edit-form').find('[name="first_name"]').val(data.first_name);
                    $('#user-edit-form').find('[name="last_name"]').val(data.last_name);
                    $('#user-edit-form').find('[name="email"]').val(data.email);
                    $('#user-edit-form').find('[name="mobile_number"]').val(data.mobile_number);

                    // Dynamically set the status options
                    let statusOptions = `<option value="1" ${data.status == 1 ? 'selected' : ''}>Active</option>
                                        <option value="0" ${data.status == 0 ? 'selected' : ''}>Inactive</option>`;
                    $('#user-edit-form').find('[name="status"]').html(statusOptions);

                    $('#editModal').modal('toggle');
                }
            });
        }

        function deleteClient(id){
            var clientId = $(id).attr('data-id');
            swal.fire({
                title: "Are you sure?",
                text: "You want to delete this client",
                icon: "warning",
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: `Delete`,
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        url: "{{ route('users.client.delete') }}",
                        method: "POST",
                        data: {
                            id: clientId,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#clientTable').DataTable().ajax.reload();
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 1500
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#clientTable').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred. Please try again later.',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500
                            });
                        }
                    });
                }
            });
        }

    </script>
@endsection
