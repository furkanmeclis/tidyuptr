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
            url: $('#createMessage').attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {

                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
                        onClosing: () => {
                            window.location.href = response.url;
                        },
                    });
                } else {
                    iziToast.error({
                        title: "Hata",
                        message: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status == 419) {
                    iziToast.error({
                        title: "Hata",
                        message:
                            "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.",
                    });
                } else {
                    iziToast.error({
                        title: "Hata",
                        message: "Bir Hata Oluştu: " + error,
                    });
                }
            },
        });
    });
    let deleteButons = document.querySelectorAll(".delete-content-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Mesajı Silmek İstediğinize Emin Misiniz?",
                    message: "",
                    overlay: true,
                    position: "bottomRight",
                    timeout: 10000,
                    buttons: [
                        [
                            "<button>Vazgeç</button>",
                            function (instance, toast) {
                                instance.hide(
                                    {
                                        transitionOut: "fadeOutUp",
                                    },
                                    toast,
                                    "buttonName"
                                );
                            },
                        ],
                        [
                            "<button>Mesajı Sil</button>",
                            function (instance, toast) {
                                $.ajax({
                                    url: link,
                                    type: "DELETE",
                                    dataType: "json",
                                    success: function (response) {
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );

                                        if (response.status) {
                                            iziToast.success({
                                                title: "Başarılı",
                                                message: response.message,
                                                onClosing: () => {
                                                    window.location.reload();
                                                },
                                            });
                                        } else {
                                            iziToast.error({
                                                title: "Hata",
                                                message: response.message,
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );
                                        if (xhr.status == 419) {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.",
                                            });
                                        } else {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "Bir Hata Oluştu: " + error,
                                            });
                                        }
                                    },
                                });
                            },
                            true,
                        ],
                    ],
                });
            });
        });
    }
    var element = document.querySelector("#content_card_body");
    element.scrollTop = element.scrollHeight;
});

