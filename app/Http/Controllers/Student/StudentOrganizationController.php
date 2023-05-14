<?php

namespace App\Http\Controllers\Student;

use App\Models\BatchExams;
use App\Models\Exams;
use App\Models\Organization;
use App\Models\OrganizationLicense;
use App\Models\OrganizationTeacher;
use App\Models\Student;
use App\Models\StudentTeacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentOrganizationController extends Controller
{

    public function index()
    {
        $organizationIds = OrganizationTeacher::where('teacher_id', auth()->guard('teacher')->user()->id)->get()->map(function ($organization) {
            return $organization->organization_id;
        });
        $organizations = Organization::whereIn('id', $organizationIds)->get();
        return view('teacher.organization.all')->with('organizations', $organizations);
    }

    public function showStudents($id)
    {
        $studentIds = StudentTeacher::where('teacher_id', auth()->guard('teacher')->user()->id)->get()->map(function ($student) {
            return $student->student_id;
        });
        $students = Student::where('organization_id',$id)->whereIn('id', $studentIds)->get();
        return view('teacher.organization.students')->with('students', $students)->with('organization', Organization::find($id));
    }
}
