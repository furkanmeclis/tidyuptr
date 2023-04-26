<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationLicense extends Model
{
    use HasFactory;

    protected $table = 'organization_licenses';

    protected $fillable = [
        'organization_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function getTotalLicenseTime()
    {
        if ($this->start_date && $this->end_date) {
            $now = Carbon::parse($this->start_date);
            $end_date = Carbon::parse($this->end_date);
            $diff = $now->diff($end_date);
            $years = $diff->format('%y');
            $months = $diff->format('%m');
            $days = $diff->format('%d');

            $result = '';
            if ($years > 0) {
                $result .= $years . ' yıl ';
            }
            if ($months > 0) {
                $result .= $months . ' ay ';
            }
            if ($days > 0) {
                $result .= $days . ' gün';
            }
            return $result;
        }
        return 'Veri Yok';
    }
    public function getRemainingTime()
    {
        if ($this->start_date && $this->end_date) {
            $now = Carbon::now();
            $end_date = Carbon::parse($this->end_date);
            $diff = $now->diff($end_date);
            $years = $diff->format('%y');
            $months = $diff->format('%m');
            $days = $diff->format('%d');

            $result = '';
            if ($years > 0) {
                $result .= $years . ' yıl ';
            }
            if ($months > 0) {
                $result .= $months . ' ay ';
            }
            if ($days > 0) {
                $result .= $days . ' gün';
            }
            return $result;
        }
        return 'Veri Yok';
    }
    public function startDate($splitter = ".")
    {
        return Carbon::parse($this->start_date)->format("d" . $splitter . "m" . $splitter . "Y");
    }
    public function endDate($splitter = ".")
    {
        return Carbon::parse($this->end_date)->format("d" . $splitter . "m" . $splitter . "Y");
    }
}
