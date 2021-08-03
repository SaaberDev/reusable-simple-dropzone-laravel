<script>
    Dropzone.prototype.isFileExist = function(file) {
        var i;
        if(this.files.length > 0) {
            for(i = 0; i < this.files.length; i++) {
                if(this.files[i].name === file.name
                    && this.files[i].size === file.size
                    && this.files[i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
                {
                    return true;
                }
            }
        }
        return false;
    };

    var multipleUploadMap = {}
    Dropzone.options.multipleMediaDropzone = {
        url: '{{ $store }}',
        maxFilesize: '{{ $maxFilesize }}', // MB
        acceptedFiles: '{{ $acceptedFiles }}',
        addRemoveLinks: true,
        thumbnailWidth: 120,
        thumbnailHeight: 120,
        thumbnailMethod: 'contain',
        dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
        preventDuplicates: true,
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
            $('form').append('<input type="hidden" name="multiple_media[]" value="' + response.name + '">')
            multipleUploadMap[file.name] = response.name
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name
            } else {
                name = multipleUploadMap[file.name]
            }
            $('form').find('input[name="multiple_media[]"][value="' + name + '"]').remove()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: "{{ $delete }}",
                data: {
                    multiple_media: name,
                },
            });
        }
    }

    Dropzone.prototype.addFile = function(file) {
        file.upload = {
            progress: 0,
            total: file.size,
            bytesSent: 0
        };
        if (this.options.preventDuplicates && this.isFileExist(file)) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                showCloseButton: true,
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show',
                    icon: 'swal2-icon-show',
                },
                hideClass: {
                    popup: 'swal2-hide',
                    backdrop: 'swal2-backdrop-hide',
                    icon: 'swal2-icon-hide'
                },
                customClass: 'swal-toast-height',
                background: 'rgb(244 255 244)'
            })
            Toast.fire({
                icon: 'warning',
                title: this.options.dictDuplicateFile
            })
            return;
        }
        this.files.push(file);
        file.status = Dropzone.ADDED;
        this.emit("addedfile", file);
        this._enqueueThumbnail(file);
        return this.accept(file, (function(_this) {
            return function(error) {
                if (error) {
                    file.accepted = false;
                    _this._errorProcessing([file], error);
                } else {
                    file.accepted = true;
                    if (_this.options.autoQueue) {
                        _this.enqueueFile(file);
                    }
                }
                return _this._updateMaxFilesReachedClass();
            };
        })(this));
    };
</script>
