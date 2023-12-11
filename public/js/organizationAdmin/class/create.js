$(function () {
    jQuery('.select2').select2();
    new Wizard(document.getElementById("classWizard"), {
        topNav: false,
    });
    let classDataTable = new ClassyDataTable();

});

