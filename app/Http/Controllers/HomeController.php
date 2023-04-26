<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use SoapClient;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {

        // TYT hazırlık öğrencisi için ders listesi
        $dersler = [
            'Matematik' => 6,
            'Türkçe' => 5,
            'Tarih' => 4,
            'Coğrafya' => 3,
            'Biyoloji' => 4,
            'Fizik' => 4,
            'Kimya' => 4,
            "Özel Ders" => 5
        ];

        // Derslerin günlük maksimum saat sınırları
        $ders_gunluk_saat_limitleri = [
            'Matematik' => 2,
            'Türkçe' => 2,
            'Tarih' => 2,
            'Coğrafya' => 2,
            'Biyoloji' => 2,
            'Fizik' => 2,
            'Kimya' => 2,
            'Özel Ders' => 2
        ];

        // Öğrencinin seçtiği dersler
        $secilen_dersler = array_keys($dersler);

        // Öğrencinin uygun olduğu saatler (0: Pazartesi, 1: Salı, vb.)
        $uygun_saatler = [
            0 => [1, 2, 3, 4, 5, 6, 7],
            1 => [1, 2, 3, 4, 5, 6, 7],
            2 => [1, 2, 3, 4, 5, 6, 7],
            3 => [1, 2, 3, 4, 5, 6, 7],
            4 => [1, 2, 3, 4, 5, 6, 7],
        ];

        $ders_programi = [];

        function rand_weighted(array $values, array $weights)
        {
            $total = array_sum($weights);
            $rand = mt_rand(1, $total);
            foreach ($values as $key => $value) {
                if (isset($weights[$key])) {
                    $rand -= $weights[$key];
                    if ($rand <= 0) {
                        return $value;
                    }
                }
            }
            return end($values);
        }

        function haftalik_ders_programi_olustur($dersler, $ders_gunluk_saat_limitleri, $uygun_saatler, $ders_sureleri)
        {
            $secilen_dersler = array_keys($dersler);
            $ders_programi = [];

            foreach ($secilen_dersler as $ders) {
                $saat = $dersler[$ders];
                $gunluk_saat_limit = $ders_gunluk_saat_limitleri[$ders];

                while ($saat > 0 && count($uygun_saatler) > 0) {
                    $gun = rand_weighted(array_keys($uygun_saatler), array_map('count', $uygun_saatler));
                    $saatler = $uygun_saatler[$gun];
                    $eklenen_saatler = 0;

                    while ($saat > 0 && $eklenen_saatler < $gunluk_saat_limit && count($saatler) > 0) {
                        $saat_numarasi = array_rand($saatler);
                        $ders_programi[$gun][] = [
                            'ders' => $ders,
                            'saat' => $saatler[$saat_numarasi],
                            'sure' => $ders_sureleri[$ders]
                        ];

                        unset($uygun_saatler[$gun][$saat_numarasi]);
                        unset($saatler[$saat_numarasi]);
                        $saat--;
                        $eklenen_saatler++;
                    }

                    if (count($uygun_saatler[$gun]) === 0) {
                        unset($uygun_saatler[$gun]);
                    }
                }
            }

            ksort($ders_programi);
            return $ders_programi;
        }


        $ders_sureleri = [
            'Matematik' => 45,
            'Türkçe' => 45,
            'Tarih' => 45,
            'Coğrafya' => 45,
            'Biyoloji' => 45,
            'Fizik' => 45,
            'Kimya' => 45,
            "Özel Ders" => 45
        ];

        $ders_programi = haftalik_ders_programi_olustur($dersler, $ders_gunluk_saat_limitleri, $uygun_saatler, $ders_sureleri);

        dd($ders_programi);
    }
}
