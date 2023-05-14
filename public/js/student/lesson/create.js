$(function () {
    const form = document.getElementById("createLesson");
    if (!form) {
        return;
    }
    const validateOptions = {
        errorElement: "div",
        rules: {
            name: {
                required: true,
            },
            grade: {
                required: true,
            }
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
    let konuSayisi = 0;
    $("#addTopic").on("click", function () {
        konuSayisi++;
        let konularHtml = `
                    <div class="input-group mb-3">
                        <span class="input-group-text font-weight-bold">Konu Adı:</span>
                        <input type="text" class="form-control"  placeholder="örn. Dalga Mekaniği" name="topics[${konuSayisi}][name]">
                        <span class="input-group-text font-weight-bold">Konu Katsayısı:</span>
                        <input type="number" class="form-control float-input-js" placeholder="örn 5.4" name="topics[${konuSayisi}][coefficient]">
                        <button class="btn btn-outline-danger delete-tmp-topic"  data-bs-toggle="tooltip" title="Sil">
                            <small><i class="bi-trash"></i></small>
                        </button>
                    </div>
                `;
        $("#topicsCreate").append(konularHtml);
    });
    $(document).on("click", ".delete-tmp-topic", function () {
        konuSayisi--;
        $(this).closest(".input-group").fadeOut().remove();
    });
    $(document).on("blur", ".float-input-js", function (e) {
        let value = e.target.value;
        if (!value || value === "0") {
            return "0.00";
        }
        const floatValue = parseFloat(value.replace(",", ".")).toFixed(2);
        e.target.value = floatValue;
    });
});
