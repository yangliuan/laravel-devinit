<?php

namespace App\Traits;

trait DateFormat
{
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value === null) {
            return '';
        } else {
            return $value;
        }
    }

    public function getUpdatedAtAttribute($value)
    {
        if ($value === null) {
            return '';
        } else {
            return $value;
        }
    }
}
