<?php

namespace App\Http\Controllers\Organization;

use App\Models\ExamSchemes;
use App\Models\Lesson;
use App\Models\OpticalParameter;
use App\Models\OpticalParameterDetails;
use App\Services\FmtReader;
use App\Services\OpticHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationOpticController extends Controller
{
    public function index()
    {
        $schemes = OpticalParameter::where('organization_id',auth('organization')->user()->id)->get();
        return view('organizationAdmin.optic.all',compact('schemes'));
    }

    public function create()
    {
        $lessons = Lesson::all();
        return view('organizationAdmin.optic.create',compact('lessons'));
    }


    public function uploadFmt(Request $request)
    {
        try{
            $fmtReader = new FmtReader($request->file('file')->getContent());
            $fmt = $fmtReader->getFmtDetails();
            $helper = new OpticHelper($fmt['areas']);
            return response()->json(['status' => true, 'message' => 'Optik Şeması Başarıyla Okundu',"data"=>[
                "paper" => $fmt['paper'],
                "areas" => $helper->getTransactionArray()
            ],"url" => route('organizationAdmin.optic.store')]);
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    protected function getMatched($area, $matches)
    {
        foreach($matches as $matched){
            if($area['xs'] == $matched['xs'] && $area['ys'] == $matched['ys'] && $area['xe'] == $matched['xe'] && $area['ye'] == $matched['ye']){
                return $matched;
            }
        }
        return null;
    }
    public function store(Request $request)
    {
        try{
            if($request->hasFile('file')){
                $optic = new OpticalParameter();
                $optic->name = $request->input('name');
                $optic->organization_id = auth('organization')->user()->id;
                $paperDetails = $request->file('file')->getContent();
                $fmtReader = new FmtReader($paperDetails);
                $fmt = $fmtReader->getFmtDetails();
                $optic->values = json_encode($fmt['paper']);
                $path = $request->file('file')->store('uploads');
                if($optic->save()){
                    $areas = $fmt['areas'];
                    $opticHelper = new OpticHelper($areas);
                    $transactionArray = $opticHelper->getTransactionArray();
                    foreach ($transactionArray as $key => $area){
                        $areaN = new OpticalParameterDetails();
                        $areaN->optical_paramater_id = $optic->id;
                        $areaN->name = $area['name'];
                        $coordinates = ['xs' => $area['xs'], 'ys' => $area['ys'],'xe' => $area['xe'], 'ye' => $area['ye']];
                        $areaN->length = $area['length'];
                        $areaN->alignment = $area['level'];
                        $areaN->coordinates = json_encode($coordinates);
                        $areaN->data_type = $area['dataType'];
                        $areaN->index = $key;
                        $areaN->type = $area['type'];
                        $areaN->save();
                    }
                    return response()->json(['status' => true, 'message' => 'Optik Şeması Başarıyla Yüklendi',"url"=>route('organizationAdmin.optic.index')]);
                }else {
                    if ($path){
                        Storage::delete($path);
                    }
                    return response()->json(['status' => false, 'message' => 'Optik Şeması Yüklenirken Bir Hata Oluştu,Lütfen Sayfayı Yenileyin']);
                }
            }else{
                return response()->json(['status' => false, 'message' => 'Optik Şeması Yüklenirken Bir Hata Oluştu,Lütfen Sayfayı Yenileyin']);
            }

        }catch (\Exception $e){
           return response()->json(['status' => false, 'message' => $e->getMessage(),$e->getLine(),$e->getFile()]);
        }
    }

    public function edit($id)
    {
        return redirect()->back();
        $scheme = ExamSchemes::find($id);
        return view('organizationAdmin.examScheme.edit',compact('scheme'));
    }

    public function update(Request $request, $id)
    {
        try{
            return redirect()->back();
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
        $scheme = OpticalParameter::find($id);
        if (!$scheme) {
            return response()->json(['status' => false, 'message' => 'Optik Şeması Bulunamadı']);
        }
        try {
            $file = $scheme->file;
            if ($file) {
                Storage::delete($file);
            }
            $scheme->delete();
            return response()->json(['status' => true, 'message' => 'Optik Şeması Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function downloadFmt($id)
    {
        $scheme = OpticalParameter::find($id);
        if (!$scheme) {
            return response()->json(['status' => false, 'message' => 'Optik Şeması Bulunamadı']);
        }
        try {
            $fmtContent = $scheme->getFmtContent();
            //new random with .fmt extension file
            $fileName = Str::random(10) . '.fmt';
            Storage::put($fileName, $fmtContent);
            return response()->download(storage_path('app/public/' . $fileName));
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
