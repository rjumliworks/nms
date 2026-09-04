<?php

namespace App\Services;

use App\Models\ListDropdown;
use App\Models\ListRole;

class DropdownClass
{
    public function dropdowns($class, $type = null)
    {
        $data = ListDropdown::where('classification', $class)
        ->when($type, function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->where('is_active', 1)
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function roles()
    {
        $data = ListRole::where('is_active', 1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }
}
