$(document).ready(function() {
  $('.select').select2();
});

// Dropzone Uploader
Dropzone.autoDiscover = false;
let $form = $(this);
$btn = $('#uploaderBtn');
myDropzone = new Dropzone('div#imageUpload', {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 100,
    maxThumbnailFilesize: 50,
    method: 'post',
    acceptedFiles : 'image/jpeg,image/png,image/webp',
    paramName: 'file',
    clickable: true,
    url: 'includes/server/gallery.php',
    init: function () {

        var myDropzone = this;
        // Update selector to match your button
        $btn.click(function (e) {
            // let $imgHeight = $("#imgHeight").val();
            // let $imgQuality = $("#imgQuality").val();
            // let $imgOutSize = $("#imgOutSize").val();
            // let $imgTrans = $("#imgTansVal").val();
            // console.log($imgTrans);
            myDropzone.processQueue();
            return false;
        });

        this.on('sending', function (file, xhr, formData) {
            // Append all form inputs to the formData Dropzone will POST
            var data = $form.serializeArray();
            let $imgQuality = $("#imgQuality").val();
            let $imgWpQuality = $("#imgWpQuality").val();
            // console.log($imgTrans);
            formData.append('imgQuality', $imgQuality);
            formData.append('imgWpQuality', $imgWpQuality);
            $.each(data, function (key, el) {
                $form.append(el.name, el.value);
            });
            console.log($form);

        });
    },
    error: function (file, response){
        if ($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    successmultiple: function (file, response) {
        console.log(file, response);
        // $modal.modal("show");
        // $('.toast').toast('show');
        this.removeAllFiles(true);
    },
    completemultiple: function (file, response) {
        console.log(file, response, "completemultiple");
        //$modal.modal("show");
    },
    reset: function () {
        console.log("resetFiles");
        this.removeAllFiles(true);
    },
    success: function() {
        window.location.href = "gallery.php?done";
    }
});