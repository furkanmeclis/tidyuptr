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
        $assignments = Assignments::where('teacher_id', auth('teacher')->user()->id)->get();
        return view('teacher.assignment.all')->with('assignments', $assignments);
    }
    public function create()
    {
        return view('teacher.assignment.create');
    }
    public function store(Request $request)
    {
        try {
            $assignment = new Assignments();
            $assignment->teacher_id = auth('teacher')->user()->id;
            $assignment->title = $request->input('title');
            $assignment->description = $request->input('description');
            $assignment->due_date = Carbon::createFromFormat('d/m/Y', $request->input('due_date'))->format('Y-m-d');

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads');
                $assignment->file = $path;
            }

            if ($assignment->save()) {
                $students = [];
                foreach($request->input('student_ids') as $stid){
                    $students[] = [
                        'student_id' => $stid,
                        'assignment_id' => $assignment->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                AssignmentStudents::insert($students);
                return response()->json(['status' => true, 'message' => 'Ödev Eklendi',"url" => route('teacher.assignment.show',$assignment->id)]);
            } else {
                return response()->json(['status' => false, 'message' => 'Ödev Eklenemedi']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        $assignment = Assignments::find($id);
        return view('teacher.assignment.show')->with('assignment', $assignment);
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
