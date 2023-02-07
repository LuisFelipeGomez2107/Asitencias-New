<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportIndex(Request $request)
    {


        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $usuarios = User::join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->where('users_has_status.status', 1)
                ->where('roles.name', '!=', 'Admin')
                ->where('roles.name', '!=', 'Supervisor')
                ->select('users.name as name', 'users.id as id')
                ->get();
        } else {
            $usuarios = User::join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->where('users_has_status.status', 1)
                ->where('roles.name', '!=', 'Admin')
                ->where('roles.name', '!=', 'Supervisor')
                ->where('users.areas_id', $user->areas_id)
                ->select('users.name as name', 'users.id as id')
                ->get();
        }

        // $fI = date("Y-m-01");
        // $fF = date("Y-m-t");
        // Dates
        // foreach ($usuarios as $user) {
        //     $images = Images::join('users', 'users.id', 'images.user_id')
        //         ->whereBetween('images.created_at', [$fI, $fF])
        //         ->select('images.name', 'images.id as imageId', 'images.user_id', 'images.created_at')
        //         ->get();
        // }

        $imagenes = Images::whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')])->orderBy('id')
            ->get();


        $collection = new Collection;
        foreach ($imagenes as $imagen) {
            $collection->push([
                'id_imagen' => $imagen->id,
                'id_user' => $imagen->user_id,
                'created_at' => date('j', strtotime($imagen->created_at)),
                'all_date' => date('Y-m-d H:i:s', strtotime($imagen->created_at)),
                'date' => date('m-d', strtotime($imagen->created_at)),
                'Year' => date('Y-m', strtotime($imagen->created_at)),
            ]);
        }
        $collection = json_decode(json_encode($collection));


        $justificaciones = Justificaciones::whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')])->orderBy('id')
            ->get();

        $collectionjustificaciones = new Collection;
        foreach ($justificaciones as $justificacion) {
            $collectionjustificaciones->push([
                'id_imagen' => $justificacion->id,
                'id_user' => $justificacion->user_id,
                'created_at' => date('Y-m-d H:i:s', strtotime($justificacion->created_at)),
                'falta' => $justificacion->falta
            ]);
        }

        $collectionjustificaciones = json_decode(json_encode($collectionjustificaciones));
    

        $lastDayMonth = date('t', strtotime(date('Y-m-d')));
        $countMonths = 0;
        $export = new DashExport($usuarios, $lastDayMonth, $countMonths, $collection,$collectionjustificaciones);
        return Excel::download($export, 'Reporte_Asistencias.xlsx');
    }
    public function exportHistorial($requestDateInicio, $requestDateFinal, $requestArea,)
    {
       
        // $request->all();
        $user = Auth::user();
        $area = $requestArea;
        if ($user->hasRole('Admin')) {
            $usuarios = User::join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->where('users_has_status.status', 1)
                ->where('roles.name', '!=', 'Admin')
                ->where('roles.name', '!=', 'Supervisor')
                ->select('users.name as name', 'users.id as id')
                ->when($requestArea, function ($usuarios, $area) {
                    return $usuarios->where('users.areas_id', $area);
                }, function ($usuarios) {
                    // return $query->orderBy('name');
                })
                ->get();
        } else {
            $usuarios = User::join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', 'roles.id')
                ->join('users_has_status', 'users.id', 'users_has_status.user_id')
                ->where('users_has_status.status', 1)
                ->where('roles.name', '!=', 'Admin')
                ->where('roles.name', '!=', 'Supervisor')
                ->where('users.areas_id', $user->areas_id)
                ->select('users.name as name', 'users.id as id')
                ->get();
        }

        $dateI = date_format(date_create($requestDateInicio), "Y-m-d");
        $dateF = date_create($requestDateFinal);
        $dateInitial = $requestDateInicio;
        $area = $requestArea;
        $lastDayMonth = date('t', strtotime(date($requestDateFinal)));
        $start = new DateTime($requestDateInicio);
        $end =  new DateTime($requestDateFinal);
        $diff = $start->diff($end);
        $yearsInMonths = $diff->format('%r%y') * 12;
        $months = $diff->format('%r%m');
        $mes1 = date('Y-m', strtotime(date($requestDateInicio)));
        $mes2 = date('Y-m', strtotime(date($requestDateFinal)));
        if ($months == 0 && $diff->d < 28 && ($mes1 != $mes2)) {
            $months++;
            // dd($months);
        }
        $countMonths = $yearsInMonths + $months;
        $dI = date("n", strtotime($requestDateInicio));
        $dF = date("n", strtotime($requestDateFinal));

        $imagenes = Images::whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')])->orderBy('id')
        ->when($requestDateInicio, function ($imagenes) use ($requestDateInicio, $requestDateFinal) {

            $imagenes = Images::whereBetween('created_at', [("$requestDateInicio 00:00:00"), ("$requestDateFinal 23:59:59")])->orderBy('id');

            return $imagenes;
        })

        ->get();


    $collection = new Collection;
    foreach ($imagenes as $imagen) {
        $collection->push([
            'id_imagen' => $imagen->id,
            'id_user' => $imagen->user_id,
            'created_at' => date('j', strtotime($imagen->created_at)),
            'all_date' => date('Y-m-d H:i:s', strtotime($imagen->created_at)),
           'date' => date('m-d', strtotime($imagen->created_at)),
           'Year' => date('Y-m'  ,strtotime($imagen->created_at)),
        ]);
    }
    $collection = json_decode(json_encode($collection));
    
    $justificaciones = Justificaciones::whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')])->orderBy('id')
        ->when(($requestDateInicio != null), function ($justificaciones) use ($requestDateInicio, $requestDateFinal) {
            $justificaciones = Justificaciones::whereBetween('falta', [($requestDateInicio), ($requestDateFinal)])->orderBy('id');
            return $justificaciones;
        })
        ->get();
    $collectionjustificaciones = new Collection;
    foreach ($justificaciones as $justificacion) {
        $collectionjustificaciones->push([
            'id_imagen' => $justificacion->id,
            'id_user' => $justificacion->user_id,
            'created_at' => date('Y-m-d H:i:s', strtotime($justificacion->created_at)),
            'falta' => $justificacion->falta
        ]);
    }
    $collectionjustificaciones = json_decode(json_encode($collectionjustificaciones));
       

        $i = 0;
        $meses = array();
        $colors = array();
        while ($i <= $countMonths) {
            $mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"][$dI - 1];
            switch ($mes) {
                case 'Enero':
                    $colors = array('color' => '#0082ba', 'rgba' => 'rgba(0, 130, 186, 0.2)');
                    break;
                case 'Febrero':
                    $colors = array('color' => '#00cf79', 'rgba' => 'rgba(0, 207, 121, 0.2)');
                    break;
                case 'Marzo':
                    $colors = array('color' => '#fdc204', 'rgba' => 'rgba(253, 194, 4, 0.2)');
                    break;
                case 'Abril':
                    $colors = array('color' => '#d42a15', 'rgba' => 'rgba(212, 42, 21, 0.2)');
                    break;
                case 'Mayo':
                    $colors = array('color' => '#da72ec', 'rgba' => 'rgba(218, 114, 236, 0.2)');
                    break;
                case 'Junio':
                    $colors = array('color' => '#90a6a7', 'rgba' => 'rgba(144, 166, 167, 0.2)');
                    break;
                case 'Julio':
                    $colors = array('color' => '#98cc42', 'rgba' => 'rgba(152, 204, 66, 0.2)');
                    break;
                case 'Agosto':
                    $colors = array('color' => '#93d1ec', 'rgba' => 'rgba(147, 209, 236, 0.2)');
                    break;
                case 'Septiembre':
                    $colors = array('color' => '#580433', 'rgba' => 'rgba(88, 4, 51, 0.2)');
                    break;
                case 'Octubre':
                    $colors = array('color' => '#e8b1aa', 'rgba' => 'rgba(232, 177, 170, 0.2)');
                    break;
                case 'Noviembre':
                    $colors = array('color' => '#fa7800', 'rgba' => 'rgba(250, 120, 0, 0.2)');
                    break;
                case 'Diciembre':
                    $colors = array('color' => '#1e680f', 'rgba' => 'rgba(30, 104, 15, 0.2)');
                    break;
                default:
                    # code...
                    break;
            }
            $lastDayMonthI = date('t', strtotime(date($dateI)));
            $lastDayMonthI = intval($lastDayMonthI);
            $dateI = date("Y-m-d", strtotime($dateI . "+ 1 month"));
            if ($i == 0) {
                $primer = date("j", strtotime($requestDateInicio));
                $p = intval($primer);
                $porcionesI = explode("-", date("j", strtotime($requestDateInicio)));
                $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                if ($i == $countMonths) {
                    $porciones = explode("-", $requestDateFinal);
                    $fechaF = intval($porciones[2]);
                    $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $fechaF, 'primerDia' => $p, 'color' => $colors);
                } else {
                    $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                }
            } else {
                $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => 1, 'color' => $colors);
                if ($i == $countMonths) {
                    $porciones = explode("-", $requestDateFinal);
                    $fechaF = intval($porciones[2]);
                    $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $fechaF, 'primerDia' => 1, 'color' => $colors);
                } else {
                    $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => 1, 'color' => $colors);
                }
            }
            if ($dI == 12) {
                $dI = 1;
                $i++;
            } else {

                $dI++;
                $i++;
            }
        }
        $export = new DashExportHistorial($usuarios, $lastDayMonth, $countMonths, $meses,  $dateInitial, $requestDateInicio, $requestDateFinal, $requestArea,$collection, $collectionjustificaciones);
        return Excel::download($export, 'Reporte_Asistencias.xlsx');
    }
}

}
