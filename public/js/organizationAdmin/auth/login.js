class AuthLogin {
    constructor() {
        // Initialization of the page plugins
        this._initForm();
    }

    // Form validation
    _initForm() {
        const form = document.getElementById("systemAdminLoginForm");
        if (!form) {
            return;
        }
        const validateOptions = {
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    email: "E-posta adresiniz doğru formatta olmalıdır!",
                },
            },
        };
        jQuery(form).validate(validateOptions);
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (jQuery(form).valid()) {
                const formValues = {
                    email: form.querySelector('[name="email"]').value,
                    password: form.querySelector('[name="password"]').value,
                    remember_me: form.querySelector('[name="remember_me"]')
                        .value,
                };
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
                                onClosing: function () {
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
}
