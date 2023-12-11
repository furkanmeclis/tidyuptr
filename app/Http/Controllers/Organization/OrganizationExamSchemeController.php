<?php

namespace App\Http\Controllers\Organization;

use App\Models\ExamSchemes;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrganizationExamSchemeController extends Controller
{
    public function index()
    {
        $schemes = ExamSchemes::where('organization_id',auth('organization')->user()->id)->get();
        return view('organizationAdmin.examScheme.all',compact('schemes'));
    }

    public function create()
    {
        $lessons = Lesson::all();
        return view('organizationAdmin.examScheme.create',compact('lessons'));
    }

    public function store(Request $request)
    {
        try{
            $scheme = new ExamSchemes();
            $lessons = json_decode($request->input('lessons'));
            $lesson_ids = [];
            foreach ($lessons as $lesson){
                $lesson_ids[] = $lesson->id;
            }
            $scheme->name = $request->input('name');
            $scheme->lesson_ids = json_encode($lesson_ids);
            $scheme->organization_id = auth('organization')->user()->id;
            $scheme->grade = $request->input('grade');
            if($scheme->save()){
                return response()->json(['status' => true, 'message' => 'Şema Başarıyla Eklendi',"url"=>route('organizationAdmin.examScheme.index')]);
            }else{
                return response()->json(['status' => false, 'message' => 'Şema Eklenirken Bir Hata Oluştu']);
            }
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $scheme = ExamSchemes::find($id);
        return view('organizationAdmin.examScheme.edit',compact('scheme'));
    }

    public function update(Request $request, $id)
    {
        try{
            $scheme = ExamSchemes::find($id);
            $lessons = json_decode($request->input('lessons'));
            $lesson_ids = [];
            foreach ($lessons as $lesson){
                $lesson_ids[] = $lesson->id;
            }
            $scheme->name = $request->input('name');
            $scheme->lesson_ids = json_encode($request->input('lesson_ids'));
            $scheme->lesson_ids = json_encode($lesson_ids);
            $scheme->organization_id = auth('organization')->user()->id;
            $scheme->grade = $request->input('grade');
            if($scheme->save()){
                return response()->json(['status' => true, 'message' => 'Şema Başarıyla Güncellendi',"url"=>route('organizationAdmin.examScheme.index')]);
            }else{
                return response()->json(['status' => false, 'message' => 'Şema Güncellenirken Bir Hata Oluştu']);
            }
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $scheme = ExamSchemes::find($id);
        if (!$scheme) {
            return response()->json(['status' => false, 'message' => 'Şema Bulunamadı']);
        }
        try {
            $scheme->delete();
            return response()->json(['status' => true, 'message' => 'Şema Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
