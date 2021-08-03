<script>
    // Dropzone Service Image
    var multipleUploadMap = {}
    Dropzone.options.multipleMediaDropzone = {
        url: "{{ $store }}",
        maxFilesize: '{{ $maxFilesize }}', // MB
        acceptedFiles: '{{ $acceptedFiles }}',
        addRemoveLinks: true,
        thumbnailWidth: 120,
        thumbnailHeight: 120,
        thumbnailMethod: 'contain',
        init: function () {
            let myDropzone = this;
            myDropzone.on("addedfile", function(file) {
                $('.dz-image').last().find('img').addClass('dz-thumb');
            });
            // Data fetch
            $.ajax({
                type: 'get',
                url: "{{ $get }}",
                data: {
                    request: 'multipleUploader',
                    id: '{{ $model->id }}'
                },
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (key, value) {
                        var mockFile = {
                            id: value.uuid,
                            name: value.name,
                            size: value.size,
                            accepted: true
                        };

                        myDropzone.options.addedfile.call(myDropzone, mockFile);
                        $('.dz-image').find('img').addClass('dz-thumb');

                        $('form').append('<input type="hidden" name="multiple_media[]" value="' + value.file_name + '">');

                        myDropzone.options.thumbnail.call(myDropzone, mockFile, value.original_url);

                        // Make sure that there is no progress bar, etc...
                        myDropzone.emit("complete", mockFile);
                    });
                }
            });
        },
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="multiple_media[]" value="' + response.name + '">')
            multipleUploadMap[file.name] = response.name
        },
        removedfile: function (file) {

            var name = '';

            if (typeof file.id !== 'undefined') {
                Swal.fire({
                    title: "Are you sure ?",
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'rgb(0 0 0)',
                    cancelButtonColor: 'rgb(204 75 75)',
                    background: 'rgb(235 246 236)',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Delete media from server and filesystem - ajax
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ $delete }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                multiple_media: file.name,
                                uuid: file.id
                            },
                            success: function (response) {
                                file.previewElement.remove()
                                $('form').find('input[name="multiple_media[]"][value="' + name + '"]').remove()
                            }
                        });
                    }
                    return false;
                })
            } else {
                name = multipleUploadMap[file.name];

                file.previewElement.remove();
                $('form').find('input[name="multiple_media[]"][value="' + name + '"]').remove();
                // Delete preview from filesystem - ajax
                $.ajax({
                    type: 'DELETE',
                    url: "{{ $delete }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        multiple_media: name,
                    },
                });
            }
        }
    }
</script>
