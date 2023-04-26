$(() => {
    let deleteButons = document.querySelectorAll(".delete-teacher-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Öğretmeni Silmek İstediğinize Emin misiniz?",
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
                            "<button>Öğretmeni Sil</button>",
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
    let endRegisterButons = document.querySelectorAll('.end-registration-teacher-btn');
    if (endRegisterButons && endRegisterButons.length > 0) {
        endRegisterButons.forEach((button) => {
            button.addEventListener('click', (e) => {
                let link = button.getAttribute('href');
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    message: 'Öğretmenin Kaydını Kurumdan Silmek İstediğinize Emin misiniz?\nÖğretmene Bağlı Öğrencilerinizin Öğretmenini Yeniden Seçmek Durumunda Kalacaksınız.',
                    title: 'Uyarı',
                    overlay: true,
                    position: 'bottomRight',
                    timeout: 10000,
                    buttons: [
                        [
                            '<button>Vazgeç</button>',
                            function (instance, toast) {
                                instance.hide(
                                    {
                                        transitionOut: 'fadeOutUp',
                                    },
                                    toast,
                                    'buttonName'
                                );
                            },
                        ],
                        [
                            '<button>Öğretmenin Kaydını Sonlandır</button>',
                            function (instance, toast) {
                                $.ajax({
                                    url: link,
                                    type: 'DELETE',
                                    dataType: 'json',
                                    success: function (response) {
                                        instance.hide(
                                            {
                                                transitionOut: 'fadeOutUp',
                                            },
                                            toast,
                                            'buttonName'
                                        );

                                        if (response.status) {
                                            iziToast.success({
                                                title: 'Başarılı',
                                                message: response.message,
                                                onClosing: () => {
                                                    window.location.reload();
                                                },
                                            });
                                        } else {
                                            iziToast.error({
                                                title: 'Hata',
                                                message: response.message,
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        instance.hide(
                                            {
                                                transitionOut: 'fadeOutUp',
                                            },
                                            toast,
                                            'buttonName'
                                        );
                                        if (xhr.status == 419) {
                                            iziToast.error({
                                                title: 'Hata',
                                                message:
                                                    'CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.',
                                            });
                                        } else {
                                            iziToast.error({
                                                title: 'Hata',
                                                message:
                                                    'Bir Hata Oluştu: ' + error,
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
});
