<?php

namespace App\Http\Controllers\Organization;

use App\Models\BatchExamLessons;
use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
