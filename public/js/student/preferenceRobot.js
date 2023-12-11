jQuery(document).ready(function($) {
    let yks_2021,yks_2020,yks_2022;
    if(localStorage.getItem("yks_2022") == null){
        $.getJSON(BASE_URL + 'js/student/data/yks-2022.json', function(data) {
            yks_2022 = data;
            localStorage.setItem("yks_2022", JSON.stringify(yks_2022));
        });
    }
    if(localStorage.getItem("yks_2021") == null){
        $.getJSON(BASE_URL + 'js/student/data/yks-2021.json', function(data) {
            yks_2021 = data;
            localStorage.setItem("yks_2021", JSON.stringify(yks_2021));
        });
    }
    if(localStorage.getItem("yks_2020") == null){
        $.getJSON(BASE_URL + 'js/student/data/yks-2020.json', function(data) {
            yks_2020 = data;
            localStorage.setItem("yks_2020", JSON.stringify(yks_2020));
        });
    }
    function ykshesapla() {
        yks_2021 = JSON.parse(localStorage.getItem("yks_2021"));
        yks_2020 = JSON.parse(localStorage.getItem("yks_2020"));
        yks_2022 = JSON.parse(localStorage.getItem("yks_2022"));
        function limit(d, y, limit) {
            if (limit < Number(d.val())) {
                d.val(limit);
                y.val(0);
            }
            if (limit < (Number(d.val()) + Number(y.val()))) {
                y.val(0);
            }
            if (d.val() == undefined) {
                d.val(0);
            }
            if (y.val() == undefined) {
                y.val(0);
            }
        }
        var diploma = $('input[name="diploma"]');
        if (100 < Number(diploma.val())) {
            diploma.val(100);
        }
        if ($('input[name="kirikOBP"]:checked').val() !== undefined) {
            var diplomapuan = (Number(diploma.val()) * 0.6) / 2;
        } else {
            var diplomapuan = (Number(diploma.val()) * 0.6);
        }
        var katsayi = $("#katsayi").val();
        var tyt_m_d = $('input[name="tyt-m-d"]');
        var tyt_t_d = $('input[name="tyt-t-d"]');
        var tyt_s_d = $('input[name="tyt-s-d"]');
        var tyt_f_d = $('input[name="tyt-f-d"]');
        var tyt_m_y = $('input[name="tyt-m-y"]');
        var tyt_t_y = $('input[name="tyt-t-y"]');
        var tyt_s_y = $('input[name="tyt-s-y"]');
        var tyt_f_y = $('input[name="tyt-f-y"]');
        var tyt_m_n = $('input[name="tyt-m-n"]');
        var tyt_t_n = $('input[name="tyt-t-n"]');
        var tyt_s_n = $('input[name="tyt-s-n"]');
        var tyt_f_n = $('input[name="tyt-f-n"]');
        var tyt_ham = $('input[name="tyt-ham"]');
        var tyt_yer = $('input[name="tyt-yer"]');
        var yks_m_d = $('input[name="yks-m-d"]');
        var yks_f_d = $('input[name="yks-f-d"]');
        var yks_k_d = $('input[name="yks-k-d"]');
        var yks_b_d = $('input[name="yks-b-d"]');
        var yks_e_d = $('input[name="yks-e-d"]');
        var yks_t_d = $('input[name="yks-t-d"]');
        var yks_c_d = $('input[name="yks-c-d"]');
        var yks_t2_d = $('input[name="yks-t2-d"]');
        var yks_c2_d = $('input[name="yks-c2-d"]');
        var yks_fe_d = $('input[name="yks-fe-d"]');
        var yks_d_d = $('input[name="yks-d-d"]');
        var yks_di_d = $('input[name="yks-di-d"]');
        var yks_m_y = $('input[name="yks-m-y"]');
        var yks_f_y = $('input[name="yks-f-y"]');
        var yks_k_y = $('input[name="yks-k-y"]');
        var yks_b_y = $('input[name="yks-b-y"]');
        var yks_e_y = $('input[name="yks-e-y"]');
        var yks_t_y = $('input[name="yks-t-y"]');
        var yks_c_y = $('input[name="yks-c-y"]');
        var yks_t2_y = $('input[name="yks-t2-y"]');
        var yks_c2_y = $('input[name="yks-c2-y"]');
        var yks_fe_y = $('input[name="yks-fe-y"]');
        var yks_d_y = $('input[name="yks-d-y"]');
        var yks_di_y = $('input[name="yks-di-y"]');
        var yks_m_n = $('input[name="yks-m-n"]');
        var yks_f_n = $('input[name="yks-f-n"]');
        var yks_k_n = $('input[name="yks-k-n"]');
        var yks_b_n = $('input[name="yks-b-n"]');
        var yks_e_n = $('input[name="yks-e-n"]');
        var yks_t_n = $('input[name="yks-t-n"]');
        var yks_c_n = $('input[name="yks-c-n"]');
        var yks_t2_n = $('input[name="yks-t2-n"]');
        var yks_c2_n = $('input[name="yks-c2-n"]');
        var yks_fe_n = $('input[name="yks-fe-n"]');
        var yks_d_n = $('input[name="yks-d-n"]');
        var yks_di_n = $('input[name="yks-di-n"]');
        var yks_say_ham = $('input[name="yks-say-ham"]');
        var yks_ea_ham = $('input[name="yks-ea-ham"]');
        var yks_s_ham = $('input[name="yks-soz-ham"]');
        var yks_d_ham = $('input[name="yks-dil-ham"]');
        var yks_say_yer = $('input[name="yks-say-yer"]');
        var yks_ea_yer = $('input[name="yks-ea-yer"]');
        var yks_s_yer = $('input[name="yks-soz-yer"]');
        var yks_d_yer = $('input[name="yks-dil-yer"]');
        var diploma = $('input[name="diploma"]');
        if (100 < Number(diploma.val())) {
            diploma.val(100);
        }
        if ($('input[name="kirikOBP"]:checked').val() !== undefined) {
            var diplomapuan = (Number(diploma.val()) * 0.30);
        } else {
            var diplomapuan = (Number(diploma.val()) * 0.6);
        }
        limit(tyt_m_d, tyt_m_y, 40);
        limit(tyt_t_d, tyt_t_y, 40);
        limit(tyt_s_d, tyt_s_y, 20);
        limit(tyt_f_d, tyt_f_y, 20);
        limit(yks_m_d, yks_m_y, 40);
        limit(yks_f_d, yks_f_y, 14);
        limit(yks_k_d, yks_k_y, 13);
        limit(yks_b_d, yks_b_y, 13);
        limit(yks_e_d, yks_e_y, 24);
        limit(yks_t_d, yks_t_y, 10);
        limit(yks_c_d, yks_c_y, 6);
        limit(yks_t2_d, yks_t2_y, 11);
        limit(yks_c2_d, yks_c2_y, 11);
        limit(yks_fe_d, yks_fe_y, 12);
        limit(yks_d_d, yks_d_y, 6);
        limit(yks_di_d, yks_di_y, 80);
        tyt_m_n.val(tyt_m_d.val() - (tyt_m_y.val() / 4));
        tyt_t_n.val(tyt_t_d.val() - (tyt_t_y.val() / 4));
        tyt_s_n.val(tyt_s_d.val() - (tyt_s_y.val() / 4));
        tyt_f_n.val(tyt_f_d.val() - (tyt_f_y.val() / 4));
        yks_m_n.val(yks_m_d.val() - (yks_m_y.val() / 4));
        yks_f_n.val(yks_f_d.val() - (yks_f_y.val() / 4));
        yks_k_n.val(yks_k_d.val() - (yks_k_y.val() / 4));
        yks_b_n.val(yks_b_d.val() - (yks_b_y.val() / 4));
        yks_e_n.val(yks_e_d.val() - (yks_e_y.val() / 4));
        yks_t_n.val(yks_t_d.val() - (yks_t_y.val() / 4));
        yks_c_n.val(yks_c_d.val() - (yks_c_y.val() / 4));
        yks_t2_n.val(yks_t2_d.val() - (yks_t2_y.val() / 4));
        yks_c2_n.val(yks_c2_d.val() - (yks_c2_y.val() / 4));
        yks_fe_n.val(yks_fe_d.val() - (yks_fe_y.val() / 4));
        yks_d_n.val(yks_d_d.val() - (yks_d_y.val() / 4));
        yks_di_n.val(yks_di_d.val() - (yks_di_y.val() / 4));
        tyt = (tyt_t_n.val() * 2.841075) + (tyt_s_n.val() * 3.14375) + (tyt_m_n.val() * 2.873075) + (tyt_f_n.val() * 3.13345) + (145.891);
        tm = (127.398) + (1.2185 * tyt_t_n.val()) + (1.3483 * tyt_s_n.val()) + (1.232225 * tyt_m_n.val()) + (1.3439 * tyt_f_n.val()) + (2.645925 * yks_m_n.val()) + (yks_e_n.val() * 3.206) + (yks_t_n.val() * 3.3335) + (yks_c_n.val() * 2.27683333);
        ts = (127.679) + (1.151925 * tyt_t_n.val()) + (1.2746 * tyt_s_n.val()) + (1.164875 * tyt_m_n.val()) + (1.27045 * tyt_f_n.val()) + (yks_t_n.val() * 3.1513) + (yks_c_n.val() * 2.1523) + (yks_t2_n.val() * 3.5133) + (yks_c2_n.val() * 2.215) + (yks_fe_n.val() * 3.89475) + (yks_d_n.val() * 2.93116667) + (3.03075 * yks_e_n.val());
        mf = (125.412) + (1.192775 * tyt_t_n.val()) + (1.31985 * tyt_s_n.val()) + (1.2062 * tyt_m_n.val()) + (1.3155 * tyt_f_n.val()) + (2.59005 * yks_m_n.val()) + (3.1925 * yks_f_n.val()) + (2.95038462 * yks_k_n.val()) + (3.11269231 * yks_b_n.val());
        yks = ((tyt - 100) * 40) / 100;
        dil = (110.465) + (1.467675 * tyt_t_n.val()) + (1.624 * tyt_s_n.val()) + (1.4842 * tyt_m_n.val()) + (1.6187 * tyt_f_n.val()) + (yks_di_n.val() * 2.6235375);
        tyt_y = (tyt) + (diplomapuan);
        tm_y = (tm) + (diplomapuan);
        ts_y = (ts) + (diplomapuan);
        mf_y = (mf) + (diplomapuan);
        dil_y = (dil) + (diplomapuan);
        tyt_ham.val(tyt.toFixed(4));
        tyt_yer.val(tyt_y.toFixed(4));
        yks_say_ham.val(mf.toFixed(4));
        yks_ea_ham.val(tm.toFixed(4));
        yks_s_ham.val(ts.toFixed(4));
        yks_d_ham.val(dil.toFixed(4));
        yks_say_yer.val(mf_y.toFixed(4));
        yks_ea_yer.val(tm_y.toFixed(4));
        yks_s_yer.val(ts_y.toFixed(4));
        yks_d_yer.val(dil_y.toFixed(4));

        function puanlimit(max, limit) {
            if (limit < Number(max.val())) {
                max.val(limit);
            }
        }
        puanlimit(tyt_ham, 500);
        puanlimit(tyt_yer, 560);
        puanlimit(yks_say_ham, 500);
        puanlimit(yks_say_yer, 560);
        puanlimit(yks_ea_ham, 500);
        puanlimit(yks_ea_yer, 560);
        puanlimit(yks_s_ham, 500);
        puanlimit(yks_s_yer, 560);
        puanlimit(yks_d_ham, 500);
        puanlimit(yks_d_yer, 560);
        tyt_puan = tyt_y.toFixed(0);
        ea_puan = tm_y.toFixed(0);
        soz_puan = ts_y.toFixed(0);
        say_puan = mf_y.toFixed(0);
        dil_puan = dil_y.toFixed(0);
        if (tyt_puan > 560) {
            tyt_puan = 560;
        };
        if (ea_puan > 560) {
            ea_puan = 560;
        };
        if (soz_puan > 560) {
            soz_puan = 560;
        };
        if (say_puan > 560) {
            say_puan = 560;
        };
        if (dil_puan > 560) {
            dil_puan = 560;
        };
        $("#tyt-2022").val(yks_2022.filter(x => x.puan == tyt_puan).map(x => x.tyt));
        $("#yks-soz-2022").val(yks_2022.filter(x => x.puan == soz_puan).map(x => x.soz));
        $("#yks-say-2022").val(yks_2022.filter(x => x.puan == say_puan).map(x => x.say));
        $("#yks-ea-2022").val(yks_2022.filter(x => x.puan == ea_puan).map(x => x.ea));
        $("#yks-dil-2022").val(yks_2022.filter(x => x.puan == dil_puan).map(x => x.dil));
        $("#tyt-2021").val(yks_2021.filter(x => x.puan == tyt_puan).map(x => x.tyt));
        $("#yks-soz-2021").val(yks_2021.filter(x => x.puan == soz_puan).map(x => x.soz));
        $("#yks-say-2021").val(yks_2021.filter(x => x.puan == say_puan).map(x => x.say));
        $("#yks-ea-2021").val(yks_2021.filter(x => x.puan == ea_puan).map(x => x.ea));
        $("#yks-dil-2021").val(yks_2021.filter(x => x.puan == dil_puan).map(x => x.dil));
        $("#tyt-2020").val(yks_2020.filter(x => x.puan == tyt_puan).map(x => x.tyt));
        $("#yks-soz-2020").val(yks_2020.filter(x => x.puan == soz_puan).map(x => x.soz));
        $("#yks-say-2020").val(yks_2020.filter(x => x.puan == say_puan).map(x => x.say));
        $("#yks-ea-2020").val(yks_2020.filter(x => x.puan == ea_puan).map(x => x.ea));
        $("#yks-dil-2020").val(yks_2020.filter(x => x.puan == dil_puan).map(x => x.dil));
    }
        $('input').on('keyup', function() {
            ykshesapla();
        });
        $('input').on('click', function() {
            ykshesapla();
        });
        $('.form-group').on('click', function() {
            ykshesapla();
        });

});
