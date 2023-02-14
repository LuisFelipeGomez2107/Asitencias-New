@extends('layouts/contentLayoutSupervisor')

@section('title', 'Dashboard Supervisor')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
    {{-- Page css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Dashboard Supervisor Starts -->
    @php
        $user = Auth::user();
        $configData = Helper::applClasses();
        
    @endphp
    <section id="dashboard-ecommerce">

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
                          <pre><span>  Falta | </span> </pre><i class="fa-solid fa-xmark" style="color:red;" id="absence-icon"></i>
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
              {{-- Filtros de consulta --}}
              <div class="flex-wrap" id="form-date">
                  <form action="{{ route('dashboard-ecommerce') }}" id="form" method="GET">
  
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
      @if (isset($dateInitial))
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
  
              {{-- tabla dashboard  --}}
              <div class="table-responsive">
                  <table class="table table-striped">
                      <thead>
                          <tr>
                              <th class="static" scope="col">Nombre</th>
                              @for ($i = $dia_fecha_inicial; $i <= $dia_fecha_final; $i++)
                                  <th class="" scope="col">{{ $i }}</th>
                              @endfor
                          </tr>
  
                      </thead>
                      <tbody>
                          @foreach ($usuarios as $usuario)
                       
                              <tr>
                                  <td class="static" scope="row">{{ $usuario->name }}</td>
                                  @php
                                      for ($i = $dia_fecha_inicial; $i <= $dia_fecha_final; $i++) {
                                          // echo "<td >";
                                          $j = 0;
                                          $flag = false;
                                          $count = count($collection) - 1;
  
                                          foreach ($collection as $collect) {
                                              if ($collect->created_at == $i) {
                                                  if ($collect->id_user == $usuario->id) {
                                                      echo TestFacades::testMethod($usuario->id, $collect->all_date, $collection, $collectionjustificaciones,$usuario->areas_id);
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
  
      <div class="modal fade" id="modalJustificarFaltas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
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


    </section>
    <!-- Dashboard Supervisor ends -->
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/dashboard-ecommerce.js')) }}"></script>
@endsection
