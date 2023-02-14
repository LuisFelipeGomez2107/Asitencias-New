<?php

namespace App\Http\Controllers;

use File;
use Closure;
use DateTime;
use App\Models\User;
use App\Models\Areas;
use App\Models\Roles;
use App\Models\Images;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Justificaciones;
use App\Models\users_has_status;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Supervisor_has_user;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SupervisorController extends Controller
{
    public function userView(Request $request)
    {

        $user = Auth::user();

        $usuario = User::join('areas', 'users.areas_id', 'areas.id')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->join('users_has_status', 'users.id', 'users_has_status.user_id')
            ->where('users.areas_id', $user->areas_id)
            ->select('users.*', 'areas.name as Areas', 'roles.name AS rol', 'users_has_status.status as status')
            ->get();


        return view('content/apps/user/app-user-list-Supervisor', compact('usuario'));
    }
}
