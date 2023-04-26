$(() =>{
    let options = [
        ['bold', 'italic', 'underline', 'strike'],
        [{header: [1, 2, 3, 4, 5, 6, false]}],
        [{list: 'ordered'}, {list: 'bullet'}],
        [{align: []}],
    ];
    let quill = new Quill('#quillEditorFilled', {
        modules: {toolbar: options},
        theme: 'bubble',
        placeholder: 'İçerik',
    });
    $('#addFile').on('click', function () {
        $('#fileInput').click();
    });
    $('#fileInput').on('change', function () {
        let file = $(this).prop('files')[0];
        $('#fileName').html(file.name+" ("+(file.size / (1024 * 1024)).toFixed(2)+" mb)");
        $('#fileSelected').fadeIn();
    });
    $('#removeFile').on('click', function () {
        $('#fileInput').val('');
        $('#fileSelected').fadeOut();
        $('#fileName').html('Dosya seçilmedi');
    });
    $('#createMessage').on('submit', function (e) {
        e.preventDefault();
        let data = new FormData(this);
        data.append('content', quill.root.innerHTML);
        $.ajax({
            url: '/teacher/class/message',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(function () {
                        window.location.href = '/teacher/class/message';
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});
