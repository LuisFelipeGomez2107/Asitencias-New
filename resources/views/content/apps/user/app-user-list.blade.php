@extends('layouts/contentLayoutMaster')


@section('title', 'Lista de Usuarios')

@section('vendor-style')
    {{-- Page Css files --}}

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                                    <select name="area" id="areasSelect_serch" class="form-control">
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
                            <button type="button" class="btn btn-primary mb-3 " id="openModalAdd" data-toggle="modal"
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


                <!-- Modal CreateUser -->
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
                            </div>

                            <div class="modal-body">
                                <form id="miFormulario" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre"
                                            aria-describedby="emailHelp" placeholder="Nombre" name="nombre">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" autocomplete="off"
                                            placeholder="Phone" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" autocomplete="off"
                                            placeholder="Email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="contraseña">Contraseña</label>
                                        <input type="password" class="form-control" id="contraseña"
                                            placeholder="Contraseña" name="password">
                                    </div>
                                    <div class="form-group">
                                        <label for="nss">NSS</label>
                                        <input type="text" class="form-control" id="nss"
                                            placeholder="Numero de seguridad social" name="nss">
                                    </div>
                                    <div class="form-group">
                                        <label for="curp">CURP</label>
                                        <input type="text" class="form-control" id="curp" placeholder="CURP"
                                            name="curp">
                                    </div>
                                    <div class="form-group">
                                        <label for="imagen">Imagen</label>
                                        <input type="file" class="form-control" id="imagen" placeholder="imagen"
                                            name="imagen" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="firma">Firma</label>
                                        <input type="file" class="form-control" id="firma" placeholder="firma"
                                            name="firma" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="tipo">Tipo de Usuario</label>
                                        <select id="rolSelect" class="form-control" name="tipoUser">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="SupervisorForm">
                                        <label for="areasSelect">Asignar a:</label>
                                        <select name="supervisor" id="supervisorSelect" class="form-control"
                                            name="supervisor"></select>
                                    </div>
                                    <div class="form-group d-none" id="areasForm">
                                        <label for="areasSelect_new">Asignar a un area:</label>
                                        <select name="areas" id="areasSelect_new" class="form-control"></select>
                                    </div>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" id="enviar">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal CreateUser -->
                {{-- --------------- --}}
                {{-- Modal Edit --}}
                <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                            </div>
                            <div class="modal-body">
                                <form id="form_edit"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre_edit"
                                            aria-describedby="emailHelp" placeholder="Nombre" name="nombre">

                                    </div>
                                    <div class="form-group">
                                        <label for="phone_edit">Phone</label>
                                        <input type="text" class="form-control" id="phone_edit" autocomplete="off"
                                            placeholder="Phone" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email_edit" autocomplete="off"
                                            placeholder="Email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="contraseña">Contraseña</label>
                                        <input type="password" class="form-control" id="contraseña_edit"
                                            placeholder="Contraseña" name="contraseña" autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <label for="nss">NSS</label>
                                        <input type="text" class="form-control" id="nss_edit"
                                            placeholder="Numero de seguridad social" name="nssUpdate">
                                    </div>
                                    <div class="form-group">
                                        <label for="curp">CURP</label>
                                        <input type="text" class="form-control" id="curp_edit" placeholder="CURP"
                                            name="curpUpdate">
                                    </div>
                                    <div class="form-group">
                                        <label for="imagen">Imagen</label>
                                        <input type="file" class="form-control" id="imagenUpdate"
                                            placeholder="imagen" name="imagenUpdate" accept="image/*">
                                    </div>
                                    <div class="form-group" id="conatinerImagen">
                                        <label for="imagen">Imagen Actual</label>

                                        <div class="container">
                                            <img src="" id="img_view" alt="" style="width:100%;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="firma">Firma</label>
                                        <input type="file" class="form-control" id="firmaUpdate" placeholder="firma"
                                            name="firmaUpdate" accept="image/*">
                                    </div>
                                    <div class="form-group" id="conatinerFirma">
                                        <label for="firma">Firma Actual</label>
                                        <div class="container">
                                            <img src="" id="firma_view" alt="" style="width:100%;">
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <input type="hidden" class="form-control" id="id_edit" autocomplete="off"
                                            name="id">
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="tipo">Tipo de Usuario</label>
                                        <select  id="rolSelect_edit" class="form-control" name="tipoUser_edit"></select>
                                    </div> --}}

                                    <div class="form-group" id="areasForm">
                                        <label for="areasSelect_edit">Asignar a un area:</label>
                                        <select name="areaupdate" id="areasSelect_edit" class="form-control"></select>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" id="enviar_edit">Guardar</button>
                                </form>
                            </div>

                        </div>
                        {{-- Modal Edit --}}

            </main>


            <script>
                $(document).ready(function() {
                    $('#miFormulario').submit(function(event) {
                        // Evita que el formulario se envíe de forma convencional
                        event.preventDefault();
                        let route = "{{ route('crear.usuario') }}";
                        // Envía los datos del formulario utilizando $.ajax()
                        $.ajax({
                            url: route, // Reemplaza por la URL a la que deseas enviar los datos
                            type: 'POST', // Método HTTP utilizado para enviar los datos
                            data: $('#miFormulario').serialize(), // Los datos del formulario serializados
                            success: function(response) {

                                Swal.fire(
                                    'Excelente',
                                    'Guardado con Exito',
                                    'success'
                                )

                            },
                            error: function(response) {

                                alert('Ocurrió un error al enviar el formulario.');
                            }
                        });
                    });


                });
            </script>

            
