$(() => {
    let deleteButons = document.querySelectorAll(".delete-class-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Sınıfı Silmek İstediğinize Emin misiniz?",
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
                            "<button>Sınıfı Sil</button>",
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
    let showExamButtons = document.querySelectorAll(".show-exam-btn");
    if(showExamButtons && showExamButtons.length > 0){
        showExamButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        $('#examResultArea').html(response.html);
                        $('#xlExample').find('.modal-title').html('Sınav Sonucu');
                        $('#xlExample').modal('show');
                    },
                    error: function (xhr, status, error) {

                    },
                });
            });
        });
    }
    let showAttenDanceBtns = document.querySelectorAll("[data-attendance-url]");
    if(showAttenDanceBtns && showAttenDanceBtns.length > 0){
        showAttenDanceBtns.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("data-attendance-url");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {

                        $('#examResultArea').html(response.html);
                        $('#xlExample').find('.modal-title').html(response.title);
                        $('#xlExample').modal('show');
                    },
                    error: function (xhr, status, error) {

                    },
                });
            });
        });
    }
});
