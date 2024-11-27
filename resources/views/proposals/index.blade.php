@extends('layouts.app')

@section('title', 'Proposal List')
@section('css')
@include('common.datatable_style')
@endsection
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Proposals</h1>
            <div class="d-flex align-items-center justify-content-between bd-highlight">
                @can('proposal-create')
                    <button class="btn btn-sm btn-primary mr-3" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus"></i> New Proposal
                    </button>
                @endcan
                <a href="{{ route('users.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i> Export To Excel
                </a>
            </div>
        </div>
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
                            <th>Client</th>
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
<script>
    // ----------------------------------------------------- Datatable
    function format ( d ) {
        var count = 0;
        var html = '';
        html += '<div class="row">';
            d.sections.forEach(datarow => {
                console.log(datarow);
                var a = datarow.title;
                count++;
                html += '<div class="col-md-2">'+a+'</div>';
            });
        html += '</div>';
        return html;
    }

    function showDatatable(){
        var devisData = $('#proposalTable').DataTable({
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            ajax: {
                url: "{{ route('users.proposals.get') }}",
                method: "GET",
                data: function (d) {
                    d._token = "{{ csrf_token() }}";
                },
            },
            columnDefs: [
                {width: '5%', className: 'text-center', targets: [0] },
                {className: 'text-center', targets: [3] },
                {width: '5%', className: 'text-center', targets: [4] },
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
                {data: 'client', name: 'client'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "aaSorting": [],
            language: {
                emptyTable: '<div class="py-4 border-1">No available proposal</div>'
            },
        });

        // var customFilter = '<label class="ml-2">aaaa: <select id="statusFilter" class="form-control form-control-sm" name="" style="min-width: 200px;"><option value="">All Statuses</option><option value="approved">Approved</option><option value="pending">Pending</option><option value="rejected">Rejected</option></select></label>';

        // $('.dataTables_filter').append(customFilter);

        $('#proposalTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = devisData.row( tr );
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                // $(tr).next('tr').children().addClass('p-0');
            }
        });
    }
    showDatatable();
</script>
@endsection
