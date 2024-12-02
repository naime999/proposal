@extends('layouts.app')

@section('title', 'Proposal List')
@section('css')
    @include('common.datatable_style')
@endsection
@section('content')
    <div class="container-fluid category-list">

        <!-- Page Heading -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Templates</h1>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Templates</h6>
            </div>
            <div class="card-body row">
                <div class="col-md-3">
                    <ul class="list-group">
                        <button class="list-group-item text-start active" data-cat="0" onclick="showProposals(this)">All
                            Templates</button>
                        @foreach ($categories as $category)
                            <button class="list-group-item text-start" data-cat="{{ $category->id }}"
                                onclick="showProposals(this)">{{ $category->title }}</button>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-9 rounded row m-0" id="proposal-view">
                </div>
            </div>
        </div>
    </div>

    @include('templates.add-modal')

@endsection

@section('scripts')
    <script src="{{ asset('admin/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.js') }}"></script>
    @include('common.datatable_js')
    <script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @include('common.alert_js')
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
                }).then((result) => {
                    $('#addModal').modal('show');
                });
            });
        </script>
    @endif
    <script>
        // ----------------------------------------------------- Datatable
        $(document).ready(function() {
            getProposals(0);
        });

        function showProposals(data) {
            $('.active').removeClass('active');
            $(data).addClass('active');
            var catId = $(data).attr('data-cat');
            getProposals(catId);
        }

        function getProposals(catId) {
            $.ajax({
                url: "{{ route('users.template.get') }}",
                method: "POST",
                data: {
                    cat_id: catId,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var view = '';
                    if(data.proposals.length > 0){
                        data.proposals.forEach(item => {
                            var cover = "{{ asset('') }}" + item.cover;
                            var viewUrl = "{{ route('users.proposal.show', ':slug') }}".replace(':slug', item.slug);
                            var html = '<div class="col-md-6">';
                                html += '<div class="card mb-4">';
                                    html += '<div class="row m-0">';
                                        html += '<div class="col-md-4 p-0">';
                                        html += '    <img src="' + cover + '" class="img-fluid rounded-start" alt="...">';
                                        html += '</div>';
                                        html += '<div class="col-md-8 p-0 position-relative">';
                                            html += '<div class="card-body p-2">';
                                                html += '<h5 class="card-title">'+item.title+'</h5>';
                                                if(item.sections.length > 1){
                                                html += '<p class="card-text"><strong>Sections : </strong>'+item.sections.length+'</p>';
                                                }else{
                                                html += '<p class="card-text"><strong>Section : </strong>'+item.sections.length+'</p>';
                                                }
                                            html += '</div>';
                                            html += '<div class="text-right m-2 position-absolute bottom-0 end-0">';
                                                html += '<button class="btn btn-sm btn-info mr-2" data-id = "'+item.id+'" onClick="useTo(this)">Use to</button>';
                                                html += '<a href="'+viewUrl+'" class="btn btn-sm btn-info" ><i class="fas fa-eye pr-1"></i> View</a>';
                                            html += '</div>';
                                        html += '</div>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                            view += html;
                        });
                    }else{
                        var html = '<div class="col-md-12">';
                            html += '<div class="card mb-4">';
                                html += '<div class="card-body text-center">';
                                    html += '<h5 class="card-title m-0">No Template</h5>';
                                    html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        view += html;
                    }
                    $("#proposal-view").html(view);
                }
            });
        }

        function useTo(data){
            var item = $(data).attr('data-id');
            console.log(item);
            swal.fire({
                title: "Are you sure?",
                text: "Use this proposal",
                icon: "info",
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Create',
            }).then((result) => {
                if (result.value == true) {
                    $('#proposal-duplicate-form').find('input[name="id"]').val(item);
                    $('#addModal').modal('show');
                }
            });
        }

    </script>
@endsection
