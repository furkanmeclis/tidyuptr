<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\Organization;
use App\Models\OrganizationTeacher;
use App\Models\StudentTeacher;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SystemAdminTeacherController extends Controller
{
    public $getTeachersHelper = false;
    public function index()
    {
        return view('systemAdmin.teacher.all')->with('teachers', Teacher::get());
    }
    private function getTeachersHelper($item, $key) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'selected' => $this->getTeachersHelper == $item->id ? true : false,
        ];
    }
    public function getTeachers($id = false)
    {

        if ($id) {
            $t = StudentTeacher::where('student_id', $id)->first();
            if ($t) {
                $this->getTeachersHelper = $t->teacher_id;
            }
        }
        $responseArr = array();
        $teachers = Teacher::select('id', 'name')->get()->map(function ($item,$key){
            return $this->getTeachersHelper($item,$key);
        })->toArray();
        if($this->getTeachersHelper == false){
            $teachers[0]['selected'] = true;
        }
        return response()->json([
            "data" => $teachers,
        ]);
    }

    public function create()
    {
        return view('systemAdmin.teacher.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:teachers'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $teacher = new Teacher;
            $teacher->name =  $request->input('name');
            $teacher->email =  $request->input('email');
            $teacher->max_students =  $request->input('max_students');
            $teacher->password = Hash::make($request->input('password'));
            if ($request->input('phone')) {
                $teacher->phone = $request->input('phone');
            }
            if ($teacher->saveOrFail()) {
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('systemAdmin.teacher.show', ["teacher" => $teacher->id])
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ekleme İşlemi Başarısız"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $teacher = Teacher::find($id);
            if ($teacher) {
                $password = $request->input('password');
                $teacher->password = Hash::make($password);
                if ($teacher->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.teacher.show', ["teacher" => $teacher->id])
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Güncelleme İşlemi Başarısız"
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Kayıt Bulunamadı"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
    public function show(Teacher $teacher)
    {
        return view('systemAdmin.teacher.show')->with('teacher', $teacher);
    }
    public function showOrganization($id)
    {
        return view('systemAdmin.teacher.showOrganization')
            ->with('teacher', Teacher::find($id))
            ->with('organizations', OrganizationTeacher::where('teacher_id', $id)->get());
    }
    public function showStudent($id)
    {
        return view('systemAdmin.teacher.showStudent')
            ->with('teacher', Teacher::find($id))
            ->with('students', StudentTeacher::where('teacher_id', $id)->get());
    }
    public function edit($id)
    {
        $organizations = Organization::select('name', 'id')->get();
        $workingOrganizations = OrganizationTeacher::where('teacher_id', $id)->get();
        foreach ($organizations as $organization) {
            $organization->selected = $workingOrganizations->contains('organization_id', $organization->id);
        }
        return view('systemAdmin.teacher.edit')->with('teacher', Teacher::find($id))->with('organizations', $organizations);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('teachers')->ignore($id)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }

        $teacher = Teacher::find($id);
        if ($teacher) {
            try {
                $teacher->name =  $request->input('name');
                $teacher->email =  $request->input('email');
                $teacher->max_students =  $request->input('max_students');
                if ($request->input('phone') != "") {
                    $teacher->phone = $request->input('phone');
                }
                if ($teacher->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.teacher.show', ["teacher" => $teacher->id])
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Güncelleme İşlemi Başarısız"
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json(["status" => false, "message" => $e->getMessage()]);
            }
        } else {
            return response()->json(["status" => false, "message" => "Kayıt Bulunamadı"]);
        }
    }


    public function destroy($id)
    {
        $record = Teacher::find($id);
        if (!$record) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $record->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateOrganizations(Request $request, $teacherId)
    {
        $record = Teacher::find($teacherId);
        if (!$record) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $organizationIds = $request->input('organizationsId');
            OrganizationTeacher::where('teacher_id', $teacherId)->delete();
            $insertData = [];
            foreach ($organizationIds as $id) {
                $tempData = [
                    'organization_id' => $id,
                    'teacher_id' => $teacherId,
                ];
                array_push($insertData, $tempData);
            }
            if (DB::table(OrganizationTeacher::getTableName())->insert($insertData)) {
                return response()->json(['status' => true, 'message' => 'Güncelleme İşlemi Başarılı', "url" => route('systemAdmin.teacher.show', $teacherId)]);
            } else {
                return response()->json(['status' => false, 'message' => 'Güncelleme İşlemi Başarısız']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
