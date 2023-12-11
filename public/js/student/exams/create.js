$(() => {
    let form = document.querySelector('#createExam');
    if(form){
        const validateOptions = {
            errorElement: "div",
        };

        jQuery(form).validate(validateOptions);
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (jQuery(form).valid()) {
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
    }
    if(document.getElementById('examResultWizard')){
        new EditableRows2();
        new Wizard(document.getElementById("examResultWizard"), {
            topNav: false,
        });
    }
    let form2 = document.querySelector('#createExamResult');
    if (!form2) {
        return;
    }
    const validateOptions2 = {
        errorElement: "div",
        rules: {
            '*[correct_answers]': {
                required: true,
            },
            '*[wrong_answers]': {
                required: true,
            },
        }
    }
    jQuery(form2).validate(validateOptions2);
    form2.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form2).valid()) {
            $(form2)
                .find("input[name=student_id]")
                .val($("input[name=student_id_tmp]:checked").val());
            const formValues = $(form2).serializeArray();
            $.ajax({
                url: form2.getAttribute("action"),
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
    })
});
