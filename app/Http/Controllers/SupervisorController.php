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

    public function createUser(Request $request)
    {

        $request->validate([
            'nombre' => 'required',
            'email' => 'required',
            'tipoUser' => 'required',
            'password' => 'required'
        ]);

        $id_areas = intval($request->areas);
        if (!isset($request->supervisor)) {
            $ext = 'jpg';
            $nombre = Str::random(30) . '.' . $ext;
            $nombreFirma = Str::random(30) . '.' . $ext;
            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->areas_id = $id_areas;


            if ($request->imagen) {
                $user->profile_photo_path = $nombre;

                $ext = $request->file('imagen')->extension();
                $tmpDir = $user->id;
                $carpeta = "personales";
                $imagen = Img::make($request->file('imagen'))

                    ->encode('jpeg');
                Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombre, $imagen);
            }
            if ($request->firma) {
                $user->firma = $nombreFirma;

                $ext = $request->file('imagen')->extension();
                $tmpDir = $user->id;
                $carpeta = "personales";

                $imageF = Img::make($request->file('firma'))

                    ->encode('jpeg');
                Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombreFirma, $imageF);
            }

            $user->curp = $request->curp;
            $user->nss = $request->nss;
            $user->save();
            $user->assignRole($request->tipoUser);
            $userHasStatus = new users_has_status;
            $userHasStatus->user_id = $user->id;
            $userHasStatus->status = 1;
            $userHasStatus->save();



            return ['succes' => "Guardado con exito"];
        } else {
            $ext = 'jpg';
            $nombre = Str::random(30) . '.' . $ext;
            $nombreFirma = Str::random(30) . '.' . $ext;
            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->areas_id = $id_areas;


            if ($request->imagen) {
                $user->profile_photo_path = $nombre;

                $ext = $request->file('imagen')->extension();
                $tmpDir = $user->id;
                $carpeta = "personales";

                $imagen = Img::make($request->file('imagen'))

                    ->encode('jpeg');
                Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombre, $imagen);
            }
            if ($request->firma) {
                $user->firma = $nombreFirma;

                $ext = $request->file('imagen')->extension();
                $tmpDir = $user->id;
                $carpeta = "personales";

                $imageF = Img::make($request->file('firma'))

                    ->encode('jpeg');
                Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombreFirma, $imageF);
            }

            $user->curp = $request->curp;
            $user->nss = $request->nss;
            $user->save();
            $user->assignRole($request->tipoUser);
            $super = new Supervisor_has_user();
            $super->user_id = $user->id;
            $super->supevisor_id = $request->supervisor;
            $super->save();
            $userHasStatus = new users_has_status;
            $userHasStatus->user_id = $user->id;
            $userHasStatus->status = 1;
            $userHasStatus->save();

            return  ['succes' => "Guardado con exito con supervisor"];
        }
    }
}
