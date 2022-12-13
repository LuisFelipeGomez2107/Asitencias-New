@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'User List') --}}

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
  integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
<style>
  table {
      display: block;
      overflow-x: auto;
  }

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

  .first-col {}

  label.error {
      font-size: .9vw !important;
  }
  </style>
@section('content')
<!-- users list start -->
<section class="app-user-list">
  <div class="container-fluid flex-wrap mt-3">
    <div class="data">

        <div class="d-flex flex-wrap " id="symbol">
            <div class="d-flex flex-wrap flex-row-reverse bd-highlight">

                <p>
                <pre><span>  Solo una Evidencia | </span> </pre><i
                    class="fas fa-exclamation-circle fas-solid showModal" style="color:#ff8c00;"
                    id="evidence-icon"></i></p>
            </div>
            <div class="d-flex flex-wrap flex-row-reverse bd-highlight">

                <p>
                <pre><span>  Falta | </span> </pre><i class="fa-solid fa-xmark" style="color:red;"
                    id="absence-icon"></i></p>
            </div>
            <div class="d-flex flex-wrap flex-row-reverse bd-highlight">

                <p>
                <pre><span>  Retardo | </span></pre> <i class="fa-solid fa-check" style="color:#ff9c00;"
                    id="time-delay-icon"></i></p>
            </div>
            <div class="d-flex flex-wrap flex-row-reverse bd-highlight">

                <p>
                <pre><span> Asistencia | </span></pre> <i class="fa-solid fa-check" style="color:#4fc341;"
                    id="attendance-icon"></i></p>
            </div>
        </div>
        <div class="flex-wrap" id="form-date">

        </div>
  <!-- list and filter start -->
  <div class="flex-wrap" id="form-date">
    <form  id="form" method="POST">
        @csrf
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
            @php
            $user = Auth::user();
            @endphp
            @if ($user = $user->hasRole('Admin'))
            <div class="col-4 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Área</label>
                    <select name="area" id="areasSelect" class="form-control">
                        <option value=""> Seleccionar </option>
                    </select>
                </div>
            </div>
            @endif
            <div class="col-1 d-flex align-items-end">
                <div class="form-group ">
                    <button type="submit" class="btn btn-primary" id="enviar">Buscar</button>
                </div>
            </div>
        </div>
    </form>
</div>

