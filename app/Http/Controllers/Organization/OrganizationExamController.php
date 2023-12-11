<?php

namespace App\Http\Controllers\Organization;

use App\Exports\AnswerKeyExport;
use App\Exports\ExampleExamSchemeExport;
use App\Models\AnswerKey;
use App\Models\AnswerValues;
use App\Models\BatchExamLessons;
use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Lesson;
use App\Models\OpticalParameter;
use App\Models\Student;
use App\Models\ExamAnalysis;
use App\Services\ExamImportHelper;
use App\Services\FmtReader;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class OrganizationExamController extends \Illuminate\Routing\Controller
{

    public function index()
    {
        return view('organizationAdmin.exam.all', [
            'exams' => BatchExams::where('organization_id', auth()->guard('organization')->user()->id)->get(),
        ]);
    }

    public function indexExam($id)
    {
        return view('organizationAdmin.exam.allExam', [
            'exams' => Exams::where('batch_exam_id', $id)->get(),
        ]);
    }

    public function downloadAnswerScheme()
    {
        return Excel::download(new AnswerKeyExport,'cevap-anahtari-ornek.xlsx');
    }

    protected function findLessonId($lessons, $lessonName)
    {
        //use similar_text() function
        $max = 0;
        $lesson_id = null;
        foreach ($lessons as $lesson) {
            similar_text($lessonName, $lesson->name, $percent);
            if ($percent > $max) {
                $max = $percent;
                $lesson_id = $lesson->id;
            }
        }
        return $lesson_id;
    }

    protected function findTopicId($topic,$topics)
    {
        $max = 0;
        $topic_id = null;
        foreach ($topics as $t) {
            similar_text($topic, $t["name"], $percent);
            if ($percent > $max) {
                $max = $percent;
                $topic_id = $t["id"];
            }
        }
        return $topic_id;
    }

    public function uploadAnswers($id)
    {
        $exam = BatchExams::find($id);
        if(!$exam){
            return response()->json(['status' => false, 'message' => 'Sınav Bulunamadı']);
        }
        $excel = Excel::toArray((object)[], request()->file('file'))[0];
        array_shift($excel);
        $lessons = $exam->lessons()->get();
        $topics = $exam->topics();
        $answers = [];
        foreach ($excel as $row) {
            $lesson = $row[1];
            $number = $row[0];
            $answer = $row[2];
            $b_number = $row[3];
            $topic = $row[4];
            //get lesson id
            $lesson_id = $this->findLessonId($lessons, $lesson);
            if ($lesson_id) {
                $answers[$lesson_id][$number] = [
                    'answer' => $answer,
                    'b_number' => $b_number,
                    'topic' => $this->findTopicId($topic,$topics[$lesson_id]),
                ];
            }
        }
        $control = AnswerKey::where('batch_exam_id', $id)->first();
        if ($control) {
            $control->delete();
        }
        $newAn = new AnswerKey();
        $newAn->batch_exam_id = $id;
        $newAn->file = '';
        if($newAn->save()){
            foreach ($answers as $lesson_id => $answerArray){
                foreach ($answerArray as $number => $answer) {
                    $newAnswer = new AnswerValues();
                    $newAnswer->answer_key_id = $newAn->id;
                    $newAnswer->lesson_id = $lesson_id;
                    $newAnswer->question_number = $number;
                    $newAnswer->answer_value = $answer['answer'];
                    $newAnswer->b_number = $answer['b_number'];
                    $newAnswer->topic = $answer['topic'];
                    $newAnswer->save();
                }
            }
            return response()->json(['status' => true, 'message' => 'Cevap Anahtarı Kaydedildi']);
        }else{
            return response()->json(['status' => false, 'message' => 'Cevap Anahtarı Kaydedilemedi']);
        }
    }

    protected function findArea($areas, $name)
    {
        if($areas){
            foreach ($areas as $area) {
                if (strtolower($area['name']) === strtolower($name)) {
                    return $area;
                }
            }
        }
        return null;
    }

    public function readExam(Request $request,$id): \Illuminate\Http\JsonResponse
    {
        try {
            $batchExam = BatchExams::find($id);
            if($batchExam){
                $lessons = [];
                $i = 0;
                foreach($batchExam->lessons()->get() as $lesson){
                    $lessons[$i] = $lesson;
                    $i++;
                }
                $answerKey = $batchExam->answerKey()->first();
                if($answerKey) {
                    $answerValues = $answerKey->getAnswerArray();
                    if(count($answerValues) != 0){
                        $fmtContent = OpticalParameter::find($request->input('fmt_id'));
                        if($fmtContent){
                            $fmtContent = $fmtContent->getFmtContent();
                            $txtFile = $request->file('file');
                            $txtFileContent = $txtFile->getContent();
                            $txtFileEncoding = mb_detect_encoding($txtFileContent, mb_detect_order(), true);
                            if($txtFileEncoding != 'UTF-8'){
                                $txtFileContent = mb_convert_encoding($txtFileContent, 'UTF-8', $txtFileEncoding);
                            }
                            $reader = new FmtReader($fmtContent, $txtFileContent);
                            $results = $reader->getData();
                            $importedExams = [];
                            $unidentifiedExams = [];

                            foreach ($results as $result){
                                $queries = [
                                    Student::where('identity_number',$result['tc_no']),
                                    Student::where('name','LIKE','%'.str_replace("."," ",$result['ad_soyad']).'%')
                                    ->orWhere('name','LIKE','%'.str_replace(".","",$result['ad_soyad']).'%')
                                    ->orWhere('name','LIKE','%'.explode('.',$result['ad_soyad'])[0].'%')
                                    ->orWhere('name','LIKE','%'.explode('.',$result['ad_soyad'])[1].'%'),
                                    Student::findMostSimilarUser(str_replace("."," ",$result['ad_soyad'])),
                                ];
                                $student = false;
                                foreach ($queries as $query){
                                    if($student = $query->first()){
                                        break;
                                    }else{
                                        $student = false;
                                    }
                                }
                                if(!$student){
                                    $unidentifiedExams[] = $result;
                                    continue;
                                }
                                $examLessons = [];
                                $b = 0;
                                foreach ($result['lessons'] as $lesson){
                                    $less = [
                                        "answers" => $lesson,
                                        "lesson" => $lessons[$b] ?? null,
                                    ];
                                    $examLessons[] = $less;
                                    $b++;
                                }
                                $examResults = [];
                                $booklets = [];
                                foreach($answerValues as $booklet => $answersForThisBooklet){
                                    $total_correct = 0;
                                    $total_wrong = 0;
                                    $lessonControls = [];
                                    foreach ($examLessons as $examLesson){
                                        if($examLesson['lesson'] != null){
                                            $answersExam = $answersForThisBooklet[$examLesson['lesson']->id] ?? null;
                                            if($answersExam){
                                                $answers = str_split($examLesson['answers']);
                                                $ca = 0;
                                                $wa = 0;
                                                $ea = 0;
                                                $questionAnalysisies = [];
                                                foreach ($answers as $number => $answer){
                                                    if($number+1 == count($answersExam)){
                                                        break;
                                                    }
                                                    $correctAnswer = $answersExam[($number+1)] ?? null;
                                                    if($answer != "." || $answer != " "){
                                                        if($correctAnswer != null){
                                                            if($answer == $correctAnswer['answer']){
                                                                $ca++;
                                                                $questAnalysis = new ExamAnalysis();
                                                                $questAnalysis->lesson_id = $examLesson['lesson']->id;
                                                                $questAnalysis->question_number = $booklet.' - '.($number+1);
                                                                $questAnalysis->student_id = $student->id;
                                                                $questAnalysis->topic_id = $correctAnswer['topic'];
                                                                $questAnalysis->status = 'correct';
                                                                $questionAnalysisies[] = $questAnalysis;
                                                            }else{
                                                                $wa++;
                                                                $questAnalysis = new ExamAnalysis();
                                                                $questAnalysis->lesson_id = $examLesson['lesson']->id;
                                                                $questAnalysis->question_number = $booklet.' - '.($number+1);
                                                                $questAnalysis->student_id = $student->id;
                                                                $questAnalysis->topic_id = $correctAnswer['topic'];
                                                                $questAnalysis->status = 'wrong';
                                                                $questionAnalysisies[] = $questAnalysis;
                                                            }
                                                        }else{
                                                            continue;
                                                        }
                                                    }else{
                                                        $ea++;
                                                        $questAnalysis = new ExamAnalysis();
                                                        $questAnalysis->lesson_id = $examLesson['lesson']->id;
                                                        $questAnalysis->question_number = $booklet.' - '.($number+1);
                                                        $questAnalysis->student_id = $student->id;
                                                        $questAnalysis->topic_id = $correctAnswer['topic'];
                                                        $questAnalysis->status = 'empty';
                                                        $questionAnalysisies[] = $questAnalysis;
                                                    }
                                                }
                                                $newExamResult = new ExamResults();
                                                $newExamResult->lesson_id = $examLesson['lesson']->id;
                                                $newExamResult->correct_answers = $ca;
                                                $newExamResult->wrong_answers = $wa;
                                                $newExamResult->empty_answers = $ea;
                                                $bookletControl = [
                                                    'correct_answers' => $ca,
                                                    'wrong_answers' => $wa,
                                                    'empty_answers' => $ea,
                                                    'question_analysisies' => $questionAnalysisies,
                                                    'total'=> $ca - ($wa/4),
                                                    'booklet' => $booklet,
                                                    'exam_result' => $newExamResult,
                                                ];
                                                $total_correct += $ca;
                                                $total_wrong += $wa;
                                                $lessonControls[] = $bookletControl;
                                            }else{
                                                $std = new \stdClass();
                                                $std->lesson_id = 0;
                                                $lessonControls[] = [
                                                    "empty" => true,
                                                    "exam_result" => $std,
                                                    "question_analysisies" => [],
                                                ];
                                            }
                                        }else{
                                            continue;
                                        }
                                    }
                                    $booklets[$booklet] = [
                                        "booklet" => $booklet,
                                        "total_correct" => $total_correct,
                                        "total_wrong" => $total_wrong,
                                        "total" => $total_correct - ($total_wrong/4),
                                        "lessons" => $lessonControls,
                                    ];
                                }
                                $selected = null;
                                $max = 0;
                                foreach ($booklets as $booklet){
                                    if($booklet['total'] > $max){
                                        $max = $booklet['total'];
                                        $selected = $booklet;
                                    }
                                }
                                if($selected != null){
                                    foreach ($selected["lessons"] as $s){
                                        $examResults[] = [
                                            "results" => $s['exam_result'],
                                            "question_analysisies" => $s['question_analysisies'],
                                        ];
                                    }
                                }
                                $exam = new Exams();
                                $exam->student_id = $student->id;
                                $exam->batch_exam_id = $batchExam->id;
                                if($exam->save()){
                                    $resultsExam = [];
                                    foreach ($examResults as $examResult) {
                                        if ($examResult['results']->lesson_id != 0){
                                            $examResult['results']->exam_id = $exam->id;
                                            $examResult['results']->save();
                                        }
                                        $resultsExam[] = $examResult['results'];
                                        foreach ($examResult['question_analysisies'] as $wrongQuestion){
                                            $wrongQuestion->exam_id = $exam->id;
                                            $wrongQuestion->save();
                                        }
                                    }
                                    $exam->identity_number = $student->identity_number;
                                    $exam->name = $student->name;
                                    $exam->lessons = $resultsExam;
                                    $importedExams[] = $exam;
                                }else{
                                    continue;
                                }
                            }
                            $exportLessons = [];
                            foreach ($lessons as $lesson){
                                $exportLessons[] = [
                                    'id' => $lesson->id,
                                    'name' => $lesson->name,
                                ];
                            }

                            return response()->json([
                                "status" => true,
                                "message" => "Sınavlar Başarıyla Kaydedildi",
                                "importedExams" => $importedExams,
                                "unidentifiedExams" => $unidentifiedExams,
                                "lessons" => $exportLessons,
                            ]);
                        }else{
                            return response()->json([
                                "status" => false,
                                "message" => "FMT İçeriği Bulunamadı Sayfayı Yenileyip Tekrar Deneyiniz.",
                            ]);
                        }
                    }else{
                        return response()->json([
                            "status" => false,
                            "message" => "Cevap Anahtarı Bulundu İçerik Bulunamadı.Lütfen Cevap Anahtarınızı Tekrar Yükleyiniz.",
                        ]);
                    }
                }else{
                    return response()->json([
                        "status" => false,
                        "message" => "Cevap Anahtarı Bulunamadı,Lütfen Cevap Anahtarı Ekleyiniz.",
                    ]);
                }
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Sınav Bulunamadı",
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                "status" => false,
                "message" => utf8_encode($e->getMessage()),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
            ]);
        }
    }
    public function download($id)
    {
        $exams = Exams::where('batch_exam_id', $id)->get();
        $organization = Auth::guard('organization')->user();
        //$pdf = PDF::loadView('organizationAdmin.exam.pdf', compact('exams', 'exam', 'organization'));
        $lessonTotals = [];
        $average = 0;
        $examArray = [];
        foreach ($exams as $exam) {
            $student = $exam->student();
            $results = ExamResults::where('exam_id', $exam->id)->get();
            $correct_answers = 0;
            $wrong_answers = 0;
            $resultArray = [];
            foreach ($results as $result) {
                if (!isset($lessonTotals[$result->lesson_id])) {
                    $lessonTotals[$result->lesson_id] = [
                        'total' => 0,
                        'count' => 0,
                        'name' => '',
                    ];
                }
                $lesson = $result->lesson();
                $lessonTotals[$result->lesson_id]["name"] = $lesson->name;
                $lessonTotals[$result->lesson_id]["total"] += $result->correct_answers - ($result->wrong_answers / 4);
                $lessonTotals[$result->lesson_id]["count"] += 1;
                $correct_answers += $result->correct_answers;
                $wrong_answers += $result->wrong_answers;
                $resultArray[] = [
                    'correct_answers' => $result->correct_answers,
                    'wrong_answers' => $result->wrong_answers,
                    'lesson_name' => $lesson->name,
                    'lesson_id' => $lesson->id,
                    'total' => $result->correct_answers - ($result->wrong_answers / 4),
                    'average' => 0,
                ];
            }
            $examArray[] = [
                'id' => $exam->id,
                'student' => $student->name,
                'results' => $resultArray,
                'total' => $correct_answers - ($wrong_answers / 4),
                'correct_answers' => $correct_answers,
                'wrong_answers' => $wrong_answers,
                'created_at' => $exam->created_at->format('d.m.Y H:i:s'),
                'average' => 0,
                'wrong_questions' => $exam->getWrongQuestions() ?? [],
            ];
            $average += $correct_answers - ($wrong_answers / 4);
        }
        $average = round($average / count($exams),2);
        $page = 2;
        //examArray sort by total
        usort($examArray, function ($a, $b) {
            return $a['total'] < $b['total'];
        });
        //lessonTotals add $examArray
        foreach ($examArray as $key => $exam) {
            foreach ($exam['results'] as $key2 => $result) {
                $totalLesson = round($lessonTotals[$result['lesson_id']]['total'] / $lessonTotals[$result['lesson_id']]['count'],2);
                $examArray[$key]['results'][$key2]['average'] = $totalLesson;
                $examArray[$key]['results'][$key2]['higher'] = $result['total'] > $totalLesson ? true : false;
            }
            $examArray[$key]['higher'] = $exam['total'] > $average ? true : false;
            $examArray[$key]['average'] = $average;
        }
        $batch = BatchExams::find($id);
        $pdf = PDF::loadView('organizationAdmin.exam.showExamsPdf', [
            "examArray" => json_decode(json_encode($examArray)),
            "lessonTotals" => json_decode(json_encode($lessonTotals)),
            "batch" => $batch,
        ]);
        $prefix = $batch->name;
        $filename = $prefix."_sınav-sonucu_".date('d-m-Y_H-i').".pdf";
        return $pdf->download($filename);

    }

    public function downloadPdf($batch, $id)
    {
        $exam = Exams::where('id', $id)->first();
        $pdf = PDF::loadView('organizationAdmin.reports.examScore', [
            'exam' => $exam,
        ])->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($exam->student()->name . '-deneme-sonuc.pdf');
    }
    public function getLessons($batch = false)
    {

        $lessons = Lesson::all()->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'name' => $lesson->name." - ".$lesson->grade,
            ];
        })->toArray();

        return response()->json([
            "lessons" => $lessons,
        ]);
    }
    public function create()
    {
        return view('organizationAdmin.exam.create');
    }
    public function createExam($id)
    {
        return view('organizationAdmin.exam.createExam', [
            'batch' => BatchExams::find($id),
            'lessons' => BatchExamLessons::where('batch_exam_id', $id)->get()->map(function ($lesson) {
                $l = $lesson->lesson();
                return $l;
            }),
        ]);
    }
    public function store(Request $request)
    {
        try{
            $exam = new BatchExams();
            $exam->name = $request->input('name');
            $exam->organization_id = auth()->guard('organization')->user()->id;
            $exam->save();
            $lessons = json_decode($request->input('lessons'), true);
            foreach ($lessons as $lesson) {
                $examLesson = new BatchExamLessons();
                $examLesson->batch_exam_id = $exam->id;
                $examLesson->lesson_id = $lesson['id'];
                $examLesson->saveOrFail();
            }
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Eklendi',"url"=>route('organizationAdmin.batchExam.index')]);
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function storeExam(Request $request,$id)
    {
        try{
            if(BatchExams::find($id)->organization_id != auth()->guard('organization')->user()->id){
                return response()->json(['status' => false, 'message' => 'Bu sınavı ekleyemezsiniz']);
            }
            $exam = new Exams();
            $exam->student_id = $request->input('student_id');
            $exam->batch_exam_id = $id;
            if($exam->saveOrFail()){
                $lessons = $request->input('lessons');
                foreach ($lessons as $key => $lesson) {
                    $result = new ExamResults();
                    $result->exam_id = $exam->id;
                    $result->lesson_id = $key;
                    $result->correct_answers = $lesson['correct_answers'];
                    $result->wrong_answers = $lesson['wrong_answers'];
                    $result->saveOrFail();
                }
                return response()->json(['status' => true, 'message' => 'Sınav Sonucu Eklendi',"url"=>route('organizationAdmin.batchExam.exam.index',$id)]);
            }else{
                return response()->json(['status' => false, 'message' => 'Kayıt Eklenemedi']);
            }
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        return view('organizationAdmin.exam.allExam', [
            'exams' => Exams::where('batch_exam_id', $id)->get(),
            'batch' => BatchExams::find($id),
            'organization' => Auth::guard('organization')->user(),
        ]);
    }
    public function showExam($id,$examId)
    {
        return response()->json([
            "html" => view('organizationAdmin.exam.showExam', [
                'exam' => Exams::where('id', $examId)->first(),
            ])->render(),
        ]);
    }

    public function showExamScore($id)
    {
        return response()->json([
            "html" => view('organizationAdmin.exam.showExam', [
                'exam' => Exams::where('id', $id)->first(),
            ])->render(),
        ]);
    }

    public function edit($id)
    {
        $exam = BatchExams::where('id', $id)->first();
        return view('organizationAdmin.exam.edit', [
            'batch' => $exam,
            'lessons' => BatchExamLessons::where('batch_exam_id', $id)->get()->map(function ($lesson) {
                $l = $lesson->lesson();
                return [
                    'id' => $lesson->lesson_id,
                    'value' => $l->name." - ".$l->grade,
                ];
            })->toArray(),
        ]);
    }
    public function editExam($id,$examId)
    {
        $exam = Exams::where('id', $examId)->first();
        return view('organizationAdmin.exam.editExam', [
            'exam' => $exam,
            'batch' => BatchExams::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $exam = BatchExams::find($id);
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $lessons = json_decode($request->input('lessons'), true);
            $lessonIds = [];
            foreach ($lessons as $lesson) {
                $lessonIds[] = $lesson['id'];
                $examLesson = BatchExamLessons::where('batch_exam_id', $id)->where('lesson_id', $lesson['id'])->first();
                if (!$examLesson) {
                    $examLesson = new BatchExamLessons();
                    $examLesson->batch_exam_id = $id;
                    $examLesson->lesson_id = $lesson['id'];
                    $examLesson->saveOrFail();
                }
            }
            BatchExamLessons::where('batch_exam_id', $id)->whereNotIn('lesson_id', $lessonIds)->delete();
            $exam->name = $request->input('name');
            $exam->save();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Güncellendi',"url"=>route('organizationAdmin.batchExam.show',$exam->id)]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateExam(Request $request, $id)
    {
        $exam = Exams::find($id);
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        $lessons = $request->input('lessons');
        foreach ($lessons as $key => $lesson) {
            ExamResults::where('exam_id', $id)->where('lesson_id', $key)->update(['correct_answers' => $lesson['correct_answers'], 'wrong_answers' => $lesson['wrong_answers']]);
        }
        try {
            $exam->save();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Güncellendi',"url"=>route('organizationAdmin.batchExam.exam.index',[
                "exam" => $exam->batch_exam_id,
                "examId" => $exam->id,
            ])]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroyExam($batchId,$id)
    {
        $exam = Exams::where('id',$id)->where('batch_exam_id',$batchId)->first();
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $exam->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        $exam = BatchExams::where('id',$id)->where('organization_id',auth()->guard('organization')->user()->id)->first();
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $exam->delete();
            Exams::where('batch_exam_id',$id)->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function downloadExampleScheme($id)
    {
        $headings = [
            [""],
            ["Öğrenci Adı"]
        ];
        $batch = BatchExams::find($id);
        if(!$batch){
            return redirect()->back();
        }
        $lessons = $batch->lessons()->get();
        $i=1;
        $merged = [];
        $corrects = [];
        $wrongs = [];
        foreach ($lessons as $lesson) {
            $columnKey = getColumnFromKey($i)."1:".getColumnFromKey($i+1)."1";
            $merged[] = $columnKey;
            $corrects[] = getColumnFromKey($i)."1:".getColumnFromKey($i)."100";
            $headings[0][$i] = $lesson->name;
            $headings[1][$i] = "Doğru Sayısı";

            $i++;
            $wrongs[] = getColumnFromKey($i)."1:".getColumnFromKey($i)."100";
            $headings[0][$i] = "";
            $headings[1][$i] = "Yanlış Sayısı";
            $i++;
        }
        return Excel::download(new ExampleExamSchemeExport($headings,$merged,$corrects,$wrongs), $batch->name.'-sinav-sablonu.xlsx');
    }

    public function importResults(Request $request,$id)
    {
        try {
            $batch = BatchExams::find($id);
            if(!$batch){
                return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
            }
            $excelFile = $request->file('file');
            $data = Excel::toArray((object)[], $excelFile);
            $helper = new ExamImportHelper($data,$id);
            $array = $helper->getArray();
            Cache::put('exam-import-cache-'.$id,$array,now()->addMinutes(10));
            return response()->json([
                "data" => $array,
                "status" => true,
                "storeUrl" => route('organizationAdmin.batchExam.storeImport',$id),
            ]);
        }catch (\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function storeImport(Request $request, $id)
    {
        try {
            $batch = BatchExams::find($id);
            if(!$batch){
                return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
            }
            if($data = Cache::get('exam-import-cache-'.$id)){
                $data = $data['data'];
                foreach($data as $exam){
                    $newExam = new Exams;
                    $newExam->batch_exam_id = $id;
                    $newExam->student_id = $exam['student_id'];
                    if($newExam->saveOrFail()){
                        $results = $exam['lessons'];
                        foreach($results as $result){
                            $newResult = new ExamResults;
                            $newResult->exam_id = $newExam->id;
                            $newResult->lesson_id = $result['id'];
                            $newResult->correct_answers = $result['correct_answers'];
                            $newResult->wrong_answers = $result['wrong_answers'];
                            $newResult->saveOrFail();
                        }
                    }
                }
                $batch->touch();
                Cache::forget('exam-import-cache-'.$id);
                return response()->json([
                    "status" => true,
                    "message" => "Sonuçlar İçeri Aktarıldı",
                    "url" => route('organizationAdmin.batchExam.show',$id),
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Önbellekte Kayıt Bulunamadı Lütfen Excel",
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
