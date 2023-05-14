$(function () {
    const form = document.getElementById("createAssignment");
    if (!form) {
        return;
    }
    const validateOptions = {
        errorElement: "div",
        rules: {
            description: {
                required: true,
            },
            title: {
                required: true,
                minlength: 8,
            },
            due_date: {
                required: true,
            }
        },
    };

    jQuery(form).validate(validateOptions);
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form).valid()) {
            var formData = new FormData(form);
            $("input[name=student_id]:checked").each(function () {
                formData.append('student_ids[]', $(this).val())
            });
            $.ajax({
                url: form.getAttribute("action"),
                type: "POST",
                data: formData,
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
    new Wizard(document.getElementById("studentWizard"), {
        topNav: false,
    });
});
if (jQuery().datepicker) {
    jQuery("#datePickerAssignmentDueDate").datepicker({
        minDate: 0,
    });
}

