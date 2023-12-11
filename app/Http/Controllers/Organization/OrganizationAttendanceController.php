<?php

namespace App\Http\Controllers\Organization;

use App\Models\ClassAttendance;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class OrganizationAttendanceController extends Controller
{
    public function downloadAttendanceToday()
    {
        $students = Student::where('organization_id', auth('organization')->user()->id)->pluck('id')->toArray();
        $attendances = ClassAttendance::whereIn('student_id', $students)->where('attendance_status',0)->whereDate('created_at', Carbon::today())->orderBy('created_at','desc')->get();
        $title = "Bugünün Devamsızlık Listesi";
        $pdf = PDF::loadView('organizationAdmin.attendancePdf', compact('attendances','title'))->setOptions(['isPhpEnabled' => true]);
        $fileName = date('d-m-Y_H-i_').'devamsızlık_listesi-'.Str::random(6).'.pdf';
        return $pdf->download($fileName);
    }

    public function downloadAttendanceWeek()
    {
        $students = Student::where('organization_id', auth('organization')->user()->id)->pluck('id')->toArray();
        $attendances = ClassAttendance::whereIn('student_id', $students)->where('attendance_status',0)->whereBetween('created_at',[\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->get();
        $title = "Bu Haftanın Devamsızlık Listesi";
        $pdf = PDF::loadView('organizationAdmin.attendancePdf', compact('attendances','title'))->setOptions(['isPhpEnabled' => true]);
        $fileName = date('d-m-Y_H-i_').'devamsızlık_listesi-'.Str::random(6).'.pdf';
        return $pdf->download($fileName);
    }

    public function downloadAttendanceMonth()
    {
        $students = Student::where('organization_id', auth('organization')->user()->id)->pluck('id')->toArray();
        $attendances = ClassAttendance::whereIn('student_id', $students)->where('attendance_status',0)->whereBetween('created_at',[\Carbon\Carbon::now()->startOfMonth(),\Carbon\Carbon::now()->endOfMonth()])->orderBy('created_at','desc')->get();
        $title = "Bu Ayın Devamsızlık Listesi";
        $pdf = PDF::loadView('organizationAdmin.attendancePdf', compact('attendances','title'))->setOptions(['isPhpEnabled' => true]);
        $fileName = date('d-m-Y_H-i_').'devamsızlık_listesi-'.Str::random(6).'.pdf';
        return $pdf->download($fileName);

    }
}
