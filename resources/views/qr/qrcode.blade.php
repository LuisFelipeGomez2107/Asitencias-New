<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Qr {{$usuario->name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="icon" href="{{asset('images/Icono-Fay-Web-Pestana.jpg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/qr.css')}}">
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="{{asset('images/logo-fay.png')}}" alt="Logo Fay publicidad" width="140" height="60"></a>
        </div>
      </nav>
    <div class="container shadow-sm p-3 mb-5 bg-white rounded">
        <div class="card">

            <div class="d-flex justify-content-center">
              <div class="card" style="width: 50%;">
                <div class="credencial">
                  <img src="{{{asset('images/Credenciales.png')}}}" class="card-img-top" alt="...">
                   <div class="image-credencial " >
                                     <div class="container-image"><img src="{{asset('storage/personales/'.$usuario->id.'/'.$usuario->profile_photo_path)}}" class="img-thumbnail mx-auto d-block"  alt="..."></div>

                        <div class="card-body">
                 <div class="text-container">
                  <p class="card-text text-center" id="name_credencial">{{$usuario->name}}</p>
                  @if($usuario->nss != null)
                  <p class="card-text text-center" id="nss_credencial">NSS:{{$usuario->nss}}</p>
                  @endif
                  @if($usuario->curp != null)
                    <p class="card-text text-center" id="curp_credencial">CURP: {{$usuario->curp}}</p>
                  @endif
                  <p class= "employed">Empleado</p>


            </div>
        </div>  </div>


                    </div>

  </div>
                    </div>

                  </div>
                  </div>
                </div>

    </div>
</body>

</html>

