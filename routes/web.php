<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Organization\OrganizationAttendanceController;
use App\Http\Controllers\Organization\OrganizationAuthController;
use App\Http\Controllers\Organization\OrganizationClassController;
use App\Http\Controllers\Organization\OrganizationExamController;
use App\Http\Controllers\Organization\OrganizationExamSchemeController;
use App\Http\Controllers\Organization\OrganizationOpticController;
use App\Http\Controllers\Organization\OrganizationStatsController;
use App\Http\Controllers\Organization\OrganizationStudentController;
use App\Http\Controllers\Organization\OrganizationTeacherController;
use App\Http\Controllers\Student\StudentAIController;
use App\Http\Controllers\Student\StudentAnnouncementController;
use App\Http\Controllers\Student\StudentAssignmentController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentClassController;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentMentorFollowUpController;
use App\Http\Controllers\Student\StudentPreferenceRobotController;
use App\Http\Controllers\Student\StudentQuestionAnswerController;
use App\Http\Controllers\Student\StudentScheduleController;
use App\Http\Controllers\Student\StudentStatsController;
use App\Http\Controllers\SystemAdmin\SystemAdminAuthController;
use App\Http\Controllers\SystemAdmin\SystemAdminExamController;
use App\Http\Controllers\SystemAdmin\SystemAdminLessonController;
use App\Http\Controllers\SystemAdmin\SystemAdminLicenseController;
use App\Http\Controllers\SystemAdmin\SystemAdminOpticController;
use App\Http\Controllers\SystemAdmin\SystemAdminOrganizationController;
use App\Http\Controllers\SystemAdmin\SystemAdminStudentController;
use App\Http\Controllers\SystemAdmin\SystemAdminTeacherController;
use App\Http\Controllers\Teacher\TeacherAnnouncementController;
use App\Http\Controllers\Teacher\TeacherAssignmentController;
use App\Http\Controllers\Teacher\TeacherAuthController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherLessonRequestController;
use App\Http\Controllers\Teacher\TeacherMentorFollowUpController;
use App\Http\Controllers\Teacher\TeacherOrganizationController;
use App\Http\Controllers\Teacher\TeacherQuestionAnswerController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use Illuminate\Support\Facades\Route;
use App\Services\ExamImportHelper;
/*
|--------------------------------------------------------------------------
*/
Route::view('/', 'index')->name('index');






Route::get('excel', function (){
    $data = Maatwebsite\Excel\Facades\Excel::toArray([],storage_path('app/public/uploads/excel.xlsx'));
    $helper = new ExamImportHelper($data);
    $print = $helper->getArray();
    return response()->json($print);
});
/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Sistem Yöneticisi Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/

