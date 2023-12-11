<?php

namespace App\Http\Controllers;

use App\Models\DayHours;
use App\Models\DayTable;
use App\Models\Exams;
use App\Models\HourTable;
use App\Models\Lesson;
use App\Models\Organization;
use App\Models\OrganizationTeacher;
use App\Models\Teacher;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;

class HomeController extends Controller
{


    public function studentIndex()
    {
        $timeTable = TimeTable::where('student_id', auth('student')->user()->id)->first();
        return view('student.index')->with('timeTable', $timeTable);
    }

    public function organizationIndex()
    {
        return view('organizationAdmin.index');
    }

    public function teacherIndex()
    {
        return view('teacher.index');
    }

    public function organizationAnalyzes()
    {
        return view('organizationAdmin.analyzes');
    }
    public function studentAnalyzes()
    {
        return view('student.analyzes');
    }

    public function getStatsOrganization()
    {
        $students = Student::where('organization_id', auth('organization')->user()->id)->pluck('id')->toArray();
        $exams = Exams::whereIn('student_id', $students)->get();
        if($exams){
            $dataForDate = [];
            $countsForDate = [];
            $dataForBatch = [];
            $countsForBatch = [];
            $dataForLesson = [];
            $countsForLesson = [];
            $dataForAll = [];
            if(count($exams) > 0){
                $earliestDate = null;
                $latestDate = null;
                $batches = 0;
                $student = 0;
                foreach($exams as $exam){
                    $date = $exam->created_at->format('d-m-Y');

                    $batch = $exam->batchName();
                    $score = $exam->score();
                    if(count($score->lessons) == 0) continue;
                    if ($earliestDate === null || strtotime($date) < strtotime($earliestDate)) {
                        $earliestDate = $date;
                    }
                    if ($latestDate === null || strtotime($date) > strtotime($latestDate)) {
                        $latestDate = $date;
                    }

                    $student++;
                    $lessons = $score->lessons;
                    if (array_key_exists($date, $dataForDate)) {
                        $dataForDate[$date] += $score->total;
                    } else {
                        $dataForDate[$date] = $score->total;
                    }
                    if (array_key_exists($date, $countsForDate)) {
                        $countsForDate[$date]++;
                    } else {
                        $countsForDate[$date] = 1;
                    }
                    if($batch){
                        if (array_key_exists($batch, $dataForBatch)) {
                            $dataForBatch[$batch] += $score->total;
                        } else {
                            $dataForBatch[$batch] = $score->total;
                        }
                        if (array_key_exists($batch, $countsForBatch)) {
                            $countsForBatch[$batch]++;
                        } else {
                            $countsForBatch[$batch] = 1;
                        }
                        $batches++;
                    }
                    foreach($lessons as $key => $value){
                        if (array_key_exists($key, $dataForLesson)) {
                            $dataForLesson[$key] += $value;
                        } else {
                            $dataForLesson[$key] = $value;
                        }
                        if (array_key_exists($key, $countsForLesson)) {
                            $countsForLesson[$key]++;
                        } else {
                            $countsForLesson[$key] = 1;
                        }
                        $countsForLesson[$key]++;
                    }
                }
                $labelsForDate = array_keys($dataForDate);
                $valuesForDate = [];
                foreach($dataForDate as $key => $total){
                    $valuesForDate[] = round($total / $countsForDate[$key], 2);
                }
                $labelsForBatch = array_keys($dataForBatch);
                $valuesForBatch = [];
                foreach($dataForBatch as $key => $total){
                    $valuesForBatch[] = round($total / $countsForBatch[$key], 2);
                }
                $labelsForLesson = [];
                $valuesForLesson = [];
                foreach($dataForLesson as $key => $total){
                    $labelsForLesson[] = Lesson::where('id', $key)->first()->name;
                    $valuesForLesson[] = round($total / $countsForLesson[$key], 2);
                }
              $printData = [
                  "data" => [
                        [
                            "title" => "Tüm Sınavlar (Bireysel,Toplu)",
                            "labels" => $labelsForDate,
                            "data" => $valuesForDate,
                            "visible" => $countsForDate > 1,
                            "id" => Str::random(16)
                        ],
                        [
                            "title" => "Toplu Sınavlar",
                            "labels" => $labelsForBatch,
                            "data" => $valuesForBatch,
                            "visible" => $countsForBatch > 1,
                            "id" => Str::random(16)
                        ],
                        [
                            "title" => "Derslere Göre",
                            "labels" => $labelsForLesson,
                            "data" => $valuesForLesson,
                            "visible" => $countsForLesson > 1,
                            "id" => Str::random(16)
                        ]
                  ],
                  "description" => $earliestDate . " İle $latestDate Tarihleri Arasında Yapılan Sınavların Analizi",
                  "count" => [
                      "total" => count($exams)." Sınav",
                      "batch" => $batches." Toplu Sınav",
                      "lesson" => count($dataForLesson)." Ders",
                      "student" => $student." Öğrenci Katılımı"
                  ],
                "status" => true
              ];
                return response()->json($printData);
            }else{
                return response()->json(['status' => false]);
            }
        }else{
            return response()->json(['status' => false]);
        }

    }
    public function getStatsStudent($student_id=null)
    {
        if(!$student_id) $student_id = auth('student')->user()->id;
        $exams = Exams::where('student_id', $student_id)->get();
        if($exams){
            $dataForDate = [];
            $countsForDate = [];
            $dataForBatch = [];
            $countsForBatch = [];
            $dataForLesson = [];
            $countsForLesson = [];
            $dataForAll = [];
            $individual = [];
            if(count($exams) > 0){
                $earliestDate = null;
                $latestDate = null;
                $batches = 0;
                $student = 0;
                foreach($exams as $exam){
                    $date = $exam->created_at->format('d-m-Y H:i');

                    $batch = $exam->batchName();
                    $score = $exam->score();
                    if(count($score->lessons) == 0) continue;
                    if ($earliestDate === null || strtotime($date) < strtotime($earliestDate)) {
                        $earliestDate = $date;
                    }
                    if ($latestDate === null || strtotime($date) > strtotime($latestDate)) {
                        $latestDate = $date;
                    }

                    $student++;
                    $lessons = $score->lessons;
                    if (array_key_exists($date, $dataForDate)) {
                        $dataForDate[$date] += $score->total;
                    } else {
                        $dataForDate[$date] = $score->total;
                    }
                    if (array_key_exists($date, $countsForDate)) {
                        $countsForDate[$date]++;
                    } else {
                        $countsForDate[$date] = 1;
                    }
                    if($batch){
                        if (array_key_exists($batch, $dataForBatch)) {
                            $dataForBatch[$batch] += $score->total;
                        } else {
                            $dataForBatch[$batch] = $score->total;
                        }
                        if (!array_key_exists($batch, $countsForBatch)) {
                            $countsForBatch[$batch] = 1;
                        }
                    }else{
                        $date = $exam->created_at->format('d-m-Y H:i');
                        if(array_key_exists($date, $individual)){
                            $individual[$date] += $score->total;
                        }else{
                            $individual[$date] = $score->total;
                        }
                    }
                    foreach($lessons as $key => $value){
                        if (array_key_exists($key, $dataForLesson)) {
                            $dataForLesson[$key] += $value;
                        } else {
                            $dataForLesson[$key] = $value;
                        }
                        if (array_key_exists($key, $countsForLesson)) {
                            $countsForLesson[$key]++;
                        } else {
                            $countsForLesson[$key] = 1;
                        }
                        $countsForLesson[$key]++;
                    }
                }
                $labelsForDate = array_keys($dataForDate);
                $valuesForDate = [];
                foreach($dataForDate as $key => $total){
                    $valuesForDate[] = round($total / $countsForDate[$key], 2);
                }
                $labelsForBatch = array_keys($dataForBatch);
                $valuesForBatch = [];
                foreach($dataForBatch as $key => $total){
                    $valuesForBatch[] = round($total / $countsForBatch[$key], 2);
                }
                $labelsForLesson = [];
                $valuesForLesson = [];
                foreach($dataForLesson as $key => $total){
                    $labelsForLesson[] = Lesson::where('id', $key)->first()->name;
                    $valuesForLesson[] = round($total / $countsForLesson[$key], 2);
                }
              $printData = [
                  "data" => [
                        [
                            "title" => "Tüm Sınavlar (Bireysel,Toplu)",
                            "labels" => $labelsForDate,
                            "data" => $valuesForDate,
                            "visible" => $countsForDate > 1,
                            "id" => Str::random(16)
                        ],
                        [
                            "title" => "Bireysel Sınavlar",
                            "labels" => array_keys($individual),
                            "data" => array_values($individual),
                            "visible" => count($individual) > 1,
                            "id" => Str::random(16)
                        ],
                        [
                            "title" => "Toplu Sınavlar",
                            "labels" => $labelsForBatch,
                            "data" => $valuesForBatch,
                            "visible" => $countsForBatch > 1,
                            "id" => Str::random(16)
                        ],
                        [
                            "title" => "Derslere Göre",
                            "labels" => $labelsForLesson,
                            "data" => $valuesForLesson,
                            "visible" => $countsForLesson > 1,
                            "id" => Str::random(16)
                        ]
                  ],
                  "description" => $earliestDate . " İle $latestDate Tarihleri Arasında Yapılan Sınavların Analizi",
                  "count" => [
                      "total" => count($exams)." Sınav",
                      "batch" => count($countsForBatch)." Toplu Sınav",
                      "individual" => count($individual)." Bireysel Sınav",
                      "lesson" => count($dataForLesson)." Ders"
                  ],
                "status" => true
              ];
                return response()->json($printData);
            }else{
                return response()->json(['status' => false]);
            }
        }else{
            return response()->json(['status' => false]);
        }

    }

