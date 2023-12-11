<?php

namespace App\Http\Controllers\Student;

use App\Models\Exams;
use Illuminate\Routing\Controller;

class StudentStatsController extends Controller
{
    public function getExamResults()
    {
        try{
            $student = auth('student')->user();
            $exams = Exams::where('student_id', $student->id)->get();
            $printDataBatch = [];
            $printData = [];
            foreach ($exams as $exam) {
                if($exam->batch_exam_id != null){
                    $printDataBatch[] = [
                        "date" => $exam->updated_at->format('d.m.Y'),
                        "total" => $exam->score()->total,
                    ];
                }else{
                    $printData[] = [
                        "date" => $exam->updated_at->format('d.m.Y'),
                        "total" => $exam->score()->total,
                    ];
                }
            }
            return response()->json([
                "status" => true,
                "count" => count($exams),
                "batch" => $printDataBatch,
                "normal" => $printData,
            ]);
        }catch (\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
