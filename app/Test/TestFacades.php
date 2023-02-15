<?php

namespace App\Test;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Images;
use App\Models\Justificaciones;
use App\Models\Tolerancia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TestFacades
{

    public function countImages($id_user, $date, $justificaciones, $area, $i, $collect, $collection)
    {


        $mes = date('m', strtotime($date));



        $dateY = date('Y-m-d', strtotime($collect->Year . '-' . $mes . '-' . $i));

        /*
               0  falta
               1 asistencias
               2 retardo
            -  3 usuario no creados
               4 Justiticación de retardo
               5 Justificación con falta
               6 falta por que solo se subio una evidencia
            */
        $horarios = Tolerancia::find($area)
            ->where('created_at', '<=', $date)
            ->first();


        $contadorImagenes = 0;
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);
                if ($dateY == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }

        // Validamos cuando tenemos dos Evidencias Fotograficas
        if ($contadorImagenes == 2) {
            global $Hora_In;
            $Hora_In = null;

            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {

                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($dateY == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        break;
                    }
                }
            }

            // Traemos la tabla de tolerancias para validar retardos


            // Validacion de la hora de Entrada con la Tolerancia Establecida
            $hora = date("h:i:s", strtotime($horarios->tolerancia));
            if ($Hora_In <= $hora) {

                return 1;
            } else {
                $portionDate = explode(' ', $date);
                // Verificamos si existen Justificaciones en la Base Justificamos el Dia
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return 1;
                        }
                    }
                }
                // Si no se cumplen las condiciones anteriores Colocamos Retardo
                return 2;
            }
        }

        // Validacion cuando encotramos una Evidencia Fotografica en la base de Datos
        if ($contadorImagenes == 1) {
            $dia_actual = date('Y-m-d');
            global $Hora_In;
            $Hora_In = null;
            global $dias;
            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {
                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($dateY == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        $dias = $portionAlldate[0];
                        break;
                    }
                }
            }
            // Si es igual a una Evidencia y nos encotramos en el dia actual Validamos
            if ($dia_actual == $dias) {
                $hora = date("h:i:s", strtotime($horarios->tolerancia));
                if ($Hora_In != null) {
                    // Si la hora de entrada es mayor a la toleracia
                    if ($Hora_In > $hora) {
                        return 2;
                    }
                    // Si estamos dentro del Rango de Tolerancia
                    if ($Hora_In <= $hora) {
                        return 1;
                    }
                }
            } else {
                // Verificamos si existen Justificaciones en la Base de datos
                $portionDate = explode(' ', $date);
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return 1;
                        }
                    }
                }
                // Si no tenemos Justificacion colocaremos solo una evidencia
                return 3;
            }
        }
        // Validamos cuando encontramos DOS imagenes
        if ($contadorImagenes > 2) {

            return 1;
        }
        // Cuando no encotremos evidencias Fotograficas colocaremos falta
        if ($contadorImagenes == 0) {

            return 0;
        }
    }



    public function countfalta($dia_corto, $collection, $date, $id_user, $Justificaciones)
    {



        $date = date('Y-m-' . $dia_corto);

        $dateGet = date("l", strtotime($date));

        // Validamos los dias de la semana si es sabado o domingo saltaremos los dias
        if ($dateGet == "Sunday") {
            return "-";
        }
        if ($dateGet == "Saturday") {
            return "-";
        }
        // Falta
        $portionDate = explode(' ', $date);
        $contadorImagenes = 0;
        // Contaremos la coleccion de imagenes
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);

                if ($portionDate[0] == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }
        // Validaremos si las evidencias Fotograficas son igual a 0
        if ($contadorImagenes == 0) {
            // Validaremos si la fecha es mayor a la fecha actual colocaremos los campos vacios
            if ($portionDate[0] > date('Y-m-d')) {
                return "-";
            } else {
                // Verificamos si existen evidencias fotograficas
                foreach ($Justificaciones as $justificacion) {

                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $date) {
                            return 1;
                        }
                    }
                }
                return 0;
            }
        }

        // Falta
        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones

        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones
    }

    public function countfaltaYear($i, $collection, $id_user, $Justificaciones, $mes, $collect)
    {


        $dateY = date('Y-m-d', strtotime($collect->Year . '-' . $mes['mes'] . '-' . $i));

        $dateGet = date("l", strtotime($dateY));

        // Validamos los dias de la semana si es sabado o domingo saltaremos los dias
        if ($dateGet == "Sunday") {
            return "-";
        }
        if ($dateGet == "Saturday") {
            return "-";
        }
        // Falta

        $contadorImagenes = 0;
        // Contaremos la coleccion de imagenes
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);

                if ($dateY == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }
        // Validaremos si las evidencias Fotograficas son igual a 0
        if ($contadorImagenes == 0) {

            // Validaremos si la fecha es mayor a la fecha actual colocaremos los campos vacios
            if ($dateY > date('Y-m-d')) {

                return "-";
            } else {
                // Verificamos si existen evidencias fotograficas
                foreach ($Justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $dateY) {
                            // dd($dateY);
                            return 1;
                        }
                    }
                }

                return 0;
            }
        }
        // Falta
        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones

        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones
    }




    public function returnView($date, $user_id)
    {

        $start = date($date . " " . '00:00:00');
        $end = date($date . " " . '23:59:59');
        $images = Images::whereBetween('created_at', [$start, $end])
            ->where('user_id', $user_id)
            ->orderBy('id')
            ->get();
        return count($images);
    }
    
    // Conteos
    public function countStatusMonth($user_id)
    {
        return $user_id;
    }
    //   Conteos
    public function testMethod($id_user, $date, $collection, $justificaciones, $area)
    {

        /*
               0  falta
               1 asistencia
               2 retardo
            -  3 usuario no creado
               4 Justiticación de retardo
               5 Justificación con falta
               6 falta por que solo se subio una evidencia
            */
        $horarios = Tolerancia::find($area)
            ->where('created_at', '<=', $date)
            ->first();






        $portionDate = explode(' ', $date);
        $contadorImagenes = 0;
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);
                if ($portionDate[0] == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }
        // Validamos cuando tenemos dos Evidencias Fotograficas
        if ($contadorImagenes == 2) {
            global $Hora_In;
            $Hora_In = null;
            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {

                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($portionDate[0] == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        break;
                    }
                }
            }




            // Validacion de la hora de Entrada con la Tolerancia Establecida
            $hora = date("h:i:s", strtotime($horarios->tolerancia));
            if ($Hora_In <= $hora) {

                echo '<td class="text-center" ><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            } else {
                $portionDate = explode(' ', $date);
                // Verificamos si existen Justificaciones en la Base Justificamos el Dia
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return '<td class="text-center"><i class="fa-solid fa-check-double showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-userId=' . $id_user . ' data-date=' . $date . '></i></td>';
                        }
                    }
                }
                // Si no se cumplen las condiciones anteriores Colocamos Retardo
                return '<td class="text-center"><i class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"  data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            }
        }

        // Validacion cuando encotramos una Evidencia Fotografica en la base de Datos
        if ($contadorImagenes == 1) {
            $dia_actual = date('Y-m-d');
            global $Hora_In;
            $Hora_In = null;
            global $dias;
            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {
                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($portionDate[0] == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        $dias = $portionAlldate[0];
                        break;
                    }
                }
            }
            // Si es igual a una Evidencia y nos encotramos en el dia actual Validamos
            if ($dia_actual == $dias) {
                $hora = date("h:i:s", strtotime($horarios->tolerancia));
                if ($Hora_In != null) {
                    // Si la hora de entrada es mayor a la toleracia
                    if ($Hora_In > $hora) {
                        return '<td class="text-center"><i class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"  data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                    }
                    // Si estamos dentro del Rango de Tolerancia
                    if ($Hora_In <= $hora) {
                        echo '<td class="text-center" ><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                    }
                } else {
                    echo "<td class='text-center' ><i class='fa-solid fa-xmark justificafalta' data-userId='.$id_user.' data-date='.$date.' style='color:red;' id='absence-icon'></i></td>";
                }
            } else {
                // Verificamos si existen Justificaciones en la Base de datos
                $portionDate = explode(' ', $date);
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return '<td class="text-center"><i class="fa-solid fa-check-double showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                        }
                    }
                }
                // Si no tenemos Justificacion colocaremos solo una evidencia
                echo '<td class="text-center"><i class="fas fa-exclamation-circle fas-solid showModal justificafalta" style="color:#ff8c00; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            }
        }
        // Validamos cuando encontramos DOS imagenes
        if ($contadorImagenes > 2) {

            echo '<td class="text-center"><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-userId=' . $id_user . ' data-date=' . $date . '></i></td>';
        }
        // Cuando no encotremos evidencias Fotograficas colocaremos falta
        if ($contadorImagenes == 0) {

            echo '<td class="first-col text-center"  ><i class="fa-solid fa-xmark justificafalta" style="color:red; cursor:pointer;" data-toggle="modal" data-target="#modalJustificarFaltas" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
        }
    }



    public function faltametodo($dia_corto, $collection, $date, $id_user, $Justificaciones)
    {





        $date = date('Y-m-' . $dia_corto);

        $dateGet = date("l", strtotime($date));

        // Validamos los dias de la semana si es sabado o domingo saltaremos los dias
        if ($dateGet == "Sunday") {
            return "<td class='text-center' data-date='$date'>-</td>";
        }
        if ($dateGet == "Saturday") {
            return "<td class='text-center' data-date='$date'>-</td>";
        }
        // Falta
        $portionDate = explode(' ', $date);
        $contadorImagenes = 0;
        // Contaremos la coleccion de imagenes
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);

                if ($portionDate[0] == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }
        // Validaremos si las evidencias Fotograficas son igual a 0
        if ($contadorImagenes == 0) {
            // Validaremos si la fecha es mayor a la fecha actual colocaremos los campos vacios
            if ($portionDate[0] > date('Y-m-d')) {
                return "<td class='text-center' data-date='$date'>-</td>";
            } else {
                // Verificamos si existen evidencias fotograficas
                foreach ($Justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $date) {
                            return '<td class="text-center"><i class="fas fa-calendar-check showJustificarFalta" style="color:#4fc341; cursor:pointer;" data-user-id=' . $id_user . ' data-date=' . $date . ' data-bs-toggle="modal" data-bs-target="#modalShowJustificarFaltas"></i></td>';
                        }
                    }
                }
                return "<td class='text-center' ><i class='fa-solid fa-xmark justificafalta' style='color:red; cursor:pointer' data-toggle='modal';  data-target='#modalJustificarFaltas'  id='absence-icon'data-userid='$id_user' data-date='$date'></i></td>";
            }
        } else {
            // Si no existe ninguna de las condiciones anteriores colocaremos faltametodo
            return "<td class='text-center'><i class='fa-solid fa-xmark justificafalta' style='color:red;' id='absence-icon' data-userId='.$id_user.' data-date='$date'></i></td>";
        }

        // Falta
        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones
        return "<td class='text-center' data-date='$date'>ROR</td>";
        // En caso de que el created at sea nulo no aplicara correctamente las asignaciones
    }


    // En Estos metodos retornamos las validaciones pero con colores rgba para distinguir los meses desplegados

    public function testMethodColors($id_user, $date, $collection, $justificaciones, $item, $area)
    {

        /*
               0  falta
               1 asistencia
               2 retardo
            -  3 usuario no creado
               4 Justiticación de retardo
               5 Justificación con falta
               6 falta por que solo se subio una evidencia
            */

        $horarios = Tolerancia::find($area)
            ->where('created_at', '<=', $date)
            ->first();


        $portionDate = explode(' ', $date);
        $contadorImagenes = 0;
        foreach ($collection as $collect) {
            if ($id_user == $collect->id_user) {
                $portionAlldate = explode(' ', $collect->all_date);
                if ($portionDate[0] == $portionAlldate[0]) {
                    $contadorImagenes++;
                }
            }
        }
        // Validamos cuando tenemos dos Evidencias Fotograficas
        if ($contadorImagenes == 2) {
            global $Hora_In;
            $Hora_In = null;
            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {

                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($portionDate[0] == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        break;
                    }
                }
            }

            // Validacion de la hora de Entrada con la Tolerancia Establecida

            $hora = date("h:i:s", strtotime($horarios->tolerancia));
            if ($Hora_In <= $hora) {

                echo '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            } else {
                $portionDate = explode(' ', $date);
                // Verificamos si existen Justificaciones en la Base Justificamos el Dia
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check-double showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-userId=' . $id_user . ' data-date=' . $date . '></i></td>';
                        }
                    }
                }
                // Si no se cumplen las condiciones anteriores Colocamos Retardo
                return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"  data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            }
        }

        // Validacion cuando encotramos una Evidencia Fotografica en la base de Datos
        if ($contadorImagenes == 1) {
            $dia_actual = date('Y-m-d');
            global $Hora_In;
            $Hora_In = null;
            global $dias;
            foreach ($collection as $collect) {
                if ($id_user == $collect->id_user) {
                    $portionAlldate = explode(' ', $collect->all_date);
                    if ($portionDate[0] == $portionAlldate[0]) {
                        $Hora_In = $portionAlldate[1];
                        $dias = $portionAlldate[0];
                        break;
                    }
                }
            }
            // Si es igual a una Evidencia y nos encotramos en el dia actual Validamos
            if ($dia_actual == $dias) {
                $hora = date("h:i:s", strtotime($horarios->tolerancia));
                if ($Hora_In != null) {
                    // Si la hora de entrada es mayor a la toleracia
                    if ($Hora_In > $hora) {
                        return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"  data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                    }
                    // Si estamos dentro del Rango de Tolerancia
                    if ($Hora_In <= $hora) {
                        echo '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '" ><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                    }
                } else {
                    echo '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-xmark justificafalta" data-userId=' . $id_user . ' data-date=' . $date . ' style="color:red;" id="absence-icon"></i></td>';
                }
            } else {
                // Verificamos si existen Justificaciones en la Base de datos
                $portionDate = explode(' ', $date);
                foreach ($justificaciones as $justificacion) {
                    if ($justificacion->id_user == $id_user) {
                        if ($justificacion->falta == $portionDate[0]) {
                            return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check-double showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
                        }
                    }
                }
                // Si no tenemos Justificacion colocaremos solo una evidencia
                echo '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fas fa-exclamation-circle fas-solid showModal justificafalta" style="color:#ff8c00; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
            }
        }
        // Validamos cuando encontramos DOS imagenes
        if ($contadorImagenes > 2) {

            echo '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-userId=' . $id_user . ' data-date=' . $date . '></i></td>';
        }
        // Cuando no encotremos evidencias Fotograficas colocaremos falta
        if ($contadorImagenes == 0) {

            echo '<td class="first-col text-center" style="background-color:' . $item['color']['rgba'] . '"  ><i class="fa-solid fa-xmark justificafalta" style="color:red; cursor:pointer;" data-toggle="modal" data-target="#modalJustificarFaltas" data-user-id=' . $id_user . ' data-date=' . $date . '></i></td>';
        }
    }

    public function faltametodoColors($dia_corto, $collection, $date, $id_user, $Justificaciones, $item)
    {



        foreach ($item as $mes) {

            foreach ($collection as $collect) {

                $dateY = date('Y-m-d', strtotime($collect->Year . '-' . $mes . '-' . $dia_corto));

                $dateGet = date("l", strtotime($dateY));



                // Validamos los dias de la semana si es sabado o domingo saltaremos los dias
                if ($dateGet == "Sunday") {
                    return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '" data-date=' . $date . '>-</td>';
                }
                if ($dateGet == "Saturday") {
                    return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '" data-date=' . $date . '>-</td>';
                }
                // Falta

                $contadorImagenes = 0;
                // Contaremos la coleccion de imagenes
                foreach ($collection as $collect) {

                    if ($id_user == $collect->id_user) {
                        $portionAlldate = explode(' ', $collect->all_date);

                        if ($dateGet == $portionAlldate[0]) {

                            $contadorImagenes++;
                        }
                    }
                }


                // Validaremos si las evidencias Fotograficas son igual a 0
                if ($contadorImagenes == 0) {
                    // Validaremos si la fecha es mayor a la fecha actual colocaremos los campos vacios

                    if ($dateY > date('Y-m-d')) {
                        return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '" data-date=' . $date . '>-</td>';
                    } else {
                        // Verificamos si existen evidencias fotograficas
                        foreach ($Justificaciones as $justificacion) {
                            if ($justificacion->id_user == $id_user) {
                                if ($justificacion->falta ==  $dateY) {
                                    return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fas fa-calendar-check showJustificarFalta" style="color:#4fc341; cursor:pointer;" data-user-id=' . $id_user . ' data-date=' . $date . ' data-bs-toggle="modal" data-bs-target="#modalShowJustificarFaltas"></i></td>';
                                }
                            }
                        }
                        return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '" ><i class="fa-solid fa-xmark justificafalta" style="color:red; cursor:pointer" data-toggle="modal";  data-target="#modalJustificarFaltas"  id="absence-icon" data-userid=' . $id_user . ' data-date=' . $dateY . '></i></td>';
                    }
                } else {
                    // Si no existe ninguna de las condiciones anteriores colocaremos faltametodo
                    return '<td class="text-center" style="background-color:' . $item['color']['rgba'] . '"><i class="fa-solid fa-xmark justificafalta" style="color:red; cursor:pointer" data-toggle="modal";  data-target="#modalJustificarFaltas"  id="absence-icon" data-userid=' . $id_user . ' data-date=' .  $dateY . '></i></td>';
                }

                // Falta
                // En caso de que el created at sea nulo no aplicara correctamente las asignaciones
                return '<td class="text-center" \>ROR</td>';
                // En caso de que el created at sea nulo no aplicara correctamente las asignaciones)

            }
        }
    }
}