    public function systemAdminSettings()
    {
        $user = auth("admin")->user();
        return view('systemAdmin.settings', compact('user'));
    }
    public function systemAdminSettingsStore(Request $request)
    {
        try {
            $user = \App\Models\User::find(auth("admin")->user()->id);
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            if(trim($request->input("password")) != ""){
                $user->password = Hash::make(trim($request->input("password")));
            }
            $user->save();
            $user->touch();
            return response()->json(['status' => true, 'message' => 'Bilgileriniz Güncellendi']);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function organizationAdminSettings()
    {
        $user = auth("organization")->user();
        return view('organizationAdmin.settings', compact('user'));
    }

    public function organizationAdminSettingsStore(Request $request)
    {
        try {
            $user = Organization::find(auth("organization")->user()->id);
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->address = $request->input("address");
            $user->phone = $request->  input("phone");
            if(trim($request->input("password")) != ""){
                $user->password = Hash::make(trim($request->input("password")));
            }
            $user->save();
            $user->touch();
            return response()->json(['status' => true, 'message' => 'Bilgileriniz Güncellendi']);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    } public function teacherSettings()
    {
        $user = auth("teacher")->user();
        return view('teacher.settings', compact('user'));
    }

    public function teacherSettingsStore(Request $request)
    {
        try {
            $user = Teacher::find(auth("teacher")->user()->id);
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->live_lesson_url = $request->input("live_lesson_url");
            $user->phone = $request->  input("phone");
            if(trim($request->input("password")) != ""){
                $user->password = Hash::make(trim($request->input("password")));
            }
            $user->save();
            $user->touch();
            return response()->json(['status' => true, 'message' => 'Bilgileriniz Güncellendi']);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }public function studentSettings()
    {
        $user = auth("student")->user();
        return view('student.settings', compact('user'));
    }

    public function studentSettingsStore(Request $request)
    {
        try {
            $user = Student::find(auth("student")->user()->id);
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->address = $request->input("address");
            $user->identity_number = $request->  input("identity_number");
            $user->phone = $request->  input("phone");
            if(trim($request->input("password")) != ""){
                $user->password = Hash::make(trim($request->input("password")));
            }
            $user->save();
            $user->touch();
            return response()->json(['status' => true, 'message' => 'Bilgileriniz Güncellendi']);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

}
