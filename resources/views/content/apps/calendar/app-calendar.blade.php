@extends('layouts/contentLayoutMaster')

@section('title', 'Horarios')

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
<!-- Full calendar start -->
<section>
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
<style>
  .static {
      position: sticky;
      left: 0;
      background-color: white;
  }
</style>
<main>
  <div class="table-responsive">
      <table class="table table-striped">
          <thead>
              <tr>
                   <th class="text-center" scope="col">Actualizado</th>
                  <th class="text-center" scope="col">Area</th>
                  <th class="text-center" scope="col">Tolerancia</th>
                  <th class="text-center" scope="col">Acción</th>

              </tr>
              @foreach ($horarios as $horario)
          </thead>
          <tbody>
              <tr>
                    <td class="text-center">{{$horario->updated_at}}</td>
                  <td class="text-center">{{ $horario->area }}</td>
                  <td class="text-center">{{ $horario->tolerancia }}</td>
                  <td class="text-center">

                      <div class="row justify-content-between">
                          <div class="text-center">
                              <i class="fa-solid fa-pen-to-square mt-2" data-toggle="modal" class="btn_update"
                                  data-target="#modalEdit" data-whatever="@getbootstrap" style="cursor:pointer;"
                                  id="editmodaluser" data-id="{{ $horario->id }}" data-name="{{ $horario->area }}"
                                  data-tolerancia="{{ $horario->tolerancia }}"
                                  data-area="{{ $horario->area_id }}"></i>

                          </div>
                          <div class="col">
                  </td>


              </tr>
              <tr>
                  @endforeach
          </tbody>
      </table>
      {{-- Modal Edit --}}
      <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Editar Tolerancia</h5>
                  </div>
                  <div class="modal-body">
                      <form id="form_edit" enctype="multipart/form-data" method="POST"
                          action="{{ route('horarios.update') }}">
                          @csrf
                          <div class="form-group ">
                              <label for="nombre">Nombre</label>
                              <input type="text" class="form-control" id="nombre_edit"
                                  aria-describedby="nombrearea" placeholder="Nombre" name="nombre">

                          </div>
                          <div class="form-group mt-3">
                              <label for="tolerancia">Tolerancia</label>
                              <input type="text" class="form-control" id="tolerancia_edit"
                                  aria-describedby="toleranciaarea" placeholder="Tolerancia" name="Tolerancia">

                              <div class="form-group">
                                  <input type="hidden" class="form-control" id="id_edit" autocomplete="off"
                                      name="id">
                              </div>
                              <div class="form-group">
                                  <input type="hidden" class="form-control" id="id_areaedit" autocomplete="off"
                                      name="id_area">
                              </div>

                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary"
                                      data-dismiss="modal">Cerrar</button>
                                  <button type="submit" class="btn btn-primary" id="enviar_edit">Guardar</button>
                      </form>
                  </div>

              </div>
              {{-- Modal Edit --}}
          </div>
</main>

</section>
<!-- Full calendar end -->
@endsection
<script>
  //  Update User get elements
  $(".fa-pen-to-square").on('click', function() {
      // Sanitize form prev
      // alert($("#form_edit select option").length)
      $("#nombre_edit").val($(this).data('name'))
      $("#tolerancia_edit").val($(this).data('tolerancia'))
      $("#id_edit").val($(this).data('id'))
      $("#id_areaedit").val($(this).data('area'))
      // Inputs
  });


  //Modal para editar Horarios
  $('#form_edit').submit(function(ev) {
      $("#modalEdit").modal("hide");
      $(".modal-backdrop").remove();
      $('body').removeClass('modal-open');
      $("#modalEdit").removeClass("show");
      $("#modalEdit").removeAttr("aria-modal");
      $("#modalEdit").removeAttr("role");
      $("#modalEdit").attr("aria-hidden", "true");
      $("#modalEdit").css('display', 'none')

      Swal.fire(
          'Excelente',
          'Guardado con Exito',
          'success'
      )
  });
</script>

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset(mix('vendors/js/calendar/fullcalendar.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/pages/app-calendar-events.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/app-calendar.js')) }}"></script>
@endsection
