$(() => {

    baguetteBox.run('.lightbox');
    $("#chatAttachButton").on("click",(e) => {
        e.stopPropagation();
        e.preventDefault();
        document.getElementById('chatAttachmentInput').dispatchEvent(new MouseEvent('click'));
    });
    $('#chatAttachmentInput').on('change', (e) => {
        let file = e.target.files[0];
        //control file
        if(file !== undefined){
            $("#chatAttachButton").attr("title",file.name)
            $("#selectedFiles").removeClass("d-none");
            $("#unselectedFiles").addClass("d-none");
            $("#chatAttachButton").toggleClass("btn-outline-primary btn-primary");
        }else{
            $("#chatAttachButton").attr("title","Seçilmedi")
            $("#selectedFiles").addClass("d-none");
            $("#unselectedFiles").removeClass("d-none");
            $("#chatAttachButton").toggleClass("btn-outline-primary btn-primary");
        }
    });
    $("#sendAnswer").on("submit", (e) => {
        e.preventDefault();
        e.stopPropagation();
        let form = e.target;
        let formData = new FormData(form);
        let url = form.action;
        let method = form.method;
        $.ajax({
            url: url,
            type: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
                    });
                    window.location.reload();
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
    function responsiveSizer(){
        if($(window).width() < 768){
            $("#contactView").addClass("d-none");
            $("#backButton").removeClass("d-none");
            $("#chatView").removeClass("d-none");
        }else{
            $("#contactView").removeClass("d-none");
            $("#backButton").addClass("d-none");
            $("#chatView").removeClass("d-none");
        }
    }
    responsiveSizer();
    $(window).resize(function(){
        responsiveSizer();
    });

    $("#backButton").on('click', () => {
        if($(window).width() < 768){
            $("#contactView").removeClass("d-none");
            $("#chatView").addClass("d-none");
            $("#backButton").addClass("d-none");
        }
    });
    $("#activeQuestion").on('click', (e) => {
        e.stopPropagation();
        e.preventDefault();
        if($(window).width() < 768){
            $("#contactView").addClass("d-none");
            $("#chatView").removeClass("d-none");
            $("#backButton").removeClass("d-none");
        }
    });
    const element = document.querySelector('#content_card_body');
    const options = {};
    const osInstance = OverlayScrollbars(element, options);

    osInstance.scroll({
        y: '100%',
        duration: 500,
        easing: 'linear'
    });
});

