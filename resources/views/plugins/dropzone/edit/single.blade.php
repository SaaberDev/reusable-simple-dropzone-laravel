<script>
    // Dropzone Service Thumb
    var singleUploadMap = {}
    Dropzone.options.{{ $dropzone }} = {
        url: "{{ $store }}",
        maxFilesize: '{{ $maxFilesize }}', // MB
        maxFiles: '{{ $maxFiles }}',
        uploadMultiple: false,
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
                    request: '{{ $getRequestParam }}',
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

                        myDropzone.displayExistingFile(mockFile, value.original_url);

                        // myDropzone.options.addedfile.call(myDropzone, mockFile);
                        $('.dz-image').find('img').addClass('dz-thumb');

                        $('form').append('<input type="hidden" name="{{ $fileInputName}}" value="' + value.file_name + '">');

                        // myDropzone.options.thumbnail.call(myDropzone, mockFile, value.original_url);

                        // Make sure that there is no progress bar, etc...
                        myDropzone.emit("complete", mockFile);

                        let fileCountOnServer = 1; // The number of files already uploaded
                        myDropzone.options.maxFiles = myDropzone.options.maxFiles - fileCountOnServer;

                    });
                }
            });
        },
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="{{ $fileInputName}}" value="' + response.name + '">')
            singleUploadMap[file.name] = response.name
        },
        removedfile: function (file) {

            var name = ''

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
                                {{ $fileInputName }}: file.name,
                                uuid: file.id
                            },
                            success:function(response) {
                                file.previewElement.remove()
                                $('form').find('input[name="{{ $fileInputName }}"][value="' + name + '"]').remove()
                                // myDropzone.options.maxFiles = 1;
                                window.location.reload()
                            }
                        });
                    }
                    return false;
                })
            } else {
                name = singleUploadMap[file.name];

                file.previewElement.remove();
                $('form').find('input[name="{{ $fileInputName }}"][value="' + name + '"]').remove();
                // Delete preview from filesystem - ajax
                $.ajax({
                    type: 'DELETE',
                    url: "{{ $delete }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        {{ $fileInputName }}: name
                    },
                });
            }

        }
    }
</script>
