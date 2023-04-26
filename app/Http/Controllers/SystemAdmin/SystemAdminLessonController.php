<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class SystemAdminLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('systemAdmin.lesson.all')->with('lessons', Lesson::all());
    }

    public function create()
    {
        return view('systemAdmin.lesson.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:lessons'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('name'))
            ]);
        }
        try {
            $topics = $request->input('topics');
            $lesson = new Lesson;
            $lesson->name =  $request->input('name');
            $lesson->grade = $request->input('grade');
            if ($lesson->saveOrFail()) {
                foreach ($topics as $topic) {
                    if (!empty($topic['name'])) {
                        $coefficient = !empty($topic['coefficient']) ? $topic['coefficient'] : 0;
                        $newTopic = $lesson->topics()->create([
                            'name' => $topic['name'],
                            'coefficient' => $coefficient
                        ]);
                    }
                }
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('systemAdmin.lesson.show', ["lesson" => $lesson->id])
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
    public function storeTopic(Request $request, $id)
    {
        try {
            $topic = new Topic;
            $topic->lesson_id = $id;
            $topic->name =  $request->input('name');
            $topic->coefficient = $request->input('coefficient');
            if ($topic->saveOrFail()) {

                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "newData" => [
                        "id" => $topic->id,
                        "name" => $topic->name,
                        "coefficient" => $topic->coefficient,
                        "check" => ""
                    ]
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        return view('systemAdmin.lesson.show')->with('lesson', $lesson);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        return view('systemAdmin.lesson.edit')->with('lesson', $lesson);
    }

    public function update(Request $request, $lessonId)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('lessons')->ignore($lessonId)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('name'))
            ]);
        }
        try {
            $lesson = Lesson::find($lessonId);
            $lesson->name =  $request->input('name');
            $lesson->grade = $request->input('grade');
            if ($lesson->save()) {
                return response()->json([
                    "status" => true,
                    "message" => "Güncelleme İşlemi Başarılı",
                    "url" => route('systemAdmin.lesson.show', ["lesson" => $lesson->id])
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
    }
    public function updateTopic(Request $request, $lessonId, $topicId)
    {
        try {
            $topic = Topic::where('lesson_id', $lessonId)->where('id', $topicId)->first();
            $topic->name =  $request->input('name');
            $topic->coefficient = $request->input('coefficient');
            if ($topic->save()) {
                return response()->json([
                    "status" => true,
                    "message" => "Güncelleme İşlemi Başarılı",
                    "editedData" => [
                        "id" => $topic->id,
                        "name" => $topic->name,
                        "coefficient" => $topic->coefficient,
                        "check" => ""
                    ]
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
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $record = Lesson::find($id);
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
    public function destroyTopic($id)
    {
        $record = Topic::find($id);
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
