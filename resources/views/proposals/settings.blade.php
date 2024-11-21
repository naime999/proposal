
<script>


// function settingProposal(data){
//     var proId = $(data).attr("data-id");
//     $('#settingsModal').find('#proposal-id').val(proId);
//     $('#settingsModal').modal('toggle');
// }

function fileDetailsCrop(data)
{
    let name = $(data).attr('data-val');
    let name_view = $(data).attr('data-attr');
    let img_size = $(data).attr('data-size');
    let pro_id = $(data).attr('data-id');
    $("#crop").attr('name', name);
    $("#crop").attr('data-size', img_size);
    $("#crop").attr('data-id', pro_id);
    $("#crop").val(name_view);
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
    if (!img_size || !img_size.includes('x')) {
        console.error("Invalid data-size format. Expected 'widthxheight'.");
        return;
    }
    var csize = img_size.split('x');
    console.log(parseInt(csize[0]),parseInt(csize[1]));
    cropper = new Cropper(image, {
        aspectRatio: 9 / 12.5,
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
    let baseImage = $(this).attr('name');
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
            console.log("base64 data", base64data,baseImage)
            var bgCss = {"background-image":'url('+base64data+')', "height":'1250px'};
            $('#'+baseImage).val(base64data);
            $('.heading').find('#header').css(bgCss);
            $modal.modal('hide')
            saveCover();
        }
    });
})

function saveCover(){
    load.show();
    var formData = $('#cover-form').serializeArray();
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


</script>
