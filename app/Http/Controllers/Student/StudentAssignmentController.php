<?php

namespace App\Http\Controllers\Student;
use App\Models\AssignmentResponses;
use \App\Models\Assignments;
use App\Models\AssignmentStudents;
use App\Models\StudentTeacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $assignment_ids = AssignmentStudents::where('student_id', auth('student')->user()->id)->pluck('assignment_id');
        $assignments = Assignments::whereIn('id', $assignment_ids)->get();
        return view('student.assignment.all')->with('assignments', $assignments);
    }

    public function store(Request $request,$id)
    {
        try {
            $student = auth('student')->user();
            if(AssignmentStudents::where('assignment_id',$id)->where('student_id',$student->id)->count() == 1){
                $as_res = new AssignmentResponses();
                $as_res->assignment_id = $id;
                $as_res->student_id = $student->id;
                $as_res->response = $request->input('content');
                $as_res->file = null;
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $path = $file->store('uploads');
                    $as_res->file = $path;
                }
                if ($as_res->save()) {
                    return response()->json(['status' => true, 'message' => 'Ödev Gönderildi']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Ödev Gönderilemedi']);
                }
            }else{
                return response()->json(['status' => false, 'message' => 'Bu Ödevden Sorumlu Değilsiniz']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        $assignment = Assignments::find($id);
        return view('student.assignment.show')->with('assignment', $assignment)
            ->with('response',AssignmentResponses::where('assignment_id',$id)
            ->where('student_id',auth('student')->user()->id)
                ->first());
    }
    public function showResponse($assignmenId,$id)
    {
        return response()->json([
            "html" => view('teacher.assignment.response', [
                'response' => AssignmentResponses::where('id',$id)->where('assignment_id',$assignmenId)->first(),
            ])->render(),
        ]);
    }

    public function getStudents(){
        $studentIds = StudentTeacher::where('teacher_id',auth('teacher')->user()->id)->pluck('student_id');
        $students = \App\Models\Student::whereIn('id',$studentIds)->get()->map(function ($item,$key){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'selected' => false,
            ];
        })->toArray();
        $students[0]['selected'] = true;
        return response()->json([
            "status" => true,
            "data" =>  $students,
        ]);

    }
    public function destroy($id)
    {
        if ($assignment = Assignments::find($id)) {
            if ($assignment->teacher_id == auth('teacher')->user()->id) {
                //file delete
                $file_urls = [];
                if ($assignment->file != null) {
                    $file_urls[] = $assignment->file;
                }
                foreach ($assignment->responses() as $response) {
                    if ($response->file != null) {
                        $file_urls[] = $response->file;
                    }
                }
                if ($assignment->delete()) {
                    foreach ($file_urls as $file_url) {
                        Storage::delete($file_url);
                    }
                    return response()->json(['status' => true, 'message' => 'Ödev Silindi']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Ödev Silinemedi']);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Yalnızca Kendi Ödevlerinizi Silebilirsiniz']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
    }
}
