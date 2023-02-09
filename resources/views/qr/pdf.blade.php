<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Qr {{$usuario->name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="icon" href="{{asset('images/Icono-Fay-Web-Pestana.jpg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/pdf.css')}}">
</head>
<body>
<div class="card">
    <div class="credencial  mt-5" >
        <img src="{{{asset('images/Credenciales.jpg')}}}" class="card-img-top" alt="...">
         <div class="image-credencial " >
                           <img src="{{asset('storage/personales/'.$usuario->id.'/'.$usuario->profile_photo_path)}}" class="img mx-auto d-block"  alt="...">
                 </div>
                 @if ($usuario->areas_id != 1)
                 <div class="text-fay-center">
                    <p>Empresa promotora:</p>
                </div>
                <div class="text-fay-1-center">
                     <p>Fay Mexico S.A de C.V</p>
                 </div>
                 @else
                 <div class="text-fay">
                    <p>Empresa promotora:</p>
                </div>
                <div class="text-fay-1">
                     <p>Fay Mexico S.A de C.V</p>
                 </div>
                 @endif

@if ($usuario->areas_id == 1)
<div class="text-fay-2">
    <p>Agencia que Contrata:</p>
</div>
<div class="text-fay-3">
    <p>Walmart Connect</p>
</div>

@endif
         <div class="text">
             <p class="card-text text-aling-center" id="name_credencial">{{$usuario->name}}</p>
             @if($usuario->nss == null)
              <p class="hidden" id="nss_credencial"></p>
             @else
             <p class="card-text text-aling-center" id="nss_credencial">NSS:{{$usuario->nss}}</p>
             @endif
             @if($usuario->curp == null)
             <p class="hidden mt-4"   id="curp_credencial"></p>

             @else
             <p class="card-text text-aling-center" id="curp_credencial">CURP: {{$usuario->curp}}</p>
               @endif
             <p class= "employed">Empleado</p>
         </div>
            <div class="qr">
              <img src="{{{asset('qrcodes/qrcode.svg')}}}" id="qr{{$usuario->id}}" class="image-qr">
              </div>

                     </div>
                          </div>
                                </div>
                                     </div>
</body>
</html>
