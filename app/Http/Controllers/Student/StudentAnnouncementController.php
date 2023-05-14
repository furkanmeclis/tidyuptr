<?php

namespace App\Http\Controllers\Student;

use App\Models\Announcements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentAnnouncementController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        return view('teacher.announcement.all', [
            'contents' => Announcements::where('teacher_id', auth()->guard('teacher')->user()->id)->orderBy('updated_at', 'asc')->get(),
        ]);
    }
    public function store(Request $request){
        try {
            $content = $request->input('content');
            $class = new Announcements();
            $class->teacher_id = auth()->guard('teacher')->user()->id;
            $class->content = $content;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads');
                $class->file = $path;
            }
            if($class->save()){
                return response()->json([
                    "status" => true,
                    "message" => "Duyuru Eklendi",
                    "url" => route('teacher.announcement.index')
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Duyuru Eklenemedi",
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }

    }

    public function destroy($id)
    {
        $content = Announcements::find($id);
        if (!$content) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $file_path = $content->file;
            if ($file_path) {
                Storage::delete($file_path);
            }
            $content->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
