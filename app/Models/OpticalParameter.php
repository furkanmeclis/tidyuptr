<?php

namespace App\Models;

use App\Services\FmtWriter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OpticalParameter extends Model
{
    use HasFactory;
    protected $table = 'optical_paramaters';

    public function getPaper()
    {
        $exportData = [
        'yl' => '',
        'xl' => '',
        'undefined' => '04',
        'level' => 'D',
        'dataType' => '*',
        'name' => ' ',
        ];
        $paperData = json_decode($this->values,true);
        $exportData['yl'] = $paperData['yl'];
        $exportData['xl'] = $paperData['xl'];
        return $exportData;
    }
    public function areas()
    {
        return $this->hasMany(OpticalParameterDetails::class,'optical_paramater_id','id')->orderBy('index','asc');
    }

    public function getFmtContent()
    {
        $areas = $this->areas()->get();
        $paper = $this->getPaper();
        $dataScheme = [
            "ys" => 0,
            "ye" => 0,
            "xs" => 0,
            "xe" => 0,
            "undefined" => "K",
            "level" => "D",
            "dataType" => "ABCDE",
            "unidentified" => "X2",
            "name" => "",
        ];
        $data = [];
        foreach ($areas as $area){
            $coordinates = json_decode($area->coordinates);
            $dataScheme['ys'] = $coordinates->ys;
            $dataScheme['ye'] = $coordinates->ye;
            $dataScheme['xs'] = $coordinates->xs;
            $dataScheme['xe'] = $coordinates->xe;
            $dataScheme['undefined'] = "K";
            $dataScheme['level'] = $area->alignment;
            $dataScheme['dataType'] = $area->data_type;
            $dataScheme['name'] = $area->name;
            $data[] = $dataScheme;
        }
        $fmtWriter = new FmtWriter($paper,$data);
        return $fmtWriter->getFmt();
    }
}
