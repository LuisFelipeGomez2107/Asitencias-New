<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use Spatie\Permission\Traits\HasRoles;

class AdminController extends Controller
{
    use HasRoles;

    public function getAreas()
    {

        $areas = Areas::all();
        return $areas;
    }
}
