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
    
    .table{
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

                        {{-- <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Usuarios/Horarios
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Usuarios</a>
                                <a class="dropdown-item" href="#">Horarios Areas</a>

                            </div>
                        </div>
                </div>
            </div> --}}
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
                @if ($requestDateInicio!="")
                @php
                if($requestArea==null)$requestArea=0;
                else $requestArea = $requestArea;
                @endphp
                <a href="{{route('export.excel.dashHistorial',['requestDateInicio' => $requestDateInicio, 'requestDateFinal'=> $requestDateFinal, 'requestArea' => $requestArea])}}"
                    class="btn btn-primary btn-lg active" role="button" aria-pressed="true"
                    title="Descargar Reporte Excel"><i class="fas fa-arrow-circle-down"></i></a>
                @else
                <a href="{{route('export.excel.dashIndex')}}" class="btn btn-primary btn-lg active" role="button"
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
              <div class="table-responsive"  style="max-with: 400px; overflow-x: auto;">
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
                                    if ($requestDateInicio !="") {
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
                                                      
                                                            echo TestFacades::testMethodColors($usuario->id, $collect->all_date, $collection, $collectionjustificaciones, $item,$usuario->id_area);
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
                                                     
                                                        echo TestFacades::testMethod($usuario->id, $collect->all_date, $collection, $collectionjustificaciones,$usuario->id_area);
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


    </section>


@endsection
