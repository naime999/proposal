
<script>


// function settingProposal(data){
//     var proId = $(data).attr("data-id");
//     $('#settingsModal').find('#proposal-id').val(proId);
//     $('#settingsModal').modal('toggle');
// }

function fileDetailsCrop(data)
{
    let name = $(data).attr('name');
    let img_size = $(data).attr('data-size');
    let img_ratio = $(data).attr('data-ratio');
    $("#crop").attr('data-name', name);
    $("#crop").attr('data-size', img_size);
    $("#crop").attr('data-ratio', img_ratio);
}

var $modal = $('#copperModal');
var image = document.getElementById('image');
var cropper;
$("body").on("change", ".image", function(e) {
    var files = e.target.files;
    var done = function(url) {
        image.src = url;
        $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
        file = files[0];
        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function(e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
});
$modal.on('shown.bs.modal', function() {
    let img_size = $("#crop").attr('data-size');
    let img_ratio = $("#crop").attr('data-ratio');
    if (!img_size || !img_size.includes('x')) {
        console.error("Invalid data-size format. Expected 'widthxheight'.");
        return;
    }
    var csize = img_size.split('x');
    var cratio = img_ratio.split('/');
    // console.log(parseInt(csize[0]),parseInt(csize[1]));
    cropper = new Cropper(image, {
        aspectRatio: cratio[0] / cratio[1],
        dragMode: 'move',
        restore: false,
        guides: true,
        center: false,
        highlight: false,
        mouseWheelZoom: true,
        touchDragZoom: true,
        cropBoxMovable: true,
        cropBoxResizable: false,
        toggleDragModeOnDblclick: false,
        data: {
            width: parseInt(csize[0]),
            height: parseInt(csize[1])
        },
        preview: '.preview'
    });
}).on('hidden.bs.modal', function() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

$("#crop").click(function() {
    // alert('okay');
    let fieldName = $(this).attr('data-name');
    let img_size = $(this).attr('data-size');
    var csize = img_size.split('x');
    canvas = cropper.getCroppedCanvas({
        width: parseInt(csize[0]),
        height: parseInt(csize[1]),
    });
    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            var base64data = reader.result;
            console.log("base64 data", base64data,fieldName)
            $('#proposal-form').find('input[name='+fieldName+']').val(base64data);
            if(fieldName == 'cover'){
                var bgCss = {"background-image":'url('+base64data+')', "height":'1250px'};
                $('.heading').find('#header').css(bgCss);
            }else if(fieldName == 'logo'){

            }
            $modal.modal('hide')
            saveCover();
        }
    });
})

function saveCover(){
    load.show();
    var formData = $('#proposal-form').serializeArray();
    $.ajax({
        url: "{{ route('users.proposal.update') }}",
        method: "POST",
        data: formData,
        dataType: 'json',
        success: function(data) {
            console.log("Return Data : ",data);
            // load.hide();
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

function base64ToFile(base64String, fileName) {
    let arr = base64String.split(',');
    let mime = arr[0].match(/:(.*?);/)[1];
    let bstr = atob(arr[1]);
    let n = bstr.length;
    let u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], fileName, { type: mime });
}

function saveAsTemp(data){
    $('#tempModal').modal('show');
}


</script>
