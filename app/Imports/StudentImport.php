<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    public $students = [];
    public function model(array $row)
    {

        $student = [];
        $mappedColumns = [
            'email' => ['E-Posta', 'Email', 'Mail Adresi', 'Mail', 'email','e_posta','mail_adresi','mail'],
            'name' => ['Ad Soyad', 'Adı Soyadı', 'Ad', 'name','ad_soyad','adı_soyadı','ad'],
            'identity_number' => ['Kimlik Numarası', 'Kimlik No', 'TC Kimlik No', 'TC', 'identity_number','kimlik_numarasi','kimlik_no','tc_kimlik_no','tc'],
            'phone' => ['Telefon', 'Telefonu', 'Telefon Numarası', 'phone','telefon','telefonu','telefon_numarası'],
            'address' => ['Adres', 'Adresi', 'Adres Bilgisi', 'address','adres','adresi','adres_bilgisi'],
        ];
        $returnNull = false;
        foreach (array_merge($mappedColumns['email'],$mappedColumns['name'],$mappedColumns['identity_number']) as $columnName) {
            if (isset($row[$columnName])) {
                $returnNull = true;
                break;
            }
        }
        if (!$returnNull) {
            return null;
        }
        foreach ($mappedColumns as $fieldName => $columnNames) {
            foreach ($columnNames as $columnName) {
                if (isset($row[$columnName])) {
                    $student[$fieldName] = $row[$columnName];
                    break;
                }
            }
        }
        //email kontrol
        if (isset($student['email'])) {
            $student['email'] = strtolower($student['email']);
            if (Student::where('email', $student['email'])->first()) {
                $this->students[] = [...$student, 'status' => false,'error'=>'Bu e-posta adresi ile daha önce kayıt olunmuş.'];
                return null;
            }
        }
        $this->students[] = [...$student, 'status' => true];
        $student['organization_id'] = auth('organization')->user()->id;
        $student['password'] = Hash::make(substr($student['identity_number'], 0,8));
        return new Student($student);
    }
    public function getResult()
    {
        return $this->students;
    }
}