Route::prefix('system-admin')->name('systemAdmin.')->group(function () {
    Route::get('login', [SystemAdminAuthController::class, 'loginView'])->name('login');
    Route::post('login', [SystemAdminAuthController::class, 'loginStore']);
    Route::middleware('auth.admin')->group(function () {
        Route::get('/', [SystemAdminOrganizationController::class, 'index'])->name('index');
        Route::get('dersprogram', [HomeController::class, 'index']);
        Route::get('/settings',[HomeController::class,"systemAdminSettings"])->name("settings");
        Route::get('/settings',[HomeController::class,"systemAdminSettingsStore"])->name("settings");
        //Kurumların Yönetim Linkleri
        Route::prefix('organizations')->name('organization.')->group(function () {
            Route::get('/', [SystemAdminOrganizationController::class, 'index'])->name('index');
            Route::post('get-organizations/{student_id?}', [SystemAdminOrganizationController::class, 'getOrganizations'])->name('getOrganizations');
            Route::get('create', [SystemAdminOrganizationController::class, 'create'])->name('create');
            Route::post('create', [SystemAdminOrganizationController::class, 'store'])->name('store');
            Route::get('{organization}', [SystemAdminOrganizationController::class, 'show'])->name('show');
            Route::get('{organization}/teacher', [SystemAdminOrganizationController::class, 'showTeacher'])->name('showTeacher');
            Route::get('{organization}/student', [SystemAdminOrganizationController::class, 'showStudent'])->name('showStudent');
            Route::get('{organization}/exam', [SystemAdminOrganizationController::class, 'showExams'])->name('showExams');
            Route::get('{organization}/exam/{exam}', [SystemAdminOrganizationController::class, 'showExam'])->name('showExam');
            Route::get('{organization}/edit', [SystemAdminOrganizationController::class, 'edit'])->name('edit');
            Route::put('{organization}', [SystemAdminOrganizationController::class, 'update'])->name('update');
            Route::put('{organization}/update-password', [SystemAdminOrganizationController::class, 'updatePassword'])->name('updatePassword');
            Route::delete('{organization}', [SystemAdminOrganizationController::class, 'destroy'])->name('destroy');
            //Lisansların Yönetim Linkleri
            Route::prefix('{organization}/license')->name('license.')->group(function () {
                Route::get('/', [SystemAdminLicenseController::class, 'index'])->name('index');
                Route::get('create', [SystemAdminLicenseController::class, 'create'])->name('create');
                Route::post('create', [SystemAdminLicenseController::class, 'store'])->name('store');
                Route::put('{license}/active', [SystemAdminLicenseController::class, 'activeLicense'])->name('activeLicense');
                Route::get('{license}/edit', [SystemAdminLicenseController::class, 'edit'])->name('edit');
                Route::put('{license}', [SystemAdminLicenseController::class, 'update'])->name('update');
                Route::delete('{license}', [SystemAdminLicenseController::class, 'destroy'])->name('destroy');
            });

        });
        //Öğretmen Yönetim Linkleri
        Route::prefix('teachers')->name('teacher.')->group(function () {
            Route::get('/', [SystemAdminTeacherController::class, 'index'])->name('index');
            Route::post('get-teachers/{student_id?}', [SystemAdminTeacherController::class, 'getTeachers'])->name('getTeachers');
            Route::get('create', [SystemAdminTeacherController::class, 'create'])->name('create');
            Route::post('create', [SystemAdminTeacherController::class, 'store'])->name('store');
            Route::get('{teacher}', [SystemAdminTeacherController::class, 'show'])->name('show');
            Route::get('{teacher}/organization', [SystemAdminTeacherController::class, 'showOrganization'])->name('showOrganization');
            Route::get('{teacher}/student', [SystemAdminTeacherController::class, 'showStudent'])->name('showStudent');
            Route::get('{teacher}/edit', [SystemAdminTeacherController::class, 'edit'])->name('edit');
            Route::put('{teacher}', [SystemAdminTeacherController::class, 'update'])->name('update');
            Route::put('{teacher}/update-organizations', [SystemAdminTeacherController::class, 'updateOrganizations'])->name('updateOrganizations');
            Route::put('{teacher}/update-password', [SystemAdminTeacherController::class, 'updatePassword'])->name('updatePassword');
            Route::delete('{teacher}', [SystemAdminTeacherController::class, 'destroy'])->name('destroy');
        });
        //Derslerin Yönetim Linkleri
        Route::prefix('lessons')->name('lesson.')->group(function () {
            Route::get('/', [SystemAdminLessonController::class, 'index'])->name('index');
            Route::get('create', [SystemAdminLessonController::class, 'create'])->name('create');
            Route::post('create', [SystemAdminLessonController::class, 'store'])->name('store');
            Route::get('{lesson}', [SystemAdminLessonController::class, 'show'])->name('show');
            Route::get('{lesson}/edit', [SystemAdminLessonController::class, 'edit'])->name('edit');
            Route::put('{lesson}', [SystemAdminLessonController::class, 'update'])->name('update');
            Route::delete('{lesson}', [SystemAdminLessonController::class, 'destroy'])->name('destroy');
            //Konu Başlıklarının Yönetim Linkleri
            Route::put('{lesson}/topic/{topic}', [SystemAdminLessonController::class, 'updateTopic'])->name('updateTopic');
            Route::post('{lesson}/topic', [SystemAdminLessonController::class, 'storeTopic'])->name('storeTopic');
            Route::delete('{topic}/topic', [SystemAdminLessonController::class, 'destroyTopic'])->name('destroyTopic');
        });
        Route::prefix('optic-papers')->name('optic.')->group(function(){
            Route::get('/', [SystemAdminOpticController::class, 'index'])->name('index');
            Route::get('create', [SystemAdminOpticController::class, 'create'])->name('create');
            Route::post('create', [SystemAdminOpticController::class, 'store'])->name('store');
            Route::post('upload-fmt', [SystemAdminOpticController::class, 'uploadFmt'])->name('uploadFmt');
            Route::get('{optic}/edit', [SystemAdminOpticController::class, 'edit'])->name('edit');
            Route::get('{optic}/download-fmt', [SystemAdminOpticController::class, 'downloadFmt'])->name('downloadFmt');
            Route::put('{optic}', [SystemAdminOpticController::class, 'update'])->name('update');
            Route::delete('{optic}', [SystemAdminOpticController::class, 'destroy'])->name('destroy');
        });
        //Öğrenci Yönetim Linkleri
        Route::prefix('students')->name('student.')->group(function () {
            Route::get('/', [SystemAdminStudentController::class, 'index'])->name('index');
            Route::get('create', [SystemAdminStudentController::class, 'create'])->name('create');
            Route::post('create', [SystemAdminStudentController::class, 'store'])->name('store');
            Route::get('{student}', [SystemAdminStudentController::class, 'show'])->name('show');
            Route::get('{student}/exams', [SystemAdminStudentController::class, 'exams'])->name('exam');
            Route::get('{student}/edit', [SystemAdminStudentController::class, 'edit'])->name('edit');
            Route::put('{student}', [SystemAdminStudentController::class, 'update'])->name('update');
            Route::put('{student}/update-teacher', [SystemAdminStudentController::class, 'updateTeacher'])->name('updateTeacher');
            Route::put('{student}/update-organization', [SystemAdminStudentController::class, 'updateOrganization'])->name('updateOrganization');

            Route::put('{student}/update-password', [SystemAdminStudentController::class, 'updatePassword'])->name('updatePassword');
            Route::delete('{student}', [SystemAdminStudentController::class, 'destroy'])->name('destroy');
        });
        //Sınav Yönetim Linkleri
        Route::prefix('exams')->name('exam.')->group(function () {
            Route::get('/', [SystemAdminExamController::class, 'index'])->name('index');
            Route::post('{exam}', [SystemAdminExamController::class, 'show'])->name('show');
            Route::get('{exam}/download', [StudentExamController::class, 'downloadPdf'])->name('downloadPdf');
            Route::get('{exam}/edit', [SystemAdminExamController::class, 'edit'])->name('edit');
            Route::put('{exam}', [SystemAdminExamController::class, 'update'])->name('update');
            Route::delete('{exam}', [SystemAdminExamController::class, 'destroy'])->name('destroy');
            Route::delete('batch/{exam}', [SystemAdminExamController::class, 'destroyBatch'])->name('destroyBatch');
        });
        Route::get('logout', [SystemAdminAuthController::class, 'logout'])->name('logout');
    });
});
/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Sistem Yöneticisi Routeları Bitiş
|--------------------------------------------------------------------------
|
|--------------------------------------------------------------------------
| Eğitim Koçluğu Kurum Yöneticisi Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/
Route::prefix('organization')->name('organizationAdmin.')->group(function () {
    Route::get('login', [OrganizationAuthController::class, 'loginView'])->name('login');
    Route::post('login', [OrganizationAuthController::class, 'loginStore']);
    Route::middleware('auth.organization')->group(function () {
        Route::get('/settings',[HomeController::class,"organizationAdminSettings"])->name("settings");
        Route::post('/settings',[HomeController::class,"organizationAdminSettingsStore"]);
        Route::get('/', [HomeController::class, 'organizationIndex'])->name('index');
        Route::get('analyzes', [HomeController::class, 'organizationAnalyzes'])->name('analyzes');
        Route::post('analyzes', [HomeController::class, 'getStatsOrganization'])->name('getStatsOrganization');
        Route::post('analyzes-student/{student?}', [HomeController::class, 'getStatsStudent'])->name('getStatsStudent');
        //İstatistikler
        Route::prefix('stats')->name('stats.')->group(function (){
            Route::post('get-stats', [OrganizationStatsController::class, 'getStats'])->name('getStats');
        });
        //Öğretmen Yönetim Linkleri
        Route::prefix('teachers')->name('teacher.')->group(function () {
            Route::get('/', [OrganizationTeacherController::class, 'index'])->name('index');
            Route::post('get-teachers/{student_id?}', [OrganizationTeacherController::class, 'getTeachers'])->name('getTeachers');
            Route::get('create', [OrganizationTeacherController::class, 'create'])->name('create');
            Route::post('create', [OrganizationTeacherController::class, 'store'])->name('store');
            Route::get('{teacher}', [OrganizationTeacherController::class, 'show'])->name('show');
            Route::get('{teacher}/student', [OrganizationTeacherController::class, 'showStudent'])->name('showStudent');
            Route::delete('{teacher}/end-registration', [OrganizationTeacherController::class, 'endRegistration'])->name('endRegistration');
        });

        //Öğrenci Yönetim Linkleri
        Route::prefix('students')->name('student.')->group(function () {
            Route::get('/', [OrganizationStudentController::class, 'index'])->name('index');
            Route::get('create', [OrganizationStudentController::class, 'create'])->name('create');
            Route::get('download/{type?}', [OrganizationStudentController::class, 'download'])->name('download');
            Route::post('import-students', [OrganizationStudentController::class, 'importStudents'])->name('importStudents');
            Route::post('create', [OrganizationStudentController::class, 'store'])->name('store');
            Route::post('get-students', [OrganizationStudentController::class, 'getStudents'])->name('getStudents');
            Route::get('{student}', [OrganizationStudentController::class, 'show'])->name('show');
            Route::post('{student}/save-parents', [OrganizationStudentController::class, 'saveParents'])->name('saveParents');
            Route::prefix('{student}/exams')->name('exam.')->group(function () {
                Route::get('/', [OrganizationStudentController::class, 'getStudentExams'])->name('index');
                Route::get('{exam}/download', [OrganizationStudentController::class, 'downloadPdf'])->name('downloadPdf');
                Route::get('{exam}/edit', [OrganizationStudentController::class, 'editExam'])->name('edit');
                Route::post('{exam}/analysis', [StudentExamController::class, 'analysis'])->name('analysis');
                Route::post('{exam}', [OrganizationStudentController::class, 'showStudentExam'])->name('show');
                Route::put('{exam}/update', [OrganizationStudentController::class, 'updateExam'])->name('update');
                Route::delete('{exam}/destroy', [OrganizationStudentController::class, 'destroyExam'])->name('destroy');
            });
            Route::get('{student}/edit', [OrganizationStudentController::class, 'edit'])->name('edit');
            Route::put('{student}', [OrganizationStudentController::class, 'update'])->name('update');
            Route::put('{student}/update-teacher', [OrganizationStudentController::class, 'updateTeacher'])->name('updateTeacher');
            Route::put('{student}/update-password', [OrganizationStudentController::class, 'updatePassword'])->name('updatePassword');
            Route::delete('{student}', [OrganizationStudentController::class, 'destroy'])->name('destroy');

        });
        //Sınav Yönetim Linkleri
        Route::prefix('batch-exams')->name('batchExam.')->group(function () {
            Route::get('/', [OrganizationExamController::class, 'index'])->name('index');
            Route::get('create', [OrganizationExamController::class, 'create'])->name('create');
            Route::post('create', [OrganizationExamController::class, 'store'])->name('store');
            Route::get('downloadAnswerScheme', [OrganizationExamController::class, 'downloadAnswerScheme'])->name('downloadAnswerScheme');
            Route::post('lessons', [OrganizationExamController::class, 'getLessons'])->name('getLessons');
            Route::get('{exam}', [OrganizationExamController::class, 'show'])->name('show');
            Route::get('{exam}/edit', [OrganizationExamController::class, 'edit'])->name('edit');
            Route::get('{exam}/download', [OrganizationExamController::class, 'download'])->name('download');
            Route::post('{exam}/import-results', [OrganizationExamController::class, 'importResults'])->name('importResults');
            Route::post('{exam}/upload-answers', [OrganizationExamController::class, 'uploadAnswers'])->name('uploadAnswers');
            Route::post('{exam}/read-for-optic', [OrganizationExamController::class, 'readExam'])->name('readExam');
            Route::post('{exam}/confirm-import-results', [OrganizationExamController::class, 'storeImport'])->name('storeImport');
            Route::get('{exam}/download-example-scheme', [OrganizationExamController::class, 'downloadExampleScheme'])->name('downloadExampleScheme');
            Route::put('{exam}', [OrganizationExamController::class, 'update'])->name('update');
            Route::delete('{exam}', [OrganizationExamController::class, 'destroy'])->name('destroy');
            Route::prefix('{exam?}/exams')->name('exam.')->group(function(){
                Route::get('/', [OrganizationExamController::class, 'show'])->name('index');
                Route::get('create', [OrganizationExamController::class, 'createExam'])->name('create');
                Route::post('create', [OrganizationExamController::class, 'storeExam'])->name('store');
                Route::post('{examId}', [OrganizationExamController::class, 'showExam'])->name('show');
                Route::get('{examId}/download', [OrganizationExamController::class, 'downloadPdf'])->name('downloadPdf');
                Route::post('{examId}/analysis', [StudentExamController::class, 'analysis'])->name('analysis');
                Route::get('{examId}/edit', [OrganizationExamController::class, 'editExam'])->name('edit');
                Route::put('{examId}', [OrganizationExamController::class, 'updateExam'])->name('update');
                Route::delete('{examId}', [OrganizationExamController::class, 'destroyExam'])->name('destroy');
            });
        });
        Route::prefix('exam-scheme')->name('examScheme.')->group(function(){
                Route::get('/', [OrganizationExamSchemeController::class, 'index'])->name('index');
                Route::get('create', [OrganizationExamSchemeController::class, 'create'])->name('create');
                Route::post('create', [OrganizationExamSchemeController::class, 'store'])->name('store');
                Route::get('{examScheme}/edit', [OrganizationExamSchemeController::class, 'edit'])->name('edit');
                Route::put('{examScheme}', [OrganizationExamSchemeController::class, 'update'])->name('update');
                Route::delete('{examScheme}', [OrganizationExamSchemeController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('optic-papers')->name('optic.')->group(function(){
            Route::get('/', [OrganizationOpticController::class, 'index'])->name('index');
            Route::get('create', [OrganizationOpticController::class, 'create'])->name('create');
            Route::post('create', [OrganizationOpticController::class, 'store'])->name('store');
            Route::post('upload-fmt', [OrganizationOpticController::class, 'uploadFmt'])->name('uploadFmt');
            Route::get('{optic}/edit', [OrganizationOpticController::class, 'edit'])->name('edit');
            Route::get('{optic}/download-fmt', [OrganizationOpticController::class, 'downloadFmt'])->name('downloadFmt');
            Route::put('{optic}', [OrganizationOpticController::class, 'update'])->name('update');
            Route::delete('{optic}', [OrganizationOpticController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('attendance')->name('attendance.')->group(function(){
            Route::get('download-attendance-today',[OrganizationAttendanceController::class, 'downloadAttendanceToday'])->name('downloadAttendanceToday');
            Route::get('download-attendance-week',[OrganizationAttendanceController::class, 'downloadAttendanceWeek'])->name('downloadAttendanceWeek');
            Route::get('download-attendance-month',[OrganizationAttendanceController::class, 'downloadAttendanceMonth'])->name('downloadAttendanceMonth');
        });
        Route::prefix('class')->name('class.')->group(function(){
            Route::get('/', [OrganizationClassController::class, 'index'])->name('index');
            Route::get('create', [OrganizationClassController::class, 'create'])->name('create');
            Route::post('create', [OrganizationClassController::class, 'store'])->name('store');
            Route::get('{class}', [OrganizationClassController::class, 'show'])->name('show');
            Route::get('{class}/edit', [OrganizationClassController::class, 'edit'])->name('edit');
            Route::put('{class}/update', [OrganizationClassController::class, 'update'])->name('update');
            Route::get('{class}/download', [OrganizationClassController::class, 'download'])->name('download');
            Route::prefix('{class}/announcements')->name('announcement.')->group(function (){
                Route::get('/', [OrganizationClassController::class, 'announcementIndex'])->name('index');
                Route::post('create', [OrganizationClassController::class, 'announcementStore'])->name('store');
                Route::delete('{ann}/destroy', [OrganizationClassController::class, 'announcementDestroy'])->name('destroy');
            });
            Route::post('{class}/get-students', [OrganizationClassController::class, 'getStudentsClass'])->name('getStudentsClass');
            Route::get('{class}/create-time-table', [OrganizationClassController::class, 'createTimeTable'])->name('createTimeTable');
            Route::post('{class}/create-time-table', [OrganizationClassController::class, 'createTimeTableStore'])->name('createTimeTableStore');
            Route::post('{examId}/showExamScore', [OrganizationExamController::class, 'showExamScore'])->name('showExam');
            Route::post('{class}/show-attendance/{hour}', [OrganizationClassController::class, 'showAttendance'])->name('showAttendance');
            Route::get('{class}/download-attendance/{day}/all', [OrganizationClassController::class, 'attendanceDownloadAll'])->name('attendanceDownloadAll');
            Route::get('{class}/download-attendance/{day}/status_1', [OrganizationClassController::class, 'attendanceDownload_1'])->name('attendanceDownload_1');
            Route::get('{class}/download-attendance/{day}/status_0', [OrganizationClassController::class, 'attendanceDownload_0'])->name('attendanceDownload_0');
            Route::delete('{class}', [OrganizationClassController::class, 'destroy'])->name('destroy');
        });
        Route::get('logout', [OrganizationAuthController::class, 'logout'])->name('logout');
    });
});
/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Kurum Yöneticisi Routeları Bitiş
|--------------------------------------------------------------------------
|
|
|--------------------------------------------------------------------------
| Eğitim Koçluğu Öğretmen Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('login', [TeacherAuthController::class, 'loginView'])->name('login');
    Route::post('login', [TeacherAuthController::class, 'loginStore']);
    Route::middleware('auth.teacher')->group(function () {

        Route::get('/settings',[HomeController::class,"teacherSettings"])->name("settings");
        Route::get('/settings',[HomeController::class,"teacherSettingsStorer"])->name("settings");
        Route::get('/', [HomeController::class, 'teacherIndex'])->name('index');
        Route::post('analyzes-student/{student?}', [HomeController::class, 'getStatsStudent'])->name('getStatsStudent');
        Route::prefix('organization')->name('organization.')->group(function () {
            Route::get('/', [TeacherOrganizationController::class, 'index'])->name('index');
            Route::get('{organization}/students', [TeacherOrganizationController::class, 'showStudents'])->name('student');
            Route::get('{organization}/destroy', [TeacherOrganizationController::class, 'destroy'])->name('destroy');
        });
        //Öğrenci Yönetim Linkleri
        Route::prefix('students')->name('student.')->group(function () {
            Route::get('/', [TeacherStudentController::class, 'index'])->name('index');
            Route::get('{student}', [TeacherStudentController::class, 'show'])->name('show');
            Route::get('{student}/exams', [TeacherStudentController::class, 'exams'])->name('exam');
            Route::post('{exam}/analysis', [StudentExamController::class, 'analysis'])->name('analysis');
            Route::get('{exam}/download', [StudentExamController::class, 'downloadPdf'])->name('downloadPdf');
            Route::delete('{student}', [TeacherStudentController::class, 'destroy'])->name('destroy');
        });
        //Sınav Yönetim Linkleri
        Route::prefix('exam')->name('exam.')->group(function () {
            Route::get('/', [TeacherExamController::class, 'index'])->name('index');
            Route::post('{exam}', [TeacherExamController::class, 'show'])->name('show');
        });
        //Duyuru Yönetim Linkleri
        Route::prefix('announcements')->name('announcement.')->group(function () {
            Route::get('/', [TeacherAnnouncementController::class, 'index'])->name('index');
            Route::post('create', [TeacherAnnouncementController::class, 'store'])->name('store');
            Route::delete('{class}/destroy', [TeacherAnnouncementController::class, 'destroy'])->name('destroy');
        });
        //Ajanda Yönetim Linkleri
        Route::prefix('mentor-follow-up')->name('mentor.')->group(function () {
            Route::get('/', [TeacherMentorFollowUpController::class, 'index'])->name('index');
            Route::post('{id}/remind-student', [TeacherMentorFollowUpController::class, 'remindStudent'])->name('remindStudent');
        });
        //Soru Cevap Yönetim Linkleri
        Route::prefix('question-answer')->name('questionAnswer.')->group(function () {
            Route::get('/{id?}', [TeacherQuestionAnswerController::class, 'index'])->name('index');
            Route::post('{id}/answer', [TeacherQuestionAnswerController::class, 'answer'])->name('answer');
            Route::get('{id}/download', [TeacherQuestionAnswerController::class, 'download'])->name('download');
        });
        //Ödevlendirme Yönetim Linkleri
        Route::prefix('assignment')->name('assignment.')->group(function () {
            Route::get('/', [TeacherAssignmentController::class, 'index'])->name('index');
            Route::get('create', [TeacherAssignmentController::class, 'create'])->name('create');
            Route::post('create', [TeacherAssignmentController::class, 'store'])->name('store');
            Route::get('{assignment}', [TeacherAssignmentController::class, 'show'])->name('show');
            Route::get('{assignment}/response/{response}', [TeacherAssignmentController::class, 'showResponse'])->name('showResponse');
            Route::post('get-students', [TeacherAssignmentController::class, 'getStudents'])->name('getStudents');
            Route::delete('{assignment}', [TeacherAssignmentController::class, 'destroy'])->name('destroy');
        });
        //Sınıf Yönetim Linkleri
        Route::prefix('class')->name('class.')->group(function(){
            Route::get('/', [TeacherClassController::class, 'index'])->name('index');
            Route::get('all', [TeacherClassController::class, 'all'])->name('all');
            Route::get('{class?}/show', [TeacherClassController::class, 'show'])->name('show');
            Route::get('{class?}/download', [TeacherClassController::class, 'download'])->name('download');
            Route::prefix('announcements')->name('announcement.')->group(function (){
                Route::get('/{class?}', [TeacherClassController::class, 'announcementIndex'])->name('index');
                Route::post('create/{class?}', [TeacherClassController::class, 'announcementStore'])->name('store');
                Route::delete('{ann}/destroy', [TeacherClassController::class, 'announcementDestroy'])->name('destroy');
            });
            Route::post('{class}/init-attendance/{hour}', [TeacherClassController::class, 'initAttendance'])->name('initAttendance');
            Route::post('{examId}/showExamScore', [OrganizationExamController::class, 'showExamScore'])->name('showExam');
            Route::post('{class}/show-attendance/{hour}', [TeacherClassController::class, 'showAttendance'])->name('showAttendance');
            Route::get('{class}/download-attendance/{day}/all', [TeacherClassController::class, 'attendanceDownloadAll'])->name('attendanceDownloadAll');
            Route::get('{class}/download-attendance/{day}/status_1', [TeacherClassController::class, 'attendanceDownload_1'])->name('attendanceDownload_1');
            Route::get('{class}/download-attendance/{day}/status_0', [TeacherClassController::class, 'attendanceDownload_0'])->name('attendanceDownload_0');
        });
        //Ders Talepleri Yönetim Linkleri
        Route::prefix('lesson-request')->name('lessonRequest.')->group(function () {
            Route::get('/', [TeacherLessonRequestController::class, 'index'])->name('index');

            Route::put('change-url', [TeacherLessonRequestController::class, 'changeUrl'])->name('changeUrl');
            Route::put('{lessonRequest}/accept', [TeacherLessonRequestController::class, 'accept'])->name('accept');
            Route::put('{lessonRequest}/reject', [TeacherLessonRequestController::class, 'reject'])->name('reject');
        });
        Route::get('logout', [TeacherAuthController::class, 'logout'])->name('logout');
    });
});
/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Öğretmen Routeları Bitiş
|--------------------------------------------------------------------------
|
|
|--------------------------------------------------------------------------
| Eğitim Koçluğu Öğrenci Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/

Route::prefix('student')->name('student.')->group(function () {
    Route::get('login', [StudentAuthController::class, 'loginView'])->name('login');
    Route::post('login', [StudentAuthController::class, 'loginStore']);
    Route::get('register', [StudentAuthController::class, 'registerView'])->name('register');
    Route::post('register', [StudentAuthController::class, 'registerStore']);
    Route::middleware('auth.student')->group(function () {

        Route::get('/settings',[HomeController::class,"studentSettings"])->name("settings");
        Route::get('/settings',[HomeController::class,"studentSettingsStore"])->name("settings");
        Route::get('/', [HomeController::class, 'studentIndex'])->name('index');
        Route::get('analyzes', [HomeController::class, 'studentAnalyzes'])->name('analyzes');
        Route::post('analyzes-student/{student?}', [HomeController::class, 'getStatsStudent'])->name('getStatsStudent');
        //Yol Arkadaşım Yönetim Linkleri
        Route::prefix('stats')->name('stats.')->group(function (){
            Route::post('getExamResults', [StudentStatsController::class, 'getExamResults'])->name('getExamResults');
        });
        Route::prefix('travel-mate')->name('ai.')->group(function () {
            Route::get('/', [StudentAIController::class, 'index'])->name('index');
            Route::post('/send', [StudentAIController::class, 'store'])->name('store');
            Route::get('/download', [StudentAIController::class, 'download'])->name('download');
        });
        //Tercih Robotu Linkleri
        Route::prefix('preference-robot')->name('preferenceRobot.')->group(function () {
            Route::get('/', [StudentPreferenceRobotController::class, 'index'])->name('index');
            Route::post('/calculate', [StudentPreferenceRobotController::class, 'calculate'])->name('calculate');
        });
        //Duyuru Yönetim Linkleri
        Route::prefix('announcements')->name('announcement.')->group(function () {
            Route::get('/', [StudentAnnouncementController::class, 'index'])->name('index');
        });
        //Ajanda Yönetim Linkleri
        Route::prefix('mentor-follow-up')->name('mentor.')->group(function () {
            Route::get('/', [StudentMentorFollowUpController::class, 'index'])->name('index');
            Route::post('/create', [StudentMentorFollowUpController::class, 'store'])->name('store');
        });
        //Ödevlendirme Yönetim Linkleri
        Route::prefix('assignment')->name('assignment.')->group(function () {
            Route::get('/', [StudentAssignmentController::class, 'index'])->name('index');
            Route::post('create/{id}', [StudentAssignmentController::class, 'store'])->name('store');
            Route::get('{assignment}', [StudentAssignmentController::class, 'show'])->name('show');
        });
        //Soru Cevap Yönetim Linkleri
        Route::prefix('question-answer')->name('questionAnswer.')->group(function () {
            Route::get('/ask', [StudentQuestionAnswerController::class, 'ask'])->name('create');
            Route::get('download-all', [StudentQuestionAnswerController::class, 'downloadAll'])->name('downloadAll');
            Route::post('/ask', [StudentQuestionAnswerController::class, 'store'])->name('store');
            Route::get('{id?}', [StudentQuestionAnswerController::class, 'index'])->name('index');
            Route::put('{id}/complete', [StudentQuestionAnswerController::class, 'complete'])->name('complete');
            Route::post('{id}/answer', [StudentQuestionAnswerController::class, 'answer'])->name('answer');
            Route::get('{id}/download', [StudentQuestionAnswerController::class, 'download'])->name('download');
        });

        //Sınav Yönetim Linkleri
        Route::prefix('exams')->name('exam.')->group(function () {
            Route::get('/', [StudentExamController::class, 'index'])->name('index');
            Route::get('create/{scheme?}', [StudentExamController::class, 'create'])->name('create');
            Route::post('create', [StudentExamController::class, 'store'])->name('store');
            Route::post('{exam}/analysis', [StudentExamController::class, 'analysis'])->name('analysis');
            Route::post('{exam}', [StudentExamController::class, 'show'])->name('show');
            Route::get('{exam}/download', [StudentExamController::class, 'downloadPdf'])->name('downloadPdf');
            Route::get('{exam}/edit', [StudentExamController::class, 'edit'])->name('edit');
            Route::put('{exam}', [StudentExamController::class, 'update'])->name('update');
            Route::delete('{exam}', [StudentExamController::class, 'destroy'])->name('destroy');
        });
        //Ders Programı Yönetim Linkleri
        Route::prefix('schedule')->name('schedule.')->group(function () {
            Route::get('/', [StudentScheduleController::class, 'index'])->name('index');
            Route::get('create', [StudentScheduleController::class, 'create'])->name('create');
            Route::post('create', [StudentScheduleController::class, 'store'])->name('store');
        });
        Route::prefix('class')->name('class.')->group(function (){
            Route::get('schedule', [StudentClassController::class, 'schedule'])->name('schedule');
            Route::get('announcements', [StudentClassController::class, 'announcements'])->name('announcements');
        });
        Route::get('logout', [StudentAuthController::class, 'logout'])->name('logout');
    });
});
