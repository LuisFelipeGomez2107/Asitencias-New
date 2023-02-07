@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Admin')
@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
    <style>
        .static {
            position: sticky;
            left: 0;
            background-color: white;
        }

        tbody tr .static {
            background-color: white;
        }

        thead tr .static {
            background-color: white;
        }

        .table {
            max-width: 50% !important;

        }
    </style>
    <!-- Dashboard Analytics Start -->
    <section id="dashboard-analytics">
        @php
            $user = Auth::user();
        @endphp
        {{-- NavBar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/icons/logo-fay.png') }}" alt="" width="140" height="60">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link @if (Request::url() == 'admin') {{ 'active' }} @endif"
                                aria-current="1page" href="{{ route('dashboard-analytics') }}">Dashboard</a>
                        </li>


        </nav>
        <main class="flex-shrink-1">
            <div class="container-fluid flex-wrap mt-3">
                {{-- Simbologia de la app --}}
                <div class="d-flex flex-wrap " id="symbol">
                    <div class="d-flex flex-wrap flex-row-reverse bd-highlight">
                        <p>
                            <pre><span>  Solo una Evidencia | </span> </pre><i class="fas fa-exclamation-circle fas-solid showModal"
                                style="color:#ff8c00;" id="evidence-icon"></i>
                        </p>
                    </div>
                    <p>
                    <div class="d-flex flex-wrap flex-row-reverse bd-highlight">
                        <p>
                            <pre><span>  Falta | </span> </pre><i class="fa-solid fa-xmark" style="color:red;"
                                id="absence-icon"></i>
                        </p>
                    </div>
                    <p>
                    <div class="d-flex flex-wrap flex-row-reverse bd-highlight">
                        <p>
                            <pre><span>  Retardo | </span></pre> <i class="fa-solid fa-check" style="color:#ff9c00;"
                                id="time-delay-icon"></i>
                        </p>
                    </div>
                    <p>
                    <div class="d-flex flex-wrap flex-row-reverse bd-highlight">

                        <p>
                            <pre><span> Asistencia | </span></pre> <i class="fa-solid fa-check" style="color:#4fc341;"
                                id="attendance-icon"></i>
                        </p>


                    </div>
                </div>

            </div>

            {{-- Filtros de consulta --}}
            <div class="flex-wrap" id="form-date">
                <form action="{{ route('dashboard-analytics') }}" id="form" method="GET">

                    <div class="row">
                        <div class="col-6 col-lg-4">
                            <div class="form-group">
                                <label for="dateInicio">Inicio</label>
                                <input type="date" class="form-control" id="dateInicio" name="dateInicio">
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="form-group">
                                <label for="dateFinal">Final</label>
                                <input type="date" class="form-control" id="dateFinal" name="dateFinal">
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="nombre">
                            </div>
                        </div>

                        <div class="col-4 col-lg-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Puesto</label>
                                <select name="puesto" id="puestoSelect" class="form-control">
                                    <option value=""> Seleccionar </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4 col-lg-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Área</label>
                                <select name="area" id="areasSelect" class="form-control">
                                    <option value=""> Seleccionar </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-1 d-flex align-items-end">
                            <div class="form-group ">
                                <button type="submit" class="btn btn-primary" id="enviar">Buscar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{-- Exportar datos a EXCEL --}}
            <div class="d-flex flex-row-reverse bd-highlight" id="btn-excel">
                @if ($requestDateInicio != '')
                    @php
                        if ($requestArea == null) {
                            $requestArea = 0;
                        } else {
                            $requestArea = $requestArea;
                        }
                    @endphp
                    <a href="{{ route('export.excel.dashHistorial', ['requestDateInicio' => $requestDateInicio, 'requestDateFinal' => $requestDateFinal, 'requestArea' => $requestArea]) }}"
                        class="btn btn-primary btn-lg active" role="button" aria-pressed="true"
                        title="Descargar Reporte Excel"><i class="fas fa-arrow-circle-down"></i></a>
                @else
                    <a href="{{ route('export.excel.dashIndex') }}" class="btn btn-primary btn-lg active" role="button"
                        aria-pressed="true" title="Descargar Reporte Excel"><i class="fas fa-arrow-circle-down"></i></a>
                @endif

            </div>

            {{-- vista meses  --}}
            <div class="mt-3">
                @if (isset($meses))
                    <div>
                        <div class="col-6">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header text-center" id="panelsStayOpen-headingThree">
                                        <button class="accordion-button collapsed text-center" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                            Mes
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="panelsStayOpen-headingThree">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach ($meses as $item)
                                                    <div class="col-4 months" style="cursor:pointer;"
                                                        data-value="{{ $item['nombre'] }}">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <span id="color_front"
                                                                    style="border-radius: 50%;width: 20px;height: 20px;display:inline-block;background-color:{{ $item['color']['color'] }};"></span>
                                                            </div>
                                                            <div class="col-10">{{ $item['nombre'] }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{-- tabla dashboard --}}
            <div class="table-responsive" style="max-with: 400px; overflow-x: auto;">
                <table class="table ">
                    <thead>
                        <tr>
                            <th class="static" scope="col">Nombre</th>

                            @if (isset($countMonths))
                                @if (isset($meses))
                                    @php
                                        $contador = 1;
                                        $rcv = 0;
                                        
                                    @endphp
                                    @foreach ($meses as $item)
                                        @php
                                            //  dd($meses);
                                        @endphp
                                        @for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++)
                                            @if ($contador == 1)
                                                @if ($i == 1)
                                                    <th style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;"
                                                        class="first-col">{{ $i }}</th>
                                                @elseif($i == $item['ultimoDia'])
                                                    <th style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;"
                                                        id="{{ $item['nombre'] }}">{{ $i }}</th>
                                                @else
                                                    <th
                                                        style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;">
                                                        {{ $i }}</th>
                                                @endif
                                            @else
                                                @if ($i == 1)
                                                    <th
                                                        style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;">
                                                        {{ $i }}</th>
                                                @elseif($i == $item['ultimoDia'])
                                                    <th style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;"
                                                        id="{{ $item['nombre'] }}">{{ $i }}</th>
                                                @else
                                                    <th
                                                        style="color:{{ $item['color']['color'] }}; border-bottom-width: medium;">
                                                        {{ $i }}</th>
                                                @endif
                                            @endif
                                        @endfor
                                        @php
                                            $contador++;
                                            $rcv++;
                                        @endphp
                                    @endforeach
                                @else
                                    @for ($i = $dia_fecha_inicial; $i <= $dia_fecha_final; $i++)
                                        <th class="static" scope="col"> {{ $i }} </th>
                                    @endfor
                                @endif
                            @endif

                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td class="static" scope="row">{{ $usuario->name }}</td>

                                @php
                                    // if ($countMonths != 0) {
                                    if ($requestDateInicio != '') {
                                        $contador = 1;
                                        $rcv = 0;
                                    
                                        foreach ($meses as $item) {
                                            for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++) {
                                                // dd($item);
                                                $date = date('Y-' . $item['mes'] . '-' . $i);
                                                $date = date('m-d', strtotime($date));
                                                // dd($date);
                                    
                                                $j = 0;
                                                $flag = false;
                                                $count = count($collection) - 1;
                                    
                                                foreach ($collection as $collect) {
                                                    if ($collect->date == $date) {
                                                        if ($collect->id_user == $usuario->id) {
                                                            echo TestFacades::testMethodColors($usuario->id, $collect->all_date, $collection, $collectionjustificaciones, $item, $usuario->id_area);
                                                            $flag = true;
                                                            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                                                            break;
                                                        }
                                                    }
                                                    if ($j == $count && $flag == false) {
                                                        $a = 0;
                                                        if ($i < 10) {
                                                            $a = '0' . $i;
                                                        } else {
                                                            $a = $i;
                                                        }
                                                        echo TestFacades::faltametodoColors($a, $collection, $collect->all_date, $usuario->id, $collectionjustificaciones, $item);
                                                    }
                                                    $j++;
                                                }
                                            }
                                        }
                                    } else {
                                        for ($i = $dia_fecha_inicial; $i <= $dia_fecha_final; $i++) {
                                            // echo "<td >";
                                            $j = 0;
                                            $flag = false;
                                            $count = count($collection) - 1;
                                    
                                            foreach ($collection as $collect) {
                                                if ($collect->created_at == $i) {
                                                    if ($collect->id_user == $usuario->id) {
                                                        echo TestFacades::testMethod($usuario->id, $collect->all_date, $collection, $collectionjustificaciones, $usuario->id_area);
                                                        $flag = true;
                                                        break;
                                                    }
                                                }
                                                if ($j == $count && $flag == false) {
                                                    $a = 0;
                                                    if ($i < 10) {
                                                        $a = '0' . $i;
                                                    } else {
                                                        $a = $i;
                                                    }
                                                    echo TestFacades::faltametodo($a, $collection, $collect->all_date, $usuario->id, $collectionjustificaciones);
                                                }
                                                $j++;
                                            }
                                        }
                                    }
                                    
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            </div>
        </main>
{{-- Modal Acciones --}}
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Evidencias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Acciones --}}
    {{-- Nodal Justificación Faltas --}}

    <div class="modal fade" id="modalJustificarFaltas" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Justificar</h5>
                </div>
                <div class="modal-body faltas">
                    <div class="justificarFalta">
                        <form id='form' action='{{ route('falta.subir.Justificacion') }}' method='POST'
                            enctype='multipart/form-data'>
                            <input type='hidden' name='_token' value='{{ csrf_token() }}' />

                            <div class='mb-3'>
                                <p class='text-center'>Justificar Falta</p>
                                <input type='hidden' value='' name='user_id' id="user_id">
                                <input type='hidden' value='' name='date' id="date">
                                <input class='form-control' type='file' name='files[]' id='formFile'
                                    accept='image/*,.pdf' multiple>
                            </div>
                            <div class="mb-3">
                                <small>Justificar por Vacaciones: </small><i
                                    class="fas fa-toggle-off swtichJustificarFaltas" style="cursor: pointer;"></i>
                                <input type="checkbox" name="checkJustifyVacaciones" id="checkJustifyVacaciones"
                                    style="display: none;">
                            </div>
                            <div class='mb-3'>
                                <label for='exampleFormControlTextarea1' class='form-label'>Comentarios</label>
                                <textarea class='form-control' name='comentario' id='exampleFormControlTextarea1' rows='3'></textarea>
                                <div class='col-auto'>
                                    <button type='submit' class='btn btn-primary mt-3'>Subir Evidencias</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Nodal Justificación Faltas --}}
    {{-- Modal Mostrar Faltas Justificadas --}}



    <div class="modal fade" id="modalShowJustificarFaltas" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Comprobantes de Justificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body showFaltasContainer">
                    <div id="showjustificarFalta">

                    </div>
                </div>
            </div>
        </div>
    </div>
   {{-- Modal Mostrar Faltas Justificadas --}}
   <script>
    $(function() {
        let route = "{{ route('areas.get') }}";
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: route,
            success: function(data) {
                data.forEach(function(element) {
                    $("#areasSelect").append(
                        "<option class='super' value='" + element.id +
                        "'>" + element.name + "</option>")
                });
            },
            error: function() {
                console.log(data);
            }
        })
    });
    $(function() {
        let route = "{{ route('puesto.get') }}";
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: route,
            success: function(data) {
                data.forEach(function(element) {
                    $("#puestoSelect").append(
                        "<option class='super' value='" + element.id +
                        "'>" + element.name + "</option>")
                });
            },
            error: function() {
                console.log(data);
            }
        });
        var form = "#form";
        $(form).validate({
            rules: {
                dateInicio: {
                    required: true
                },
                dateFinal: {
                    required: true,
                },
            },
            messages: {
                dateInicio: {
                    required: " * Ingrese un Fecha Inicial por favor"
                },
                dateFinal: {
                    required: " * Ingrese un Fecha Final por favor"
                },

            }
        });
    })
    $(".months").on('click', function() {
        var element = document.getElementById($(this).data('value'));
        element.scrollIntoView();
    })
    $(".showModal").on('click', function() {
        user_id = $(this).data("userId")
        date = $(this).data("date")
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: "{{ route('consultarImagenes') }}",
            data: {
                user_id: user_id,
                date: date
            },
            beforeSend: function() {
                $(".modal-body").append(
                    '<div class="text-center spinner-loading"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                )
            },
            success: function(data) {
                $(".spinner-loading").remove();
                data.forEach(function(element) {
                    // console.log(element);
                    if (element.justificacion == 1) {
                        $(".modal-body").append(
                            "<div class='card mb-3 card-imgm'><img src='" +
                            element.url +
                            "' class='card-img-top' alt='...'><div class='card-body'><h5 class='card-title'>Hora: " +
                            element.created_at + "</h5><p class='card-text'>" + element
                            .address +
                            "</p><p class='card-text d-flex justify-content-between'><i class='fa-solid fa-toggle-off switch' id='switcher" +
                            element.id +
                            "' style='font-size:20px; cursor:pointer;' data-id ='" +
                            element
                            .id +
                            "'></i><i class='fas fa-file-alt' style='cursor:pointer;' data-img ='" +
                            element.id + "' data-user ='" + element.user_id +
                            "'></i></p></div><div class='container justify' id='switch-" +
                            element.id +
                            "' style='display:none;'><form id='form' action='{{ route('subirJustificacion') }}' method='POST' enctype='multipart/form-data'><input type='hidden' name='_token' value='{{ csrf_token() }}' /><div class='mb-3'><p class='text-center'>Justificar</p><input type='hidden' value='" +
                            element.fecha +
                            "' name='date'><input type='hidden' value='" +
                            element.id +
                            "' name='imagen_id'><input type='hidden' value='" +
                            element.user_id +
                            "' name='user_id'><input class='form-control' type='file' name='files[]' id='formFile' accept='image/*,.pdf' multiple></div><div class='mb-3'><label for='exampleFormControlTextarea1' class='form-label'>Comentarios</label><textarea class='form-control' name='comentario' id='exampleFormControlTextarea1' rows='3'></textarea><div class='col-auto'><button type='submit' class='btn btn-primary mt-3'>Subir Evidencias</button></div></div></div></form><div class='container' id='divJustificantes' style='display:none;'><p class='text-center'>Justificacion</p><div id='justificantes'><div class='row'></div></div></div></div>"
                        )
                    } else {
                        $(".modal-body").append(
                            "<div class='card mb-3 card-imgm'><img src='" +
                            element.url +
                            "' class='card-img-top' alt='...'><div class='card-body'><h5 class='card-title'>Hora: " +
                            element.created_at + "</h5><p class='card-text'>" + element
                            .address +
                            "</p><p class='card-text'><i class='fa-solid fa-toggle-off switch' id='switcher" +
                            element.id +
                            "' style='font-size:20px; cursor:pointer;' data-id ='" +
                            element
                            .id +
                            "'></i></p></div><div class='container justify' id='switch-" +
                            element.id +
                            "' style='display:none;'><form id='form' action='{{ route('subirJustificacion') }}' method='POST' enctype='multipart/form-data'><input type='hidden' name='_token' value='{{ csrf_token() }}' /><div class='mb-3'><p class='text-center'>Justificar</p><input type='hidden' value='" +
                            element.fecha +
                            "' name='date'><input type='hidden' value='" +
                            element.id +
                            "' name='imagen_id'><input type='hidden' value='" +
                            element.user_id +
                            "' name='user_id'><input class='form-control' type='file' name='files[]' id='formFile' accept='image/*,.pdf' multiple></div><div class='mb-3'><label for='exampleFormControlTextarea1' class='form-label'>Comentarios</label><textarea class='form-control' name='comentario' id='exampleFormControlTextarea1' rows='3'></textarea><div class='col-auto'><button type='submit' class='btn btn-primary mt-3'>Subir Evidencias</button></div></div></div></form></div>"
                        )
                    }

                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    })
    $(".justificafalta").on('click', function() {
        user_id = $(this).data("userid");
        date = $(this).data("date");
        $("#user_id").val(user_id);
        $("#date").val(date);
    });
    $(".showJustificarFalta").on('click', function() {
        user_id = $(this).data('userId');
        date = $(this).data('date');
        $.ajax({
            type: "GET",
            url: "{{ route('mostra.faltas') }}",
            data: {
                id: user_id,
                date: date
            },
            success: function(response) {
                response.forEach(function(element) {
                    // console.log(element);
                    porcion = element.name.split('.');
                    if (porcion[1] == 'pdf') {
                        $("#showjustificarFalta").append(
                            '<iframe class="objectFakta" src="' +
                            element.url +
                            '" style="width:100%; height:400px; margin:5px;" frameborder="0" ></iframe>'
                        );
                    } else {
                        $("#showjustificarFalta").append(
                            '<div class="col objectFakta" style="margin:5px;"><div class="card card-justify" style="width: 18rem;"><img class="card-img-top" src="' +
                            element.url +
                            '" alt="Card Justificación" style="width:100%;" ></div></div>'
                        );
                    }
                    $("#showjustificarFalta").append(
                        `<div class="col objectFakta"><p>${element.comentario}</p></div>`
                    );

                });
            }
        });
    });
    $(".showJustificarFalta").on('click', function() {
        for (let index = 0; index <= $(".objectFakta").length; index++) {
            $("#showjustificarFalta").children(".objectFakta").remove();
        }

    });
    // Sanitizacion de Imganes para Modal Faltas
    $(".fa-xmark").on('click', function() {

        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    $(".fa-check-double").on('click', function() {
        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    $(".fa-exclamation-circle").on('click', function() {
        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    // Sanitizacion de Imganes para Modal Faltas

    // Sanitizacion de Imganes para Modal Evidencias
    $(".fa-check").on('click', function() {

        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    $(".fa-check-double").on('click', function() {
        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    $(".fa-exclamation-circle").on('click', function() {
        for (let index = 0; index <= $(".card-imgm").length; index++) {
            $(".modal-body").children(".card-imgm").remove();
        }
        for (let index = 0; index <= $(".card-justif").length; index++) {
            $("#divJustificantes").children(".card-justif").remove();
        }
    });
    // Sanitizacion de Imganes para Modal Evidencias
    // Show div Justify
    $(document).on("click", ".switch", function() {
        id = $(this).data('id');
        var isVisible = $("#switch-" + id).is(":visible");
        if (isVisible == false) {
            $("#switcher" + id).addClass("fa-toggle-on").removeClass("fa-toggle-off");
        } else {
            $("#switcher" + id).addClass("fa-toggle-off").removeClass("fa-toggle-on");
        }
        $("#switch-" + id).toggle("slow")
    });
    $(document).on('click', '.fa-file-alt', function() {
        id = $(this).data('img')
        userId = $(this).data('user')
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: "{{ route('consultarJustificaciones') }}",
            data: {
                image_id: id,
                user_id: userId
            },
            beforeSend: function() {
                $("#divJustificantes").toggle("slow")
                $("#justificantes").append(
                    '<div class="text-center spinner-loading"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                )
            },
            success: function(data) {
                // console.log(data);
                $(".spinner-loading").remove();

                data.forEach(function(element) {
                    // console.log(element);
                    porcion = element.name.split('.');
                    if (porcion[1] == 'pdf') {
                        $("#justificantes .row").append('<iframe src="' + element.url +
                            '" style="width:100%; height:400px; margin:5px;" frameborder="0" ></iframe>'
                        );
                    } else {
                        $("#justificantes .row").append(
                            '<div class="col" style="margin:5px;"><div class="card card-justify" style="width: 18rem;"><img class="card-img-top" src="' +
                            element.url +
                            '" alt="Card Justificación" style="width:100%;" ><div class="card-body"><h5 class="card-title">Justificado por:' +
                            element.userJ + ' </h5><p class="card-text">' + element
                            .comentario + '</p></div></div></div>');
                    }

                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    $(document).on('click', ".fa-file-alt", function() {
        for (let index = 0; index <= $(".card-justify").length; index++) {
            $("#divJustificantes").children(".card-justify").remove();
        }
    });

    // Show div Justify
    $(document).on('click', '.swtichJustificarFaltas', function() {
        if ($(this).hasClass('fa-toggle-off')) {
            $(this).removeClass('fa-toggle-off')
            $(this).addClass('fa-toggle-on')
            $("#checkJustifyVacaciones").prop('checked', true);
        } else {
            $(this).removeClass('fa-toggle-on')
            $(this).addClass('fa-toggle-off')
            $("#checkJustifyVacaciones").prop('checked', false);
        }
    });
</script>
    </section>


@endsection
