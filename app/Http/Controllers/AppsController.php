<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Images;
use Illuminate\Http\Request;
use App\Models\Justificaciones;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Models\Role;


class AppsController extends Controller
{
    // invoice list App
    public function invoice_list()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/invoice/app-invoice-list', ['pageConfigs' => $pageConfigs]);
    }

    // invoice preview App
    public function invoice_preview()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/invoice/app-invoice-preview', ['pageConfigs' => $pageConfigs]);
    }

    // invoice edit App
    public function invoice_edit()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/invoice/app-invoice-edit', ['pageConfigs' => $pageConfigs]);
    }

    // invoice edit App
    public function invoice_add()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/invoice/app-invoice-add', ['pageConfigs' => $pageConfigs]);
    }

    // invoice print App
    public function invoice_print()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/invoice/app-invoice-print', ['pageConfigs' => $pageConfigs]);
    }

    // User List Page
    public function user_list(Request $request)
    {



        $requestDateInicio = date($request->dateInicio);
        $requestDateFinal = date($request->dateFinal);
        $requestArea = $request->area;
        $dateI = date_format(date_create($request->dateInicio), "Y-m-d");
        $dateF = date_create($request->dateFinal);
        $dateInitial = $request->dateInicio;
        $requestArea = $request->area;
        $requestpuesto = $request->puesto;
        $requestnombre = $request->nombre;

        $user = Auth::user();
        $usuarios = User::join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->join('users_has_status', 'users.id', 'users_has_status.user_id')
            ->where('users_has_status.status', 1)
            ->where('roles.name', '!=', 'Admin')
            ->where('roles.name', '!=', 'Supervisor')
            ->when($request->area, function ($usuarios, $area) {
                return $usuarios->where('users.areas_id', $area);
            })
            ->when($request->puesto, function ($usuarios, $puesto) {
                return $usuarios
                    ->where('roles.id', $puesto);
            })
            ->when($request->nombre, function ($usuarios, $nombre) {
                return $usuarios->where('users.name', 'like', "%" . $nombre . "%");
            })

            ->select('users.name as name', 'users.id as id')
            ->get();



        $fF = date("Y-m-t");


        if ($requestDateInicio != "") {
            $dia_fecha_inicial  = date("j", strtotime($requestDateInicio));
        } else {
            $dia_fecha_inicial = 1;
        }

        if ($requestDateFinal != "") {
            $dia_fecha_final  = date("j", strtotime($requestDateFinal));
        } else {
            $dia_fecha_final = date("d", strtotime($fF));
        }



        $imagenes = Images::whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')])->orderBy('id')
            ->when($request->dateInicio, function ($imagenes) use ($requestDateInicio, $requestDateFinal) {

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

        $start = new DateTime($request->dateInicio);
        $end =  new DateTime($request->dateFinal);
        $diff = $start->diff($end);
        $months = $diff->format('%r%m');
        $countMonths = $months;

        if ($requestDateInicio != "") {
            $dI = date("n", strtotime($request->dateInicio));
            $dF = date("n", strtotime($request->dateFinal));
            // dd($dF);
            $mes1 = date('Y-m', strtotime(date($request->dateInicio)));
            $mes2 = date('Y-m', strtotime(date($request->dateFinal)));
            if ($months == 0 && $diff->d < 28 && ($mes1 != $mes2)) {
                $months++;
            }


            $i = 0;
            $meses = array();
            $colors = array();
            $dateTest1 = date('Y', strtotime(date($mes1)));
            $dateTest2 = date('Y', strtotime(date($mes2)));
            if ($dateTest1 != $dateTest2) {
                while ($i <= $countMonths) {
                    $mes = [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ][$dI - 1];
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
                    if ($i == 0) {
                        $primer = date("j", strtotime($request->dateInicio));
                        $p = intval($primer);
                        $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                        if ($i == $countMonths) {
                            $porciones = explode("-", $request->dateFinal);
                            $fechaF = intval($porciones[2]);

                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $fechaF, 'primerDia' => $p, 'color' => $colors);
                        } else {
                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                        }
                    } else {
                        $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => 1, 'color' => $colors);
                        if ($i == $countMonths) {
                            $porciones = explode("-", $request->dateFinal);

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
            } else {
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
                    if ($i == 0) {
                        $primer = date("j", strtotime($request->dateInicio));
                        $p = intval($primer);
                        $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                        if ($i == $countMonths) {
                            $porciones = explode("-", $request->dateFinal);
                            $fechaF = intval($porciones[2]);

                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $fechaF, 'primerDia' => $p, 'color' => $colors);
                        } else {
                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => $p, 'color' => $colors);
                        }
                    } else {
                        $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => 1, 'color' => $colors);
                        if ($i == $countMonths) {
                            $porciones = explode("-", $request->dateFinal);

                            $fechaF = intval($porciones[2]);
                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $fechaF, 'primerDia' => 1, 'color' => $colors);
                        } else {
                            $meses[$i] = array('mes' => $dI, 'nombre' => $mes, 'ultimoDia' => $lastDayMonthI, 'primerDia' => 1, 'color' => $colors);
                        }
                    }
                    $dI++;
                    $i++;
                }
            }
            return view('/content/apps/user/app-user-list', compact('usuarios', 'dia_fecha_final', 'collection', 'collectionjustificaciones', 'countMonths', 'dia_fecha_inicial','meses' ,'requestDateInicio','requestArea','requestDateFinal'));
        }


        return view('/content/apps/user/app-user-list', compact('usuarios', 'dia_fecha_final', 'collection', 'collectionjustificaciones', 'countMonths', 'dia_fecha_inicial','requestDateInicio','requestArea','requestDateFinal'));
    }
        



    

    // User Account Page
    public function user_view_account()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/user/app-user-view-account', ['pageConfigs' => $pageConfigs]);
    }

    // User Security Page
    public function user_view_security()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/user/app-user-view-security', ['pageConfigs' => $pageConfigs]);
    }

    // User Billing and Plans Page
    public function user_view_billing()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/user/app-user-view-billing', ['pageConfigs' => $pageConfigs]);
    }

    // User Notification Page
    public function user_view_notifications()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/user/app-user-view-notifications', ['pageConfigs' => $pageConfigs]);
    }

    // User Connections Page
    public function user_view_connections()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/user/app-user-view-connections', ['pageConfigs' => $pageConfigs]);
    }


    // Chat App
    public function chatApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'chat-application',
        ];

        return view('/content/apps/chat/app-chat', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    // Calender App
    public function calendarApp()
    {
        $pageConfigs = [
            'pageHeader' => false
        ];

        return view('/content/apps/calendar/app-calendar', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    // Email App
    public function emailApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'email-application',
        ];

        return view('/content/apps/email/app-email', ['pageConfigs' => $pageConfigs]);
    }
    // ToDo App
    public function todoApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'todo-application',
        ];

        return view('/content/apps/todo/app-todo', [
            'pageConfigs' => $pageConfigs
        ]);
    }
    // File manager App
    public function file_manager()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'file-manager-application',
        ];

        return view('/content/apps/fileManager/app-file-manager', ['pageConfigs' => $pageConfigs]);
    }

    // Access Roles App
    public function access_roles()
    {
        $pageConfigs = ['pageHeader' => false,];

        return view('/content/apps/rolesPermission/app-access-roles', ['pageConfigs' => $pageConfigs]);
    }

    // Access permission App
    public function access_permission()
    {
        $pageConfigs = ['pageHeader' => false,];

        return view('/content/apps/rolesPermission/app-access-permission', ['pageConfigs' => $pageConfigs]);
    }

    // Kanban App
    public function kanbanApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'pageClass' => 'kanban-application',
        ];

        return view('/content/apps/kanban/app-kanban', ['pageConfigs' => $pageConfigs]);
    }

    // Ecommerce Shop
    public function ecommerce_shop()
    {
        $pageConfigs = [
            'contentLayout' => "content-detached-left-sidebar",
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Shop"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-shop', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Details
    public function ecommerce_details()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['link' => "/app/ecommerce/shop", 'name' => "Shop"], ['name' => "Details"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-details', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Wish List
    public function ecommerce_wishlist()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Wish List"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-wishlist', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Checkout
    public function ecommerce_checkout()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Checkout"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-checkout', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
