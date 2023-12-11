<?php

namespace App\Http\Controllers\Student;

use App\Models\BatchExamLessons;
use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\ExamSchemes;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentExamController extends \Illuminate\Routing\Controller
{

    public function index()
    {
        return view('student.exam.all', [
            'exams' => Exams::where('student_id', auth('student')->user()->id)->get(),
        ]);
    }

    public function create($scheme = null)
    {
        if ($scheme) {
            $examScheme = ExamSchemes::find($scheme);
        }else{
            $examScheme = false;
        }
        $lessons = Lesson::where('grade', auth('student')->user()->grade)->get();
        return view('student.exam.create', [
            'lessons' => $lessons,
            'examScheme' => $examScheme
        ]);
    }

    public function show($id)
    {
        return response()->json([
            "html" => view('organizationAdmin.exam.showExam', [
                'exam' => Exams::where('id', $id)->first(),
            ])->render(),
        ]);
    }

    public function analysis($id,$id2 = false)
    {
        $id = $id2 ? $id2 : $id;
        return response()->json([
            "html" => view('organizationAdmin.exam.showExamAnalysis', [
                'exam' => Exams::where('id', $id)->first(),
            ])->render(),
        ]);
    }
    public function downloadPdf($id)
    {
        $exam = Exams::where('id', $id)->first();
        $pdf = PDF::loadView('student.exam.showExamPdf', [
            'exam' => $exam,
        ])->setOptions(['isPhpEnabled' => true]);
        return $pdf->download($exam->student()->name . '-deneme-sonuc.pdf');
    }
    public function edit($id)
    {
        $exam = Exams::where('id', $id)->first();
        return view('student.exam.edit', [
            'exam' => $exam,
        ]);
    }
    public function update(Request $request, $id)
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
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Güncellendi',"url"=>route('student.exam.index',[
                "exam" => $exam->batch_exam_id,
                "examId" => $exam->id,
            ])]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function store()
    {
        try{
            $exam_1 = new Exams();
            $exam_1->student_id = auth('student')->user()->id;
            $exam_1->saveOrFail();
            foreach(request('data') as $exam){
                $res = new ExamResults();
                $res->exam_id = $exam_1->id;
                $res->lesson_id = $exam["lesson_id"];
                $res->correct_answers = $exam["correct_answers"];
                $res->wrong_answers = $exam["wrong_answers"];
                $res->saveOrFail();
            }
            return response()->json([
                "status" => true,
                "message" => "Sınav başarıyla kaydedildi.",
                "url" => route('student.exam.index')
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                    "message" => $e->getMessage()
                ]);
        }
    }

    public function destroy($id)
    {
        try{
            $exam = Exams::where('id', $id)->first();
            $exam->delete();
            return response()->json([
                "status" => true,
                "message" => "Sınav başarıyla silindi."
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
