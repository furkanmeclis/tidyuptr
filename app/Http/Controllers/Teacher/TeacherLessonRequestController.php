<?php

namespace App\Http\Controllers\Teacher;

use App\Models\LessonRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeacherLessonRequestController extends Controller
{
    public function index()
    {
        $requests = LessonRequests::where('teacher_id', auth('teacher')->user()->id)->get();
        return view('teacher.lesson_request.all')->with('requests', $requests);
    }

    public function changeUrl(Request $request)
    {
        try{
            $teacher = auth('teacher')->user();
            $teacher->live_lesson_url = $request->input('url');
            if($teacher->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'Canlı Ders Linki Güncellendi'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Canlı Ders Linki Güncellenemedi'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function accept(Request $request,$id)
    {
        try {
            if($lessonReq = LessonRequests::find($id)){
                $lessonReq->status = 1;
                if($lessonReq->save()) {
                    $lessonReq->touch();
                    return response()->json([
                        'status' => true,
                        'message' => 'Ders isteği kabul edildi'
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Ders isteği kabul edilemedi'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Ders isteği bulunamadı'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function reject(Request $request,$id)
    {
        try {
            if($lessonReq = LessonRequests::find($id)){
                $lessonReq->status = 0;
                if($lessonReq->save()) {
                    $lessonReq->touch();
                    return response()->json([
                        'status' => true,
                        'message' => 'Ders isteği reddedildi'
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Ders isteği ret edilemedi'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Ders isteği bulunamadı'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
