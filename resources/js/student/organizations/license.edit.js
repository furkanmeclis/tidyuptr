$(function () {
    const form = document.getElementById("updateOrganizationLicense");
    if (!form) {
        return;
    }
    const validateOptions = {
        errorElement: "div",
        rules: {
            licenseStartDate: {
                required: true,
            },
            licenseExpireDate: {
                required: true,
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
});
if (jQuery().datepicker) {
    jQuery("#datePickerCreateOrganizationLicenseStartDate").datepicker({
        minDate: 0,
    });
    jQuery("#datePickerCreateOrganizationLicenseExpireDate").datepicker({
        minDate: 0,
    });
}
