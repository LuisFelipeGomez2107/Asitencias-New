@extends('layouts/contentLayoutSupervisor')

@section('title', 'Evidencias Fotograficas')

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
        </nav>
        <main class="flex-shrink-1">
            <div id="succes" class="d-none">
                <x-success></x-success>
              </div>
              <input type="hidden" name="" id="twoVery">
              <div class="autorize d-none">
                  <div class="container text-center title">
                      <h3 class="mt-5 text-center" style="color:#8dbf42;">¡Bienvenido!</h3>
                      <p class="text-center"> <h6>Selecciona la cámara, toma una foto y guárdala para dar inicio a tu sesión</h6></p>
                  </div>
                  <div class="container text-center">
                    <div class="container" style="background-color:;">
                      <div class="container text-center w-80 play pb-2" style="background-color: #E1E1E1; box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);border-radius:5px;
                      ">
                        <button class="btn" title="Play"><i class="fa-solid fa-camera" style="font-size: 8rem; color:#696969;"></i></button>
                        <h2 style="color:#696969;">Tomar foto</h2>
                      </div>
                    </div>
                    <div>

            </main>
    </section>


@endsection

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
