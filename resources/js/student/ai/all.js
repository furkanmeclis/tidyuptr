$(() => {

    $("#sendQuestion").on("submit", (e) => {
        e.preventDefault();
        e.stopPropagation();
        let form = e.target;
        let formData = $(form).serializeArray();
        let url = form.action;
        let method = form.method;
        let submitButton = $(form).find("button[type=submit]");
        let buttonHtml = $(submitButton).html();
        $.ajax({
            url: url,
            type: method,
            data: formData,
            beforeSend: function () {
                $(submitButton).attr("disabled", true);
                $(submitButton).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );
            },
            success: function (response) {
                $(submitButton).attr("disabled", false);
                $(submitButton).html(buttonHtml);
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
                    });
                    window.location.reload();
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


    const element = document.querySelector('#content_card_body');
    const options = {};
    const osInstance = OverlayScrollbars(element, options);

    osInstance.scroll({
        y: '100%',
        duration: 500,
        easing: 'linear'
    });
});

