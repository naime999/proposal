@extends('layouts.app')

@section('title', 'Proposal List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Proposals</h1>
            <div class="d-flex align-items-center justify-content-between bd-highlight">
                <button class="btn btn-sm btn-primary mr-3" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> New Proposal
                </button>
                <a href="{{ route('users.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i> Export To Excel
                </a>
            </div>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Proposal</h6>

            </div>
            <div class="card-body table-responsive">
                <table id="proposalTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Sub Title</th>
                            <th>Image</th>
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

    @include('proposals.add-modal')

@endsection

@section('scripts')
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    function action(){
        swal.fire({
            title: "Are you sure?",
            text: "You want to delete this Work!",
            icon: "warning",
            showCloseButton: true,
            // showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Delete`,
            // dangerMode: true,
        });
    }
    // ----------------------------------------------------- Datatable
    function showDatatable(){
        var devisData = $('#proposalTable').DataTable({
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            ajax: {
                url: "{{ route('users.proposals.get') }}",
                method: "POST",
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columnDefs: [
                {width: '5%', className: 'text-center', targets: [0] },
                {className: 'text-center', targets: [4] },
                {width: '5%', className: 'text-center', targets: [5] },
            ],
             columns: [
                {
                    className: "dt-control",
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'title', name: 'title'},
                {data: 'sub_title', name: 'sub_title'},
                {data: 'image', name: 'image'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "aaSorting": [],
            language: {
                emptyTable: 'No available work, Please add a work'
            },
        });
        showDatatable();
    }
</script>
@endsection
