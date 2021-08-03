<script>
    var singleUploadMap = {}
    Dropzone.options.singleMediaDropzone = {
        url: "{{ $store }}",
        maxFilesize: '{{ $maxFilesize }}', // MB
        maxFiles: 1,
        uploadMultiple: false,
        acceptedFiles: '{{ $acceptedFiles }}',
        addRemoveLinks: true,
        thumbnailWidth: 120,
        thumbnailHeight: 120,
        thumbnailMethod: 'contain',
        init: function() {
            let myDropzone = this;
            myDropzone.on("addedfile", function(file) {
                $('.dz-image').last().find('img').addClass('dz-thumb')
                if (!file.type.match(/image.*/)) {
                    this.emit("thumbnail", file, "/_assets/_default/file_icon.png");
                }
            });
        },
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="single_media" value="' + response.name + '">')
            singleUploadMap[file.name] = response.name
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name
            } else {
                name = singleUploadMap[file.name]
            }
            $('form').find('input[name="single_media"][value="' + name + '"]').remove()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: "{{ $delete }}",
                data: {
                    single_media: name,
                },
            });
        }
    }
</script>
