@extends('layouts/contentLayoutMaster')


@section('title', 'Lista de Usuarios')

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


@section('content')
    <!-- users list start -->
    <section class="app-user-list">
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
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
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
                <div class="container-fluid flex-wrap mt-3" style="min-width: 95%;">
                    <div class="flex-wrap" id="form-date">
                        <form action="{{ route('admin.historial.userlist') }}" class="d-flex justify-content-between"
                            id="form-serch" method="POST">
                            @csrf
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
                                    <button type="submit" class="btn btn-primary" id="enviarSerch">Buscar</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>

                <div class="container-fluid flex-wrap mt-3 table-responsive ">
                    <table class="table table-striped ">
                        <div class="d-flex flex-row-reverse bd-highlight ">
                            <button type="button" class="btn btn-primary " id="openModalAdd" data-toggle="modal"
                                data-target="#addUserModal"> + Usuario</button>
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Acción</th>
                                    <th scope="col">Credencial</th>
                                    <th scope="col">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuario as $users)
                                    <tr>
                                        <td id="text-nombre">{{ $users->name }}</td>
                                        <td id="text-email">{{ $users->email }}</td>
                                        <td id="text-email">{{ $users->phone }}</td>
                                        <td>{{ $users->rol }}</td>
                                        <td>{{ $users->Areas }}</td>
                                        <td>

                                            <div class="container">
                                                <div class="row justify-content-between">
                                                    <div class="col">
                                                        <i class="fa-solid fa-pen-to-square" data-toggle="modal"
                                                            class="btn_update" data-target="#modalEdit"
                                                            data-whatever="@getbootstrap" style="cursor:pointer;"
                                                            id="editmodaluser" data-id="{{ $users->id }}"
                                                            data-rol="{{ $users->rol }}"
                                                            data-nombre="{{ $users->name }}"
                                                            data-email="{{ $users->email }}"
                                                            data-phone="{{ $users->phone }}"
                                                            data-area="{{ $users->Areas }}"
                                                            data-nss="{{ $users->nss }}"
                                                            data-curp="{{ $users->curp }}"
                                                            data-image="{{ $users->profile_photo_path }}"
                                                            data-firma="{{ $users->firma }}"></i>
                                                    </div>
                                                    <div class="col">
                                                        <i class="fa-sharp fa-solid fa-qrcode" style="cursor: pointer;"
                                                            data-id="{{ $users->id }}" data-toggle="modal"
                                                            data-target="#modalQr{{ $users->id }}"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Modal QR --}}
                                            <div class="modal fade" id="modalQr{{ $users->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="qrModalTitle">QR -
                                                                {{ $users->name }}</h5>
                                                      
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="d-flex justify-content-center">
                                                              
                                                                <img alt="Código QR" id="qr{{ $users->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Modal QR --}}
                                        </td>
                                        {{-- button credencial --}}
                                        <td>
                                            <div class="row">
                                                <div class="col-xl-12 ml-6  ">
                                                    <a href="{{ route('qr.pdf', ['id' => $users->id]) }}"
                                                        class="btn btn-secondary btn-sm"><i
                                                            class="fa-regular fa-id-badge "></i></a>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- button credencial --}}

                                        <td>
                                            <i style="cursor: pointer;"
                                                class="@php if($users->status == 1) echo "fas fa-toggle-on switchStatusUser"; else echo "fas fa-toggle-off switchStatusUser"; @endphp"
                                                data-id="{{ $users->id }}"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
                </div>
            </main>
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
    @endsection
