<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcements extends Model
{
    use HasFactory;
    protected $table = 'announcements';
    public function getIcon()
    {
        $extension = $this->getFileExtension();
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return 'file-image';
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
            case 'txt':
            case 'pdf':
                return 'file-text';
            case 'mp4':
            case 'avi':
            case 'wmv':
            case 'mov':
                return 'file-video';
            case '':
                return 'file-empty';
            case 'pptx':
                return 'presentation';
            case 'zip':
            case 'rar':
            case 'tar':
            case 'gz':
            case '7z':
                return 'archive';

            default:
                return 'file-empty';
        }
    }
    public function getFileName(){
        return pathinfo($this->file,PATHINFO_FILENAME). '.' . pathinfo($this->file,PATHINFO_EXTENSION);
    }
    public function getFileSize(){
        $size = Storage::size($this->file);
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($size < $kb) {
            return $size . ' B';
        } elseif ($size < $mb) {
            return round($size / $kb, 2) . ' KB';
        } elseif ($size < $gb) {
            return round($size / $mb, 2) . ' MB';
        } elseif ($size < $tb) {
            return round($size / $gb, 2) . ' GB';
        } else {
            return round($size / $tb, 2) . ' TB';
        }

    }
    public function getFileExtension(){
        return pathinfo($this->file,PATHINFO_EXTENSION);
    }
    public function getFileUrl(){
        return Storage::url($this->file);
    }


}
