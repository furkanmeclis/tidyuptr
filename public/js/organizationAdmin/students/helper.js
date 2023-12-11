$(() => {
    let deleteButons = document.querySelectorAll(".delete-student-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Öğrenciyi Silmek İstediğinize Emin misiniz?",
                    message: "",
                    overlay: true,
                    position: "bottomRight",
                    timeout: 10000,
                    buttons: [
                        [
                            "<button>Vazgeç</button>",
                            function (instance, toast) {
                                instance.hide(
                                    {
                                        transitionOut: "fadeOutUp",
                                    },
                                    toast,
                                    "buttonName"
                                );
                            },
                        ],
                        [
                            "<button>Öğrenciyi Sil</button>",
                            function (instance, toast) {
                                $.ajax({
                                    url: link,
                                    type: "DELETE",
                                    dataType: "json",
                                    success: function (response) {
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );

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
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );
                                        if (xhr.status == 419) {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.",
                                            });
                                        } else {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "Bir Hata Oluştu: " + error,
                                            });
                                        }
                                    },
                                });
                            },
                            true,
                        ],
                    ],
                });
            });
        });
    }
    $('#uploadStudent').find('form').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let form = $(this);
        let formData = new FormData(form[0]);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status) {
                    $('#confirmStudents').find('.successCount').hide();
                    $('#confirmStudents').find('.errorCount').hide();
                    let tbody = '';
                    let successCount = 0;
                    response.data.forEach((student) => {
                        if (student.status) {
                            successCount++;
                        }
                        tbody += '<tr>';
                        tbody += '<td>' + (student.status ? '<span class="text-success">Kayıt Edildi</span>' : '<span class="text-danger" title="'+student.error+'">Kayıt Başarısız</span>') + '</td>';
                        tbody += '<td>' + student.name + '</td>';
                        tbody += '<td>' + student.email + '</td>';
                        tbody += '<td>' + student.identity_number + '</td>';
                        tbody += '<td>' + student.phone || 'Eklenmemiş' + '</td>';
                        tbody += '</tr>';
                    });
                    $('#uploadStudent').modal('hide');
                    $('#uploadStudent').find('form').trigger('reset');
                    $('#confirmStudents').find('tbody').html(tbody);
                    if(successCount > 0){
                        $('#confirmStudents').find('.successCount').show();
                        $('#confirmStudents').find('.successCount').find('b').html(successCount);
                    }
                    if(response.data.length - successCount > 0){
                        $('#confirmStudents').find('.errorCount').show();
                        $('#confirmStudents').find('.errorCount').find('b').html(response.data.length - successCount);
                    }
                    $('#confirmStudents').find('.totalCount').html(response.data.length);

                    $('#confirmStudents').modal('show');
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
                        message:
                            "Bir Hata Oluştu: " + error,
                    });
                }
            },
        });
    });

    let newParentBtns = document.querySelectorAll(".new-parent-btn");
    if (newParentBtns && newParentBtns.length > 0) {
        newParentBtns.forEach((button) => {
            button.addEventListener("click", (e) => {
                let templateHtml = document.querySelector(button.getAttribute("data-template-selector")).innerHTML;
                let container = document.querySelector(button.getAttribute("data-container-selector"));
                let count = button.getAttribute("data-count");
                let html = templateHtml.replaceAll("{count}", count);
                button.setAttribute("data-count", parseInt(count) + 1);
                container.innerHTML += html;
            });
        });
    }
    $('.save-parent-details').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let action = $(this).attr('action');
        let formData = $(this).serializeArray();
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
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
                        message:
                            "Bir Hata Oluştu: " + error,
                    });
                }
            },

        }).fi;
    });
});
