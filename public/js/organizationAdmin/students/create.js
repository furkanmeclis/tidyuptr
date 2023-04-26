$(function () {
    const form = document.getElementById("createStudent");
    if (!form) {
        return;
    }
    const validateOptions = {
        errorElement: "div",
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            confirmPassword: {
                required: true,
                equalTo: "#password",
            },
            name: {
                required: true,
            },
        },
        messages: {
            email: {
                email: "E-posta adresiniz doğru formatta olmalıdır!",
            },
        },
    };

    new EditableRows2();
    jQuery(form).validate(validateOptions);
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form).valid()) {
            $(form)
                .find("input[name=organization_id]")
                .val($("input[name=organization_id_tmp]:checked").val());
            $(form)
                .find("input[name=teacher_id]")
                .val($("input[name=teacher_id_tmp]:checked").val());
            const formValues = $(form).serializeArray();
            $.ajax({
                url: form.getAttribute("action"),
                type: "POST",
                data: formValues,
                dataType: "json",
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
            return;
        }
    });
    new Wizard(document.getElementById("studentWizard"), {
        topNav: false,
    });
});
if (document.querySelector("#phoneNumber") !== null) {
    IMask(document.querySelector("#phoneNumber"), {
        mask: "(000) 000 00 00",
        lazy: true,
    });
}
