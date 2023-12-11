$(() => {
    $("#userSettings").on("submit", (e) => {
        e.preventDefault();
        e.stopPropagation()
        $.ajax({
            url: $("#userSettings").attr("action"),
            type: "POST",
            data: $("#userSettings").serializeArray(),
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
    })
})
