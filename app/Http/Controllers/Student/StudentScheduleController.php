<?php

namespace App\Http\Controllers\Student;

use App\Models\Classes;
use App\Models\DayHours;
use App\Models\DayTable;
use App\Models\HourTable;
use App\Models\Lesson;
use App\Models\OrganizationTeacher;
use App\Models\Teacher;
use App\Models\TimeTable;
use Illuminate\Routing\Controller;
use function App\Http\Controllers\rand_weighted;

class StudentScheduleController extends Controller
{
    public function index()
    {
        $timeTable = TimeTable::where('student_id', auth('student')->user()->id)->first();
        if($timeTable){
            return view('student.schedule.index', compact('timeTable'));
        }else{
            return redirect()->route('student.schedule.create');
        }
    }

    public function create()
    {
        $lessons = Lesson::where('grade', auth('student')->user()->grade)->get();
        return view('student.schedule.create')
            ->with('lessons', $lessons);
    }

    public function store()
    {
        $days = request()->input('days');
        $lessons = request()->input('lesson_select');
        $uygun_saatler = [];
        $start_times = [];
        $startTimeTable = TimeTable::where('student_id', auth('student')->user()->id)->first();
        foreach ($days as $key=>$day) {
            $ar_tmp = [];
            for($i = 1; $i <= $day["hours"]; $i++){
                $ar_tmp[] = $i;
            }
            $start_times["-".$key] = $day["start_time"];
            $uygun_saatler[$key] = $ar_tmp;
        }
        $dersler = [];
        foreach ($lessons as $key => $lesson) {
            if(isset($lesson['status'])){
                $dersler[$key] = $lesson['hour'];
            }
        }
        if(count($dersler) > 0){
            $schedule = $this->haftalik_ders_programi_olustur($dersler,$uygun_saatler);
            $timeTable = new TimeTable();
            $timeTable->student_id = auth('student')->user()->id;
            if($timeTable->saveOrFail()){
                foreach ($schedule as $key => $value) {
                    $dayTable = new DayTable();
                    $dayTable->time_table_id = $timeTable->id;
                    $dayTable->day = $key;
                    $dayTable->start_time = $start_times["-".$key];
                    $dayTable->saveOrFail();
                        foreach ($value as $key2 => $value2) {
                            $hourTable = new HourTable();
                            $hourTable->day_table_id = $dayTable->id;
                            $hourTable->index = $value2["index"];
                            $hourTable->recess = $value2["recess"];
                            $hourTable->duration = $value2["duration"];
                            $hourTable->saveOrFail();
                            $dayHour = new DayHours();
                            $dayHour->hour_table_id = $hourTable->id;
                            if($value2["lesson_id"] == 0){
                                $dayHour->is_live = true;
                            }else{
                                $dayHour->lesson_id = $value2["lesson_id"];
                            }
                            $dayHour->saveOrFail();
                        }
                }
                if($startTimeTable){
                    $startTimeTable->delete();
                }
                return response()->json(['message' => 'Ders Programı Başarıyla Oluşturuldu.', 'status' => true, 'url' => route('student.schedule.index')]);
            }else{
                return response()->json(['message' => 'Bir Hata Oluştu.', 'status' => false]);
            }
        }else{
            return response()->json(['message' => 'Lütfen En Az Bir Ders Seçiniz.', 'status' => false]);
        }
        return response()->json($uygun_saatler);
    }
    public function rand_weighted(array $values, array $weights)
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

    public function haftalik_ders_programi_olustur($dersler, $uygun_saatler, )
    {
        $secilen_dersler = array_keys($dersler);
        $ders_programi = [];

        foreach ($secilen_dersler as $ders) {
            $saat = $dersler[$ders];
            $gunluk_saat_limit = 2;
            while ($saat > 0 && count($uygun_saatler) > 0) {
                $gun = $this->rand_weighted(array_keys($uygun_saatler), array_map('count', $uygun_saatler));
                $saatler = $uygun_saatler[$gun];
                $eklenen_saatler = 0;

                while ($saat > 0 && $eklenen_saatler < $gunluk_saat_limit && count($saatler) > 0) {
                    $saat_numarasi = array_rand($saatler);
                    $ders_programi[$gun][] = [
                        'lesson_id' => $ders,
                        'index' => $saatler[$saat_numarasi],
                        'duration' => 45,
                        'recess' => 15
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
}
