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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Foundation\Auth\User as Authenticatable;


class AdminController extends Controller
{
    use HasRoles;

    public function upluadJustify(Request $request)
    {

        $user = Auth::user();
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $name = $file->getClientOriginalName();
                $ext = $file->extension();
                $tmpDir = $request->user_id;
                $carpeta = "justificantes";
                if ($ext != "pdf") {
                    // Storage::disk(env('ROUTE'))->put('justificantes/'.$request->user_id, $name);
                    $ext = 'jpg';
                    $nombre = Str::random(30) . '.' . $ext;
                    $imagen = Img::make($file)
                        ->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode('jpeg');
                    Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombre, $imagen);
                } else {
                    $documento = $file;
                    $nombre = Str::random(30) . $documento->getClientOriginalName();
                    Storage::disk(env('ROUTE'))->put($carpeta . "/" . $tmpDir . "/" . $nombre, File::get($documento));
                }
                $justificaciones = new Justificaciones();
                $justificaciones->images_id = $request->imagen_id;
                $justificaciones->user_id = $request->user_id;
                $justificaciones->name = $nombre;
                $justificaciones->falta = $request->date;
                $justificaciones->comentario = $request->comentario;
                $justificaciones->save();
            }
        }
        return redirect('/content/dashboard/dashboard-analytics')->with('status', 'Retardo Justificado Correctamente');
    }



    public function getAreas()
    {

        $areas = Areas::all();
        return $areas;
    }

    public function showJustyFaltas(Request $request)
    {


        $justificacion = Justificaciones::where('user_id', $request->id)
            ->where('falta', $request->date)
            ->get();
        $justificaciones = array();
        $a = 0;
        foreach ($justificacion as $j) {
            $justificaciones[$a]['url'] = Storage::disk(env('ROUTE'))->url('justificantes/' . $request->id . '/' . $j->name);
            $justificaciones[$a]['id'] = $j->id;
            $justificaciones[$a]['name'] = $j->name;
            $justificaciones[$a]['comentario'] = $j->comentario;
            $justificaciones[$a]['userJ'] = $j->userName;
            $a++;
        }
        return $justificaciones;
    }

    public function historialUserList(Request $request)
    {

        $requestpuesto = $request->puesto;
        $requestnombre = $request->nombre;
        $requestArea = $request->area;
        $dateInitial = $request->dateInicio;
        $area = $request->area;
        $nombre = $request->nombre;
        $puesto = $request->puesto;
        $usuario = User::join('areas', 'users.areas_id', 'areas.id')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->join('users_has_status', 'users.id', 'users_has_status.user_id')
            ->select('users.*', 'areas.name as Areas', 'roles.name AS rol', 'users_has_status.status as status')

            ->when($request->area, function ($usuario, $area) {
                return $usuario->where('users.areas_id', $area);
            }, function ($usuario) {
                
            })
            ->when($request->nombre, function ($usuario, $nombre) {
                return $usuario->where('users.name', 'like', "%" . $nombre . "%");
            })
            ->when($request->puesto, function ($usuario, $puesto) {
                return $usuario->where('roles.id', $puesto);
            })
            ->get();

        return view('admin.user', compact('usuario'));
    }

    public function userView()
    {

        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $usuario = User::join('areas', 'users.areas_id', 'areas.id')
                ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->select('users.*', 'areas.name as Areas', 'roles.name AS rol', 'users_has_status.status as status')
                ->get();
        } else {
            $usuario = User::join('areas', 'users.areas_id', 'areas.id')
                ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->where('users.areas_id', $user->areas_id)
                ->select('users.*', 'areas.name as Areas', 'roles.name AS rol', 'users_has_status.status as status')
                ->get();
        }

        return view('content/apps/user/app-user-list', compact('usuario'));
    }
}
