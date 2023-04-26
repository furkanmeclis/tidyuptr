<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use Illuminate\Http\Request;

class SystemAdminExamController extends \Illuminate\Routing\Controller
{

    public function index()
    {
        return view('systemAdmin.exam.all', [
            'exams' => Exams::all(),
        ]);
    }
    public function show($id)
    {
        return response()->json([
            "html" => view('systemAdmin.exam.show', [
                'exam' => Exams::where('id', $id)->first(),
            ])->render(),
        ]);
    }

    public function edit($id)
    {
        $exam = Exams::where('id', $id)->first();
        return view('systemAdmin.exam.edit', [
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
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Güncellendi',"url"=>route('systemAdmin.exam.index')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $exam = Exams::find($id);
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
    public function destroyBatch($id)
    {
        $exam = BatchExams::find($id);
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
