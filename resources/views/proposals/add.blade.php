@extends('layouts.app')

@section('title', 'Builder')
@section('css')
<link href="{{ asset('fornts/sacramento.css') }}" rel="stylesheet">
<link href="{{ asset('admin/vendor/signature_pad/css/signature-pad.css') }}" rel="stylesheet">
<style>
    .section-title {
        font-family: "Poppins", sans-serif;
        font-weight: bold !important;
        color: rgb(5, 3, 77);
        font-weight: 400;
        font-size: 36px;
        line-height: 1.2;
    }
    .section-description {
        font-family: "Poppins", sans-serif;
        color: rgb(5, 3, 77);
        font-weight: 400;
        font-size: 16px;
    }
    .defult-signature {
        position: absolute;
        left: 50%;
        width: 100%;
        height: 100%;
        transform: translateX(-50%);
        font-family: Sacramento,sans-serif;
        font-size: 40px;
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        line-height: .65em;
    }

</style>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
@endsection
@section('content')
    <div class="container-fluid" style="">
        <div class="d-flex">
            <!-- Sidebar Menu -->
            <div class="">
                <div class="collapse bg-white" id="sidebarMenu" style="width: 250px;">
                    <ul class="list-group sections-menu">
                        <li class="list-group-item">Headline</li>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-grow-1">
                <div class="text-center d-flex justify-content-center">
                    <div class="border" id="builder-view" style="width: 900px; max-width: 900px; background-color: white;">
                        <div class="heading">
                            <div class="position-relative" id="header" style="background: rgb(9,27,87); background: linear-gradient(180deg, rgba(9,27,87,1) 0%, rgba(83,120,239,1) 100%); height:1250px;">
                                <div class="position-absolute top-0 start-0 m-5 p-3 rounded" style="background: rgba(255, 255, 255, 0.549); max-width: 30%;" >
                                    <img data-key="logo" src="{{ asset(getSetting('proposal-logo')->value) }}" width="100%" />
                                </div>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h1 class="text-white text-uppercase font-weight-bold pro-data" data-key="title">{{ $proposal->title }}</h1>
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-5 text-left">
                                    <p class="text-white text-uppercase font-weight-bold pro-data m-0">To</p>
                                    <p class="text-white pro-data" data-key="name">{{ $proposal->client->first_name . " " . $proposal->client->last_name }}</p>
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-5 text-left">
                                    <p class="text-white text-uppercase font-weight-bold pro-data m-0">Contract</p>
                                    <p class="text-white pro-data" data-key="email">{{ $proposal->client->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="sections">
                            @foreach ($proposal->sections as $section)
                                <div class="section position-relative pb-4" id="section-{{ $section->id }}"  data-id="{{ $section->id }}">
                                    <div class="row m-0">
                                        <div class="col-md-4 p-3">
                                            <p class="m-0 text-start text-capitalize section-title" data-key="title">{{ $section->title }}</p>
                                            <p class="m-0 text-start fs-5" data-key="sub_title">{{ $section->sub_title }}</p>
                                        </div>
                                        <div class="col-md-8 p-3 text-start section-description" data-key="description">{!! $section->description !!}</div>
                                    </div>
                                    @can('proposal-create', 'proposal-edit')
                                    <div class="add-section-btn position-absolute bottom-0 end-0 btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addSection(this)" data-id="{{ $section->id }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="editSection(this)" data-id="{{ $section->id }}">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="deleteSection(this)" data-id="{{ $section->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                        <hr class="m-0">
                        <div class="row m-0 my-3" id="signature">
                            <div class="col-md-4 p-3">
                                <p class="m-0 text-start text-capitalize section-title">Signature</p>
                            </div>
                            <div class="col-md-8 p-3 row">
                                <div class="col-md-6">
                                    <div class="position-relative signature-view d-flex justify-content-center align-items-center" data-control="{{ Auth()->user()->id == $proposal->user_id?'true':'false' }}" style="height: 80px;">
                                        @if ($proposal->adminSignature)
                                            @if ($proposal->adminSignature->image != null)
                                                <img class="h-100 sig-image-admin" src="{{ asset($proposal->adminSignature->image) }}" alt="{{ $proposal->adminSignature->title }}">
                                            @else
                                                <span class="defult-signature sig-title-admin">{{ $proposal->adminSignature->title }}</span>
                                            @endif
                                            @if (Auth()->user()->id == $proposal->user_id)
                                                <button type="button" class="signature-edit btn btn-sm btn-secondary position-absolute bottom-0 end-0" onclick="updateSignature(this)" data-id="{{ $proposal->id }}" user-type="1"><i class="fas fa-pen"></i> Edit</button>
                                            @endif
                                        @else
                                            @if (Auth()->user()->id == $proposal->user_id)
                                                <button class="btn btn-sm btn-primary" onclick="updateSignature(this)" data-id="{{ $proposal->id }}" user-type="1">Add Signature</button>
                                            @endif
                                        @endif
                                    </div>
                                    <hr class="m-0">
                                    <div class="fs-5 text-capitalize section-title p-3">
                                        @if ($proposal->adminSignature)
                                            {{ $proposal->adminSignature->title }}
                                        @else
                                            {{ $proposal->creator->first_name.' '.$proposal->creator->last_name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative signature-view d-flex justify-content-center align-items-center" data-control="{{ Auth()->user()->id == $proposal->client_id?'true':'false' }}" style="height: 80px;">
                                        @if ($proposal->clientSignature)
                                            @if ($proposal->clientSignature->image != null)
                                                <img class="h-100 sig-image-client" src="{{ asset($proposal->clientSignature->image) }}" alt="{{ $proposal->clientSignature->title }}">
                                            @else
                                                <span class="defult-signature sig-title-client">{{ $proposal->clientSignature->title }}</span>
                                            @endif
                                            @if (Auth()->user()->id == $proposal->client_id)
                                                <button type="button" class="signature-edit btn btn-sm btn-secondary position-absolute bottom-0 end-0" onclick="updateSignature(this)" data-id="{{ $proposal->id }}" user-type="2"><i class="fas fa-pen"></i> Edit</button>
                                            @endif
                                        @else
                                            @if (Auth()->user()->id == $proposal->client_id)
                                                <button class="btn btn-sm btn-primary" onclick="updateSignature(this)" data-id="{{ $proposal->id }}" user-type="2">Add Signature</button>
                                            @endif
                                        @endif
                                    </div>
                                    <hr class="m-0">
                                    <div class="fs-5 text-capitalize section-title p-3 sig-client">
                                        @if ($proposal->clientSignature)
                                            {{ $proposal->clientSignature->title }}
                                        @else
                                            {{ $proposal->client->first_name.' '.$proposal->client->last_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('proposals.add-section-modal')
    @include('proposals.add-signature-modal')
@endsection

@section('scripts')
<script>
    load.show();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin/vendor/cropper/cropper.js') }}"></script>
<script src="{{ asset('admin/vendor/signature_pad/js/signature_pad.js') }}"></script>
<script>
// Loader
// loader.initLoaderAll();
// ------
CKEDITOR.replace('editor');
function loadData(){
    console.log("----- Load Proposal Data -----");
    // load.show();
    $.ajax({
        url: "{{ route('users.proposal.load', $proposal->slug) }}",
        method: "get",
        dataType: 'json',
        success: function(data) {
            console.log("Full Data",data);
            clientInfoHtml(data.client);
            sectionsHtml(data.sections);
            builderInit(data);
            load.hide();
        }
    });
}
loadData();

function clientInfoHtml(client){
    var html = "";
    html +="<span><strong>Client Name : </strong></span>"+client.first_name+" "+client.last_name+"<br>";
    html +="<span><strong>Email : </strong></span>"+client.email;
    $('.client-info').html(html);
}

function sectionsHtml(sections){
    $('.sections-menu').html('');
    $('.sections-menu').append('<a href="#header" class="list-group-item list-group-item-action list-group-item-primary">Heading</li>');
    sections.forEach(section => {
         $('.sections-menu').append('<a href="#section-'+section.id+'" class="list-group-item list-group-item-action list-group-item-primary">'+section.title+'</li>');
    });
    $('.sections-menu').append('<a href="#signature" class="list-group-item list-group-item-action list-group-item-primary">Signature</li>');
}

function builderInit(data){
    addHeading(data);
    addSections(data);
    addSignature(data);
}

function addHeading(data){
    if(data.cover != null){
        var coverUrl = '{{ asset('') }}' + data.cover;
        var bgCss = {"background-image":'url('+coverUrl+')', "height":'1250px'};
    }else{
        var bgCss = {"background": "rgb(9,27,87)", "background": "linear-gradient(180deg, rgba(9,27,87,1) 0%, rgba(83,120,239,1) 100%)", "height":'1250px'};
    }
    var logo = "{{ asset(getSetting('proposal-logo')->value) }}";

    var html = '';
    html += '<div class="position-relative" id="header">';
        html += '<div class="position-absolute top-0 start-0 m-5 p-3 rounded" style="background: rgba(255, 255, 255, 0.549); max-width: 30%;" ><img data-key="logo" src="'+logo+'" width="100%" /></div>';
        @can('proposal-create', 'proposal-edit')
        html += '<div class="position-absolute top-50 start-50 translate-middle"><h1 class="text-white text-uppercase font-weight-bold pro-data" data-key="title" contenteditable="true">'+data.title+'</h1></div>';
        @endcan
        @cannot('proposal-create', 'proposal-edit')
         html += '<div class="position-absolute top-50 start-50 translate-middle"><h1 class="text-white text-uppercase font-weight-bold pro-data" data-key="title" >'+data.title+'</h1></div>';
        @endcannot
        html += '<div class="position-absolute bottom-0 start-0 m-5 text-left">'+
            '<p class="text-white text-uppercase font-weight-bold pro-data m-0">To</p>'+
            '<p class="text-white pro-data" data-key="name">'+data.client.first_name+' '+data.client.last_name+'</p>'+
        '</div>';
        html += '<div class="position-absolute bottom-0 end-0 m-5 text-left">'+
            '<p class="text-white text-uppercase font-weight-bold pro-data m-0">Contract</p>'+
            '<p class="text-white pro-data" data-key="email">'+data.client.email+'</p>'+
        '</div>';
    html += '</div>';
    $('.heading').html(html).find('#header').css(bgCss);
}

function addSections(data){
    $('.sections').html("");
    var html = '';
    data.sections.forEach(section => {
        htmlJoin = '';
        htmlJoin += '<div class="section position-relative pb-4" id="section-'+section.id+'" data-id="'+section.id+'" >'+
            '<div class="row m-0">'+
            '    <div class="col-md-4 p-3">'+
            '        <p class="m-0 text-start text-capitalize section-title" data-key="title">'+section.title+'</p>'+
            '        <p class="m-0 text-start fs-5" data-key="sub_title">'+section.sub_title+'</p>'+
            '    </div>'+
            '    <div class="col-md-8 p-3 text-start section-description"  data-key="description">'+section.description+'</div>'+
            '</div>';
            @can('proposal-create', 'proposal-edit')
            if(data.status != 2){
                htmlJoin += '<div class="add-section-btn position-absolute bottom-0 end-0 btn-group" role="group" aria-label="Basic example">'+
                '    <button type="button" class="btn btn-sm btn-primary" onclick="addSection(this)" data-id="'+section.id+'"><i class="fas fa-plus"></i></button>'+
                '    <button type="button" class="btn btn-sm btn-primary" onclick="editSection(this)" data-id="'+section.id+'"><i class="fas fa-pen"></i></button>'+
                '    <button type="button" class="btn btn-sm btn-primary" onclick="deleteSection(this)" data-id="'+section.id+'"><i class="fas fa-trash"></i></button>'+
                '</div>';
            }
            @endcan
        htmlJoin += '</div>';
        html += htmlJoin;
    });
    $('.sections').append(html);
    initSection(data);
}

function addSignature(data){
    console.log("Admin Signatures", data.admin_signature);
    if(data.admin_signature.title != null){
        $(".sig-title-admin").text(data.admin_signature.title);
    }
    if(data.admin_signature.image != null){
        $(".sig-image-admin").attr('src', "{{ asset('') }}"+data.admin_signature.image);
    }

    if(data.client_signature != null){
        console.log("Client Signatures", data.client_signature);
        if(data.client_signature.title != null){
            $(".sig-title-client").text(data.client_signature.title);
        }
        if(data.client_signature.image != null){
            $(".sig-image-client").attr('src', "{{ asset('') }}"+data.client_signature.image);
        }
        console.log("Client Info", data.client);
        if(data.client_signature.title != null){
            $(".sig-client").text(data.client_signature.title);
        }else{
            $(".sig-client").text(data.client.first_name+" "+data.client.last_name);
        }
    }
}

function saveData(save){
    load.show();
    var proData = {};
    proData["_token"] = "{{ csrf_token() }}";
    proData["id"] = $(save).attr('data-id');
    $(document).find('.pro-data').each(function(){
        proData[$(this).attr('data-key')] = $(this).text();
    });
    // console.log(proData);
    $.ajax({
        url: "{{ route('users.proposal.update') }}",
        method: "POST",
        data: proData,
        dataType: 'json',
        success: function(data) {
            load.hide();
            if(data.status == 'success'){
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }else{
                Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }
            console.log(data);
        }
    });
}

function initSection(data){
    @can('proposal-create', 'proposal-edit')
        if(data.status != 2){
            $(".sections").find('.section').each(function(){
                $(this).find('.add-section-btn').hide();
                $(this).hover(function(){
                    $(this).toggleClass("border border-primary");
                    $(this).find('.add-section-btn').toggle();
                });
            });
        }
    @endcan
}
initSection();

function initSignature(){
    $(document).find('.signature-view').each(function(){
        $(this).find('.signature-edit').hide();
        $(this).hover(function(){
            let isControlEnabled = $(this).attr('data-control') === 'true' || $(this).attr('data-control') === '1';
            isControlEnabled = Boolean(JSON.parse(isControlEnabled));
            if(isControlEnabled){
                $(this).toggleClass("border border-secondary");
                $(this).find('.signature-edit').toggle();
            }
        });
    });
}
initSignature();

function updateSignature(sigData){
    var proposalId = $(sigData).attr('data-id');
    var userType = $(sigData).attr('user-type');

    $('#signature-form').find('#userType').val(userType);
    $('#signature-form').find('#proposalId').val(proposalId);

    // get user signeture
    $.ajax({
        url: "{{ route('users.signature.get') }}",
        method: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            proposal_id: proposalId,
            user_type: userType,
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.statusCode);
            getAndSetSignatureData(data);
        },
        error: function(){
            $('#signature-form').find('.defult-signature').text("Please type a signature");
        }
    });
    // ------

    $('#signature-form').find('.imageUpload').hide();
    $('#signature-form').find('.sig-view').hide();
    var selectedId = $('#signature-form').find('input:checked').attr('data-show');
    $(selectedId).toggle();

    $('#signatureModal').modal('toggle');
}

function getAndSetSignatureData(data){
    if(data.title != null){
        $('#signature-form').find('.defult-signature').text(data.title);
    }else{
        $('#signature-form').find('.defult-signature').text("Please type a signature");
    }
    var oldImage = '{{ asset('') }}'+data.image;
    $('#uploading').css({
        "background-image": "url('" + oldImage + "')",
        "background-size": "contain",
        "background-position": "center",
        "background-repeat": "no-repeat"
    });
}


$('#signature-form').find('input[name="sig_type"]').on('change', function() {
    $('#signature-form').find('.sig-view').each(function(){
        $(this).hide();
    });

    var selectedValue = $(this).val();
    var selectedId = $(this).attr('data-show');
    $(selectedId).toggle();

    // console.log("Selected Signature Option:", selectedValue);
    if (selectedValue === "1") {
        console.log("Showing Id : ", selectedId);
    } else if (selectedValue === "2") {
        console.log("Showing Id : ", selectedId);
        $(selectedId).hover(function(){
            $(this).find('.imageUpload').toggle();
        });
    } else if (selectedValue === "3") {
        console.log("Showing Id : ", selectedId);
        window.onresize = resizeCanvas;
        resizeCanvas();
    }
});

$('#imageUpload').on('change', function(event) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var base64Image = e.target.result;
        $("#baseImage").val(base64Image);
        $('#uploading').css({
            "background-image": "url('"+base64Image+"')",
            "background-size": "contain",
            "background-position": "center",
            "background-repeat": "no-repeat"
        });
    };
    reader.readAsDataURL(event.target.files[0]);
});


function addSection(sectionData) {
    var sectionId = $(sectionData).attr('data-id');
    $.ajax({
        url: "{{ route('users.section.get') }}",
        method: "GET",
        data: {
            id: sectionId,
            "_token": "{{ csrf_token() }}"
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('.modal-title').text("Create a new section");
            $('#section-form').attr('action', "{{ route('users.section.add') }}");
            $('#section-form').find('[name="title"]').val('');
            $('#section-form').find('[name="sub_title"]').val('');
            if (CKEDITOR.instances.editor) {
                CKEDITOR.instances.editor.setData();
            }
            $('#section-form').find('[name="status"]').html('<option value="1" selected>Active</option><option value="0">Inactive</option>');
            $('#sectionModal').find('#submitBtn').html("Create");

            $('#sectionModal').find('#section-id').val(sectionId);
            $('#sectionModal').modal('toggle');
        }
    });
}

function editSection(sectionData) {
    var sectionId = $(sectionData).attr('data-id');
    $.ajax({
        url: "{{ route('users.section.get') }}",
        method: "GET",
        data: {
            id: sectionId,
            "_token": "{{ csrf_token() }}"
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('.modal-title').text("Update this section");
            $('#section-form').attr('action', "{{ route('users.section.update') }}");
            $('#section-form').find('[name="title"]').val(data.title);
            $('#section-form').find('[name="sub_title"]').val(data.sub_title);
            if (CKEDITOR.instances.editor) {
                CKEDITOR.instances.editor.setData(data.description);
            }
            var options = '';
            if(data.status == 1){
                options = '<option value="1" selected>Active</option><option value="0">Inactive</option>';
            }else{
                options = '<option value="1">Active</option><option value="0" selected>Inactive</option>';
            }
            $('#section-form').find('[name="status"]').html(options);

            $('#sectionModal').find('#submitBtn').html("Update");

            $('#sectionModal').find('#section-id').val(sectionId);
            $('#sectionModal').modal('toggle');
        }
    });
}

function deleteSection(sectionData) {
    var sectionId = $(sectionData).attr('data-id');
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be delete this section!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        console.log(result);
        if (result.value == true) {
            $.ajax({
                url: "{{ route('users.section.delete') }}",
                method: "POST",
                data: {
                    id: sectionId,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status == 'success'){
                        Swal.fire({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 1500
                        }).then((_) => {
                            loadData();
                        });
                    }else{
                        Swal.fire({
                            title: 'Failed',
                            text: data.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 1500
                        }).then((_) => {
                            loadData();
                        });
                    }
                    console.log(data);
                },
            });
        } else {
            Swal.fire({
                title: "Cancelled",
                text: "Your section is safe",
                icon: "error",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        }
    });

}

function submitSection(){
    event.preventDefault();
    var formData = $('#section-form').serializeArray();
    var formUrl = $('#section-form').attr('action');
    formData.push({
        name: 'description',
        value: CKEDITOR.instances.editor.getData()
    });

    $.ajax({
        url: formUrl,
        method: "POST",
        data: formData,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if(data.status == 'success'){
                $('#sectionModal').modal('toggle');
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }else{
                Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.status);
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errors, function(field, messages) {
                    var input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');

                    input.after('<span class="text-danger">' + messages[0] + '</span>');
                });
            }if (xhr.status === 403) {
                Swal.fire({
                    title: xhr.statusText,
                    text: xhr.responseJSON.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                });
            } else {
                console.error('An unexpected error occurred:', error);
            }
        }
    });
}

