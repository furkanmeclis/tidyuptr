$(() => {
    jQuery('#selectFilled').select2({minimumResultsForSearch: Infinity});
    const form = document.getElementById("askTeacher");
    const validateOptions = {
        errorElement: "div",
        ignore: 'input[type=hidden]',
        rules: {
            question: {
                required: true,
            },
            file:{
                accept: 'image/*'
            },
            teacher_id:{
                required: true,
            }
        },
        messages: {
            file:{
                accept: 'Sadece resim dosyaları kabul edilmektedir.'
            }
        },
    };
    jQuery(form).validate(validateOptions);
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form).valid()) {
            const formValues = $(form).serializeArray();
            let formData = new FormData();
            for (let i = 0; i < formValues.length; i++) {
                formData.append(formValues[i].name, formValues[i].value);
            }
            let file = form.querySelectorAll('input[type=file]')[0].files[0];
            if(file){
                formData.append('file', file);
            }
            $.ajax({
                url: form.getAttribute("action"),
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
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