</div>

      </table>
    </div>
    <div class="d-flex flex-row-reverse bd-highlight" id="btn-excel">
      @if (isset($dateInitial))
      @php
      if($requestArea==null)$requestArea=0;
      else $requestArea = $requestArea;
      @endphp
      <a href="{{(['requestDateInicio' => $requestDateInicio, 'requestDateFinal'=> $requestDateFinal, 'requestArea' => $requestArea])}}"
          class="btn btn-primary btn-lg active" role="button" aria-pressed="true"
          title="Descargar Reporte Excel"><i class="fas fa-arrow-circle-down"></i></a>
      @else
      <a href="" class="btn btn-primary btn-lg active" role="button"
          aria-pressed="true" title="Descargar Reporte Excel"><i class="fas fa-arrow-circle-down"></i></a>
      @endif

  </div>
  <div class="mt-3">
    @if (isset($meses))
    <div>
        <div class="col-6">
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseThree">
                            Meses Desplegados
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <div class="row">
                                @foreach ($meses as $item)
                                <div class="col-4 months" style="cursor:pointer;"
                                    data-value="{{$item['nombre']}}">
                                    <div class="row">
                                        <div class="col-2">
                                            <span id="color_front"
                                                style="border-radius: 50%;width: 20px;height: 20px;display:inline-block;background-color:{{$item['color']['color']}};"></span>
                                        </div>
                                        <div class="col-10">{{$item['nombre']}}</div>
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
  <div class="table-responsive">
    <table class="table table-striped">
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
                @endphp
                @for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++) @if($contador==1) @if($i==1) <th
                    style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;"
                    class="first-col">{{$i}}</th>
                    @elseif($i == $item['ultimoDia'])
                    <th style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;"
                        id="{{$item['nombre']}}">{{$i}}</th>
                    @else
                    <th style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;">{{$i}}
                    </th>
                    @endif
                    @else
                    @if($i == 1)
                    <th style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;">{{$i}}
                    </th>
                    @elseif($i == $item['ultimoDia'])
                    <th style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;"
                        id="{{$item['nombre']}}">{{$i}}</th>
                    @else
                    <th style="border-color:{{$item['color']['color']}}; border-bottom-width: medium;">{{$i}}
                    </th>
                    @endif
                    @endif
                    @endfor
                    @php
                    $contador++;
                    $rcv++;
                    @endphp
                    @endforeach
                    @else
                    @for ($i = 1; $i <= $lastDayMonth; $i++) @if($i==1) <th style="border-color:;"
                        class="first-col" scope="col">{{$i}}</th>
                        @else
                        <th scope="col" style="border-color:;">{{$i}}</th>
                        @endif
                        @endfor
                        @endif
                        @endif
            </tr>
        </thead>
        <tbody>
            <div>
                @foreach ($usuarios as $users)

                <tr>
                    <td class="static" scope="row">{{ $users->name }}</td>
                    @endforeach
                    {{-- @if (isset($meses))
                    @php
                    $contador = 1;
                    $rcv = 0;
                    @endphp
                    @foreach ($meses as $item)
                    @for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++) @php
                        $date=date("Y-".$item['mes']."-".$i); $date=date("Y-m-d", strtotime($date)); @endphp
                        @php $countImages=TestFacades::countImages($date, $users->id);
                        if($countImages == 0){
                        echo '<td class="first-col" style="background-color:'.$item['color']['rgba'].'"><i
                                class="fa-solid fa-xmark showModal  " style="color:red;"></i></td>';
                        }
                        elseif ($countImages==1) {
                        echo '<td class="first-col" style="background-color:'.$item['color']['rgba'].'"><i
                                class="fa-solid fa-check showModal" style="color:#4fc341; cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                data-user-id='.$users->id.' data-date='.$date.'></i></td>';
                        }
                        elseif ($countImages==2) {
                        echo '<td class="first-col" style="background-color:'.$item['color']['rgba'].'"><i
                                class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                data-user-id='.$users->id.' data-date='.$date.'></i></td>';
                        }
                        elseif($countImages == 4){
                        echo '<td style="background-color:'.$item['color']['rgba'].'"><i
                                class="fa-solid fa-check-double showModal"
                                style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-user-id='.$users->id.'
                                data-date='.$date.'></i></td>';
                        }
                        elseif($countImages == 5){
                        echo '<td style="background-color:'.$item['color']['rgba'].'"><i
                                class="fas fa-calendar-check showJustificarFalta"
                                style="color:#4fc341; cursor:pointer;" data-user-id='.$users->id.'
                                data-date='.$date.' data-bs-toggle="modal"
                                data-bs-target="#modalShowJustificarFaltas"></i></td>';
                        }
                        elseif ($countImages == 6) {
                        echo '<td style="background-color:'.$item['color']['rgba'].'"><i
                                class="fas fa-exclamation-circle showModal"
                                style="color:#ff8c00;cursor:pointer;" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-user-id='.$users->id.'
                                data-date='.$date.'></i></td>';
                        }
                        else{
                        echo '<td class="first-col" style="background-color:'.$item['color']['rgba'].'">-</td>';
                        }
                        @endphp
                        @php
                        $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                        @endphp
                        @endfor
                        @php
                        $contador++;
                        $rcv++;
                        @endphp
                        @endforeach
                        @else
                        @php
                        $date = date("Y-m-01");
                        $out = null;
                        for ($i = 1; $i <= $lastDayMonth; $i++) { $countImages=TestFacades::countImages($date,
                            $users->id);
                            if($countImages == 0){
                            echo '<td class="first-col"><i class="fa-solid fa-xmark justificafalta"
                                    style="color:red; cursor:pointer;" data-toggle="modal"
                                    data-target="#modalJustificarFaltas" data-user-id='.$users->id.'
                                    data-date='.$date.'></i></td>';
                            // $boton = "<i class='fa-solid fa-xmark' style='color:red;'></i>";
                            }
                            elseif ($countImages==1) {
                            echo '<td class="first-col"><i class="fa-solid fa-check showModal"
                                    style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-user-id='.$users->id.'
                                    data-date='.$date.'></i></td>';
                            // $boton = "<i class='fa-solid fa-check' style='color:#4fc341;''></i>";
                                                    }
                                                    elseif ($countImages==2) {
                                                        echo ' <td class="first-col"><i
                                    class="fa-solid fa-check showModal" style="color:#ff9c00; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    data-user-id='.$users->id.' data-date='.$date.'></i></td>';
                                // $boton = "<i class='fa-solid fa-check' style='color:#ff9c00;'></i>";
                                }
                                elseif($countImages == 4){
                                echo '<td><i class="fa-solid fa-check-double showModal"
                                        style="color:#4fc341; cursor:pointer;" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-user-id='.$users->id.'
                                        data-date='.$date.'></i></td>';
                                }
                                elseif($countImages == 5){
                                echo '<td><i class="fas fa-calendar-check showJustificarFalta"
                                        style="color:#4fc341;cursor:pointer;" data-user-id='.$users->id.'
                                        data-date='.$date.' data-bs-toggle="modal"
                                        data-bs-target="#modalShowJustificarFaltas"></i></td>';
                                }
                                elseif ($countImages == 6) {
                                echo '<td><i class="fas fa-exclamation-circle fas-solid showModal"
                                        style="color:#ff8c00; cursor:pointer;" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-user-id='.$users->id.'
                                        data-date='.$date.'></i></td>';
                                }
                                else{
                                echo '<td class="first-col">-</td>';
                                }
                                $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                                }
                                @endphp
                                @endif
                </tr>
                @endforeach
            </div> --}}
        </tbody>
    </table>
</div>
</div>
</div>
</div>

</section>
<!-- users list ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
  @endsection

  @section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user-list.js')) }}"></script>

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

        </script>

@endsection
