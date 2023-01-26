<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <script src="{{ asset('assets/js/app.js') }}"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    <title>Fay-Web</title>
  </head>
 
  <body>
    {{-- NavBar --}}
    <nav class="navbar navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="{{asset('images/logo-fay.png')}}" alt="" width="140" height="60">
        </a>
      </div>
    </nav>
    {{-- NavBar --}}

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
              {{-- <div class="display-cover">
                <video class="d-none" autoplay></video>
                <canvas class="d-none"></canvas>
                <div class="video-options" style="display:none">
                    <select name="" id="" class="custom-select">
                        <option value="">Select camera</option>
                    </select>
                </div>
                <img class="screenshot-image d-none" alt="">
              </div> --}}
              <div class="display-cover">
                <!—Aquí el video embebido de la webcam -->
                <div class="video-options" style="display: none">
                  <select name="" id="" class="custom-select">
                      <option value="">Select camera</option>
                  </select>
              </div>
              <div class="container video-wrap">
              <video id="video" class="d-none" style="width: 100%" playsinline autoplay></video>
              <!—El elemento canvas -->
              <div class="controller" >
                {{-- <div class="container d-flex justify-content-center">
                  <button id="snap"><i class="fa-solid fa-camera"></i></button>
                </div> --}}
              </div>
              </div>
              <!—Botón de captura -->
              <canvas id="canvas" width="640" height="480" style="display: none;"></canvas> 
              </div>
              <div class="container"  style="">
                <div class="controls" style="">
                    <button class="btn btn-info pause d-none" title="Tomar Foto"><i class="fa-solid fa-camera"></i></button>
                    <div id="labels" class="">
                      <p id="Latitude" style="color:black;"></p>
                      <form action="{{route('subirImagen')}}" method="POST" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        <input type="hidden" name="" id="now">
                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude"> 
                        <input type="hidden" name="address">
                        <input type="hidden" id="target" name="imageName"/>
                        <input type="hidden" id="contentType" name="contentType" />
                        <input type="hidden" id="imageData" name="imageData" />
                        <input type="hidden" name="" id="autorize" value="">
                        <input type="hidden" name="date">

                        <button type="submit" class="btn save-btn d-none" style="background-color:#50c341; color:#ffff; border:none;">Guardar</button>
                      </form>
                    </div>
                </div>
              </div>
            </div> 
             
          </div>
      </div>
      <div class="denied d-none">
        <div class="container text-center">
          <p>
            <h6> Antes de continuar, es obligatorio activar tu ubicación.</h6>
          </p>
          <div style="color:#007d00; background-color:#e1e1e1; box-shadow: -1px 3px 9px 0px #000000; min-height:auto;">
            <div class="container align-middle w-50 pt-3 pb-3" style="">
              <i class="fa-solid fa-location-dot" style="font-size: 3rem;"></i>
              <p style="font-size: 1.1rem;">Activar GPS</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        @php
        $user =  Auth::user();
        $date = date("Y-m-d");
        $valor = TestFacades::returnView($date, $user->id);
        @endphp
        @if($valor == 1)  
        <script>
          Swal.fire({
            title: '<strong style="color:#8dbf42;">¡Gracias por tu labor de hoy!</strong>',
            // icon: 'info',
            // html:
            //   'You can use <b>bold text</b>, ' +
            //   '<a href="//sweetalert2.github.io">links</a> ' +
            //   'and other HTML tags',
            html:
              'Estas a punto de <b>registrar tu salida</b> de trabajo. Si deseas avanzar da click en el siguiente botón',
            showCloseButton: false,
            // showCancelButton: true,
            focusConfirm: false,
            confirmButtonColor: "#8dbf42",
            confirmButtonText:
              'Continuar',
            confirmButtonAriaLabel: 'Continuar',
            // cancelButtonText:
            //   '<i class="fa fa-thumbs-down"></i>',
            // cancelButtonAriaLabel: 'Thumbs down'
          })
        </script>
        @elseif($valor == 2 && !isset($_GET['success']))
        <x-denied></x-denied>
        @elseif($valor==0)
        
        @else
        <x-succes-two></x-succes-two>
        @endif
        <input type="hidden" id="salida" value="@if($valor == 2 && !isset($_GET['success'])){{"3"}}@endif">
      </div>
  </main>
    <footer class="footer mt-auto py-3 bg-light fixed-bottom">
      <div class="container d-flex flex-row-reverse">
        <a href="{{route('logout')}}"><i class="fas fa-sign-out">
          <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
            {{ csrf_field() }}
        </form></i></a>
      </div>
    </footer>
    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
    @if(isset($_GET['success']) && $valor==1 )
    <script>
      $("#verify").val("result")
      $("#succes").removeClass('d-none')
      $("#twoVery").val(1);
      $(".swal2-backdrop-show").remove();
    </script>
    @elseif(isset($_GET['success']) && $valor==2)
    @else
    <script src="{{ asset('js/modules.js')}}" type="module"></script>
   
    @endif
  
  </body>
</html>