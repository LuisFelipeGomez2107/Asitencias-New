@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Admin')


@section('content')
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

                        <div class="dropdown">
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
            </div>
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
        </main>


    </section>


@endsection
