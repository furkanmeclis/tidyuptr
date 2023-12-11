<?php

namespace App\Services;

use Carbon\Carbon;

class TimeModifier
{
    public string $startTime = '';
    public $recent=0;
    public function __construct(String $time)
    {
        $this->startTime = $time;
    }
    public function addMinutes($minutes): string
    {
        $this->recent += $minutes;
        $c = Carbon::createFromFormat('H:i:s', $this->startTime);
        $st = $c->addMinutes($minutes);
        $this->startTime = $st->format('H:i:s');
        return $st->format('H:i');
    }
    public function getTime(): string
    {
        $c = Carbon::createFromFormat('H:i:s', $this->startTime);
        return $this->recent;
    }
}
