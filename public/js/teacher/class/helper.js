$(() => {
    let showExamButtons = document.querySelectorAll(".show-exam-btn");
    if(showExamButtons && showExamButtons.length > 0){
        showExamButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        $('#examResultArea').html(response.html);
                        $('#xlExample').find('.modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>');
                        $('#xlExample').find('.modal-title').html('Sınav Sonucu');
                        $('#xlExample').modal('show');
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
        });
    }
    let showAttenDanceBtns = document.querySelectorAll("[data-attendance-url]");
    if(showAttenDanceBtns && showAttenDanceBtns.length > 0){
        showAttenDanceBtns.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("data-attendance-url");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {

                        $('#examResultArea').html(response.html);
                        $('#xlExample').find('.modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>');
                        if ($("#examResultArea").find('#classAttendanceInit').length > 0) {
                            let button = document.createElement('button');
                            button.setAttribute('type', 'button');
                            button.setAttribute('class', 'btn btn-primary');
                            button.setAttribute('id', 'classAttendanceInitBtn');
                            button.innerHTML = 'Kaydet';
                            button.addEventListener('click', (e) => {
                                //trigger form submit
                                $('#xlExample').find('form#classAttendanceInit').submit();
                            });
                            $('#xlExample').find('.modal-footer').append(button);
                            $('#xlExample').find('form#classAttendanceInit').on('submit', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                let formData = $(e.target).serializeArray();
                                $.ajax({
                                    url: e.target.getAttribute('action'),
                                    type: "POST",
                                    dataType: "json",
                                    data: formData,
                                    success: function (response) {
                                        if (response.status) {
                                            iziToast.success({
                                                title: "Başarılı",
                                                message: response.message,
                                            });
                                            $('#xlExample').modal('hide');

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
                        }
                        $('#xlExample').find('.modal-title').html(response.title);
                        $('#xlExample').modal('show');
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
        });
    }
});
