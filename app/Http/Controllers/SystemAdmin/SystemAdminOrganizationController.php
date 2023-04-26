<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\BatchExams;
use App\Models\Exams;
use App\Models\Organization;
use App\Models\OrganizationLicense;
use App\Models\OrganizationTeacher;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SystemAdminOrganizationController extends Controller
{
    public $getOrganizationsHelper = false;
    public function index()
    {
        return view('systemAdmin.organization.all')->with('organizations', Organization::get());
    }
    private function getOrganizationsHelper($item, $key) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'selected' => $this->getOrganizationsHelper == $item->id ? true : false,
        ];
    }
    public function getOrganizations(Request $request,$id = false)
    {
        if($id != false){
            $this->getOrganizationsHelper = $id;
        }
        $organizations = Organization::select('id', 'name')->get();
        $organizations = $organizations->map(function ($item,$key){
            return $this->getOrganizationsHelper($item,$key);
        })->prepend([
            'id' => 0,
            'name' => 'Bireysel Öğrenci',
            'selected' => (($id == false) ? true : ($this->getOrganizationsHelper == 0 ? true : false)),
        ])->toArray();
        return response()->json([
            "status" => true,
            "data" =>  $organizations,
        ]);
    }

    public function create()
    {
        return view('systemAdmin.organization.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:organizations'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $email = $request->input('email');
            $name = $request->input('name');
            $password = $request->input('password');
            $licenseExpireDate = Carbon::createFromFormat('d/m/Y', $request->input('licenseExpireDate'))->format('Y-m-d');
            $licenseStartDate =  Carbon::createFromFormat('d/m/Y', $request->input('licenseStartDate'))->format('Y-m-d');
            $organization = new Organization;
            $organization->name = $name;
            $organization->email = $email;
            $organization->organization_id = Str::random(4);
            $organization->password = Hash::make($password);
            if ($request->input('phone')) {
                $organization->phone = $request->input('phone');
            }
            if ($request->input('address')) {
                $organization->address = $request->input('address');
            }
            $organization->active = true;
            if ($organization->saveOrFail()) {
                OrganizationLicense::where('organization_id', $organization->id)->update(["active" => false]);
                $subscription = new OrganizationLicense;
                $subscription->organization_id = $organization->id;
                $subscription->start_date = $licenseStartDate;
                $subscription->end_date = $licenseExpireDate;
                $subscription->active = true;
                if ($subscription->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Ekleme İşlemi Başarılı",
                        "url" => route('systemAdmin.organization.show', ["organization" => $organization->id])
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Ekleme İşlemi Başarısız"
                    ]);
                }
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

    public function show($id)
    {
        return view('systemAdmin.organization.show')->with('organization', Organization::find($id));
    }
    public function showTeacher($id)
    {
        return view('systemAdmin.organization.showTeacher')
            ->with('organization', Organization::find($id))
            ->with('teachers', OrganizationTeacher::where('organization_id', $id)->get());
    }

    public function showStudent($id)
    {
        return view('systemAdmin.organization.showStudent')
            ->with('organization', Organization::find($id))
            ->with('students', Student::where('organization_id', $id)->get());
    }

    public function showExams($id)
    {
        return view('systemAdmin.organization.showExams')
            ->with('organization', Organization::find($id))
            ->with('exams', BatchExams::where('organization_id', $id)->get());
    }

    public function showExam($id,$batchId)
    {
        return view('systemAdmin.organization.showExam')
            ->with('organization', Organization::find($id))
            ->with('exams', Exams::where('batch_exam_id', $batchId)->get())
            ->with('batch', BatchExams::find($batchId));
    }
    public function edit($id)
    {
        return view('systemAdmin.organization.edit')->with('organization', Organization::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('organizations')->ignore($id)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $organization = Organization::find($id);
            if ($organization) {
                $email = $request->input('email');
                $name = $request->input('name');
                $organization->name = $name;
                $organization->email = $email;
                if ($request->input('phone')) {
                    $organization->phone = $request->input('phone');
                }
                if ($request->input('address')) {
                    $organization->address = $request->input('address');
                }
                if ($organization->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.organization.show', ["organization" => $organization->id])
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

    public function updatePassword(Request $request, $id)
    {
        try {
            $organization = Organization::find($id);
            if ($organization) {
                $password = $request->input('password');
                $organization->password = Hash::make($password);
                if ($organization->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.organization.show', ["organization" => $organization->id])
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
    public function destroy($id)
    {
        $record = Organization::find($id);
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
}
