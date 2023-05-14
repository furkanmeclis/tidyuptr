<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Organization\OrganizationAuthController;
use App\Http\Controllers\Organization\OrganizationExamController;
use App\Http\Controllers\Organization\OrganizationStudentController;
use App\Http\Controllers\Organization\OrganizationTeacherController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\SystemAdmin\SystemAdminAuthController;
use App\Http\Controllers\SystemAdmin\SystemAdminExamController;
use App\Http\Controllers\SystemAdmin\SystemAdminLessonController;
use App\Http\Controllers\SystemAdmin\SystemAdminLicenseController;
use App\Http\Controllers\SystemAdmin\SystemAdminOrganizationController;
use App\Http\Controllers\SystemAdmin\SystemAdminStudentController;
use App\Http\Controllers\SystemAdmin\SystemAdminTeacherController;
use App\Http\Controllers\Teacher\TeacherAnnouncementController;
use App\Http\Controllers\Teacher\TeacherAssignmentController;
use App\Http\Controllers\Teacher\TeacherAuthController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherMentorFollowUpController;
use App\Http\Controllers\Teacher\TeacherOrganizationController;
use App\Http\Controllers\Teacher\TeacherQuestionAnswerController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Sistem Yöneticisi Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/
Route::get('/chat-gpt',function(){
    $openaiService = new OpenAIService();
    $messages = [
        [
            'role' => 'system',
            'content' => 'Sen bir Öğretmensin cevapların çok samimi olmalı'
        ],
        [
            'role' => 'user',
            'content' => 'Merhaba Hocam Aklıma Takılan bir soru var.Biyloji dersinde çiftleşme konusundayım çiftleşme nasıl oluyor insanlar arasında '
        ]
    ];
    $response = $openaiService->generateChatCompletion($messages);
    echo $response['choices'][0]['message']['content'];
});
Route::prefix('system-admin')->name('systemAdmin.')->group(function () {
    Route::get('login', [SystemAdminAuthController::class, 'loginView'])->name('login');
    Route::post('login', [SystemAdminAuthController::class, 'loginStore']);
    Route::middleware('auth.admin')->group(function () {
        Route::view('/', 'systemAdmin/index')->name('index');
        Route::get('dersprogram', [HomeController::class, 'index']);
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
            Route::get('{exam}/edit', [SystemAdminExamController::class, 'edit'])->name('edit');
            Route::put('{exam}', [SystemAdminExamController::class, 'update'])->name('update');
            Route::delete('{exam}', [SystemAdminExamController::class, 'destroy'])->name('destroy');
            Route::delete('batch/{exam}', [SystemAdminExamController::class, 'destroyBatch'])->name('destroyBatch');
        });
        Route::get('logout', [SystemAdminAuthController::class, 'logout']);
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
        Route::get('/', [SystemAdminOrganizationController::class, 'index'])->name('index');

        //Öğretmen Yönetim Linkleri
        Route::prefix('teachers')->name('teacher.')->group(function () {
            Route::get('/', [OrganizationTeacherController::class, 'index'])->name('index');
            Route::post('get-teachers/{student_id?}', [OrganizationTeacherController::class, 'getTeachers'])->name('getTeachers');
            Route::get('{teacher}', [OrganizationTeacherController::class, 'show'])->name('show');
            Route::get('{teacher}/student', [OrganizationTeacherController::class, 'showStudent'])->name('showStudent');
            Route::delete('{teacher}/end-registration', [OrganizationTeacherController::class, 'endRegistration'])->name('endRegistration');
        });

        //Öğrenci Yönetim Linkleri
        Route::prefix('students')->name('student.')->group(function () {
            Route::get('/', [OrganizationStudentController::class, 'index'])->name('index');
            Route::get('create', [OrganizationStudentController::class, 'create'])->name('create');
            Route::post('create', [OrganizationStudentController::class, 'store'])->name('store');
            Route::post('get-students', [OrganizationStudentController::class, 'getStudents'])->name('getStudents');
            Route::get('{student}', [OrganizationStudentController::class, 'show'])->name('show');
            Route::get('{student}/exams', [OrganizationStudentController::class, 'exams'])->name('exam');
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
            Route::post('lessons', [OrganizationExamController::class, 'getLessons'])->name('getLessons');
            Route::get('{exam}', [OrganizationExamController::class, 'show'])->name('show');
            Route::get('{exam}/edit', [OrganizationExamController::class, 'edit'])->name('edit');
            Route::put('{exam}', [OrganizationExamController::class, 'update'])->name('update');
            Route::delete('{exam}', [OrganizationExamController::class, 'destroy'])->name('destroy');
            Route::prefix('{exam}/exams')->name('exam.')->group(function(){
                Route::get('/', [OrganizationExamController::class, 'show'])->name('index');
                Route::get('create', [OrganizationExamController::class, 'createExam'])->name('create');
                Route::post('create', [OrganizationExamController::class, 'storeExam'])->name('store');
                Route::post('{examId}', [OrganizationExamController::class, 'showExam'])->name('show');
                Route::get('{examId}/edit', [OrganizationExamController::class, 'editExam'])->name('edit');
                Route::put('{examId}', [OrganizationExamController::class, 'updateExam'])->name('update');
                Route::delete('{examId}', [OrganizationExamController::class, 'destroyExam'])->name('destroy');
            });
        });
        Route::get('logout', [OrganizationAuthController::class, 'logout']);
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
        Route::get('/', [SystemAdminOrganizationController::class, 'index'])->name('index');
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
        Route::get('logout', [TeacherAuthController::class, 'logout']);
    });
});
/*
|--------------------------------------------------------------------------
| Eğitim Koçluğu Öğretmen Routeları Bitiş
|--------------------------------------------------------------------------
|
|
|--------------------------------------------------------------------------
| Eğitim Koçluğu Öğretmen Routeları Başlangıç
|--------------------------------------------------------------------------
|
*/

Route::prefix('student')->name('student.')->group(function () {
    Route::get('login', [StudentAuthController::class, 'loginView'])->name('login');
    Route::post('login', [StudentAuthController::class, 'loginStore']);
    Route::middleware('auth.student')->group(function () {
        Route::get('/', [SystemAdminOrganizationController::class, 'index'])->name('index');
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
        Route::get('logout', [TeacherAuthController::class, 'logout']);
    });
});
