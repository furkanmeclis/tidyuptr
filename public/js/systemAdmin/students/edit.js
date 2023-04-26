$(function () {

    document.getElementById('updateOrganization').addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this)
            .find("input[name=organization_id]")
            .val($("input[name=organization_id_tmp]:checked").val());
        const formValues = $(this).serializeArray();
        let action = this.getAttribute("action");
        $.ajax({
            url: action,
            type: "PUT",
            data: formValues,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
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
    document.getElementById('updateTeacher').addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this)
            .find("input[name=teacher_id]")
            .val($("input[name=teacher_id_tmp]:checked").val());
        const formValues = $(this).serializeArray();
        let action = this.getAttribute("action");
        $.ajax({
            url: action,
            type: "PUT",
            data: formValues,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
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
    new EditableRows2();
    const form2 = document.getElementById("updateStudent");
    const form = document.getElementById("updateStudentPassword");
    if (!form) {
        return;
    }
    const validateOptions = {
        errorElement: "div",
        rules: {
            password: {
                required: true,
                minlength: 8,
            },
            confirmPassword: {
                required: true,
                equalTo: "#password",
            },
        },
    };
    jQuery(form).validate(validateOptions);
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form).valid()) {
            const formValues = $(form).serializeArray();
            $.ajax({
                url: form.getAttribute("action"),
                type: "PUT",
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
    if (!form2) {
        return;
    }
    const validateOptions2 = {
        errorElement: "div",
        rules: {
            email: {
                required: true,
                email: true,
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
    jQuery(form2).validate(validateOptions2);
    form2.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form2).valid()) {
            const formValues = $(form2).serializeArray();
            $.ajax({
                url: form2.getAttribute("action"),
                type: "PUT",
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
});
if (document.querySelector("#phoneNumber") !== null) {
    IMask(document.querySelector("#phoneNumber"), {
        mask: "(000) 000 00 00",
        lazy: true,
    });
}
