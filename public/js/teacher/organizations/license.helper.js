$(() => {
    let deleteButons = document.querySelectorAll(".delete-license-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Lisansı Silmek İstediğinize Emin misiniz?",
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
                            "<button>Lisansı Sil</button>",
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
    let activeButons = document.querySelectorAll(".active-license-btn");
    if (activeButons && activeButons.length > 0) {
        activeButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                if (button.getAttribute("data-active") === "false") {
                    $.ajax({
                        url: link,
                        type: "PUT",
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
                } else {
                    iziToast.warning({
                        title: "Uyarı",
                        message: "Aktif Lisansı Deaktifleştiremezsiniz",
                    });
                }
            });
        });
    }
});