function saveSignature(){
    var formData = $('#signature-form').serializeArray();

    var selectedValue = formData.find(field => field.name === 'sig_type')?.value || null;

    if (selectedValue === "1") {
        console.log("Showing Id : ", selectedValue);
        if($('#signature-form').find('.defult-signature').text() != ""){
            formData.push({
                name: 'title',
                value: $('#signature-form').find('.defult-signature').text()
            });
            uploadSignature(formData);
        }else{
            Swal.fire({
                title: 'Failed',
                text: "Please type a signature",
                icon: 'error',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        }
    } else if (selectedValue === "2") {
        console.log("Showing Id : ", selectedValue);
        if(formData.find(field => field.name === 'upload_image') != null){
            uploadSignature(formData);
        }else{
            Swal.fire({
                title: 'Failed',
                text: "Please upload a signature",
                icon: 'error',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        }
    } else if (selectedValue === "3") {
        console.log("Showing Id : ", selectedValue);
        if (!signaturePad.isEmpty()) {
            formData.push({
                name: 'drow_image',
                value: signaturePad.toDataURL()
            });
            uploadSignature(formData)
        }else{
            Swal.fire({
                title: 'Failed',
                text: "Please draw a signature",
                icon: 'error',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        }
    }

}

function uploadSignature(formData){
    $.ajax({
        url: "{{ route('users.signature.save') }}",
        method: "POST",
        data: formData,
        dataType: 'json',
        success: function(data) {
            if(data.status == 'success'){
                $('#signatureModal').modal('toggle');
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }else{
                Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                }).then((_) => {
                    loadData();
                });
            }
            console.log(data);
        },
    });
}

function sendData(thisData){
    load.show();
    var proId = $(thisData).attr('data-id');
    $.ajax({
        url: "{{ route('users.proposal.send') }}",
        method: "POST",
        data: {
            id: proId,
            "_token": "{{ csrf_token() }}"
        },
        dataType: 'json',
        success: function(data) {
            load.hide();
            if(data.status == 'success'){
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                });
            }else{
                Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500
                });
            }
        },
        error: function(xhr, status, error) {
            load.hide();
            Swal.fire({
                title: xhr.statusText,
                text: xhr.responseJSON.message,
                icon: 'error',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        }
    });

}
</script>
<script src="{{ asset('admin/vendor/signature_pad/js/app.js') }}"></script>
@endsection