<script>
    $(document).ready(function() {
        $('#form_edit').submit(function(event) {
            // Evita que el formulario se envíe de forma convencional
            event.preventDefault();
            let route = "{{ route('user.update') }}";
            // Envía los datos del formulario utilizando $.ajax()
            $.ajax({
                url: route, // Reemplaza por la URL a la que deseas enviar los datos
                type: 'POST', // Método HTTP utilizado para enviar los datos
                data: $('#form_edit').serialize(), // Los datos del formulario serializados
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Estatus Actualizado',
                        showConfirmButton: false,
                        timer: 1000
                    })
                },
                error: function(response) {

                    alert('Ocurrió un error al enviar el formulario.');
                }
            });
        });


    });
</script>

            <script>
                $('.btn_update').on('click', function(e) {
                    e.preventDefault();
                });



                // traemos dinamicamente las areas de nuestra base
                $(function() {
                    let route = "{{ route('areas.get') }}";
                    $.ajax({
                        type: 'GET', //THIS NEEDS TO BE GET
                        url: route,
                        success: function(data) {
                            data.forEach(function(element) {
                                $("#areasSelect_serch").append(
                                    "<option class='super' value='" + element.id +
                                    "'>" + element.name + "</option>")
                            });
                        },
                        error: function() {
                            console.log(data);
                        }
                    })
                });

                // traemos dinamicamente los puestos de nuestra base
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

                });

                // traemos los roles dentro de nuestor formulario para crear usuarios

                $(function() {
                    let route = "{{ route('roles.get') }}";
                    $.ajax({
                        type: 'GET', //THIS NEEDS TO BE GET
                        url: route,
                        success: function(data) {
                            data.forEach(function(element) {
                                $("#rolSelect").append("<option value='" + element.name + "'>" + element
                                    .name +
                                    "</option>")
                            });
                        },
                        error: function() {
                            console.log(data);
                        }
                    })
                });

                // Validamos si el el rol es instalador despelgamos el Select de Supervisor y los traemos de la base

                $(function() {
                    $('#rolSelect').change(function() {
                        if ($(this).val() == "Instalador") {
                            $("#SupervisorForm").removeClass('d-none')
                            let route = "{{ route('supervisor.get') }}"
                            $.ajax({
                                type: 'GET', //THIS NEEDS TO BE GET
                                url: route,
                                success: function(data) {
                                    console.log(data)
                                    data.forEach(function(element) {
                                        $("#supervisorSelect").append(
                                            "<option class='super' value='" +
                                            element.id + "'>" + element.name +
                                            "</option>")
                                    });
                                },
                                error: function() {
                                    console.log(data);
                                }
                            });
                        } else {
                            if ($("#SupervisorForm").hasClass("seleccionado") > 0) {} else {
                                $("#SupervisorForm").addClass('d-none')
                                $(".super").remove()
                            }
                        }
                    })
                });

                // traemos los roles dentro del formulario de nuestra base de datos

                $('#rolSelect').change(function() {
                    if ($(this).val() != 1) {
                        $("#areasForm").removeClass('d-none')
                        let route = "{{ route('areas.get') }}"
                        $.ajax({
                            type: 'GET', //THIS NEEDS TO BE GET
                            url: route,
                            success: function(data) {
                                console.log(data)
                                data.forEach(function(element) {
                                    $("#areasSelect_new").append(
                                        "<option class='super' value='" + element.id +
                                        "'>" + element.name + "</option>")
                                });
                            },
                            error: function() {
                                console.log(data);
                            }
                        });
                    } else {
                        if ($("#areasForm").hasClass("seleccionado") > 0) {

                        } else {
                            $("#areasForm").addClass('d-none')
                            $(".super").remove()
                        }
                    }

                });

                //  Update User get elements
                $(".fa-pen-to-square").on('click', function() {
                    console.log("hello")
                    // Sanitize form prev
                    // alert($("#form_edit select option").length)
                    if ($("#form_edit select option").length > 0) {
                        $("#form_edit select").empty()
                    }
                    $("#form_edit input[type=text] , #form_edit textarea").each(function() {
                        this.value = ''
                    });
                    $("#form_edit img").each(function() {
                        $(this).attr('src', '')
                    });
                    // Sanitize form prev
                    // Inputs
                    $('#nombre_edit').val($(this).data('nombre'));
                    $('#email_edit').val($(this).data('email'));
                    $('#phone_edit').val($(this).data('phone'));
                    $('#nss_edit').val($(this).data('nss'));
                    $('#curp_edit').val($(this).data('curp'));
                    $("#id_edit").val($(this).data('id'));


                    if ($(this).data('image') == "" || $(this).data('image') == 'undefined') {
                        $("#conatinerImagen").hide();
                    } else {
                        $('#img_view').attr('src', "/storage/personales/" + $(this).data('id') + "/" + $(this).data(
                            'image'));
                    }
                    if ($(this).data('firma') == "" || $(this).data('firma') == 'undefined') {
                        $("#conatinerFirma").hide();
                    } else {
                        $('#firma_view').attr('src', "/storage/personales/" + $(this).data('id') + "/" + $(this).data(
                            'firma'));
                    }
                    // traemos dinamicamente las areas de nuestra base
                    $(function() {
                        let route = "{{ route('areas.get') }}";
                        $.ajax({
                            type: 'GET', //THIS NEEDS TO BE GET
                            url: route,
                            success: function(data) {
                                data.forEach(function(element) {
                                    $("#areasSelect_edit").append(
                                        "<option class='super' value='" + element.id +
                                        "'>" + element.name + "</option>")
                                });
                            },
                            error: function() {
                                console.log(data);
                            }
                        })
                    });

                    // Inputs
                })




                // traemos los roles dentro del formulario de nuestra base de datos

                $('#rolSelect').change(function() {
                    if ($(this).val() != 1) {
                        $("#areasForm").removeClass('d-none')
                        let route = "{{ route('areas.get') }}"
                        $.ajax({
                            type: 'GET', //THIS NEEDS TO BE GET
                            url: route,
                            success: function(data) {
                                console.log(data)
                                data.forEach(function(element) {
                                    $("#areasSelect_new").append(
                                        "<option class='super' value='" + element.id +
                                        "'>" + element.name + "</option>")
                                });
                            },
                            error: function() {
                                console.log(data);
                            }
                        });
                    } else {
                        if ($("#areasForm").hasClass("seleccionado") > 0) {

                        } else {
                            $("#areasForm").addClass('d-none')
                            $(".super").remove()
                        }
                    }

                });


                // Open Modal Sanitize
                $("#openModalAdd").on('click', function() {
                    // if($("#form select option").length > 0){
                    //     $("#form select").empty()
                    // }
                    $("#form input[type=text] , #form textarea,form input[type=email],form input[type=password]  ").each(
                        function() {
                            this.value = ''
                        });
                })
                // Open Modal Sanitize


                // Status Switch
                $(".switchStatusUser").on('click', function() {
                    let status;
                    if ($(this).hasClass('fa-toggle-on')) {
                        $(this).removeClass('fa-toggle-on');
                        $(this).addClass('fa-toggle-off');
                        status = 0;
                    } else {
                        $(this).removeClass('fa-toggle-off');
                        $(this).addClass('fa-toggle-on');
                        status = 1;
                    }
                    id = $(this).data('id')
                    // alert(status);
                    $.ajax({
                        type: 'GET', //THIS NEEDS TO BE GET
                        url: "updateStatusUsers",
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(data) {
                            console.log(data);
                            if (data == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Estatus Actualizado',
                                    showConfirmButton: false,
                                    timer: 1000
                                })
                            }
                        },
                        error: function() {
                            console.log(data);
                        }
                    });
                })


                // Status Switch
                $(".fa-qrcode").on('click', function() {
                    createQr($(this).data('id'));
                });

                function createQr(id) {
                    var url = location.host;
                    url = "https://" + url
                    new QRious({
                        element: document.querySelector(`#qr${id}`),
                        value: url + "/qrCode/" + id, // La URL o el texto
                        size: 150,
                        backgroundAlpha: 0, // 0 para fondo transparente
                        level: "H", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
                    });
                }

           
            </script>


        </section>

        <!-- users list ends -->
    @endsection
