$(() => {
    let deleteButons = document.querySelectorAll(".delete-assignment-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Ödevi Silmek İstediğinize Emin misiniz?",
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
                            "<button>Ödevi Sil</button>",
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
    let showBtns = document.querySelectorAll(".show-assignment-response");
    if (showBtns && showBtns.length > 0) {
        showBtns.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $('#examResultArea').html(response.html);
                        $('#xlExample').modal('show');
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
        });
    }
    let sendAssignment = document.querySelector("#sendAssignment");
    if(sendAssignment){
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
        sendAssignment.addEventListener("submit", (e) => {
            e.preventDefault();
            e.stopPropagation();
            let form = document.querySelector("#sendAssignment");
            let formData = new FormData();
            formData.append("content", quill.root.innerHTML);
            let file = form.querySelector('input[type="file"]').files[0];
            if (file) {
                formData.append("file", file);
            }
            $.ajax({
                url: form.getAttribute("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
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
    }
});
