@extends('layouts.dashboard')
@section('content')
<script>
        Swal.fire({
            title: '<strong>¡Bienvenido!</strong>',
            icon: 'info',
            html:
                'Selecciona como deseas Iniciar Sesión',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
                '<a href="{{route("dashboard-ecommerce")}}" style="text-decoration: none;color:#fff;"><i class="fas fa-directions"></i> Supervisor</a>',
            confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText:
                '<a href="{{route("dashboard")}}" style="text-decoration: none;color:#fff;"><i class="fas fa-directions"></i> Administrativo</a>',
            cancelButtonAriaLabel: 'Thumbs down'
        })
</script>
@endsection