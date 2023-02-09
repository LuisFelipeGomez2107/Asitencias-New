<?php

namespace App\Http\Controllers;

use PDF;


use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function index($id)
    {
        $usuario = User::join('areas','areas.id','users.areas_id')
        ->where('users.id',$id)
        ->select('users.*','areas.name as areaName')
        ->first();


      return view('qr.qrcode',compact('usuario'));
    }

    public function download($id)
    {

            //  generamos el qr con el direccionamiento dinamico a la url del usuario correspondiente
        // QrCode::generate('http://erick-fay/qrCode/'.$id,'../public/qrcodes/qrcode.svg');
        QrCode::generate(env('APP_URL').'/qrCode/'.$id,'../public/qrcodes/qrcode.svg');

        // enviamos la Data que va a necesitar nuestra vista pdf

        $usuario = User::join('areas','areas.id','users.areas_id')
        ->where('users.id',$id)
        ->select('users.*','areas.name as areaName')
        ->first();
        // dd($usuario);
        //  convertimos nuestra vista a formato PDF
        $pdf = PDF::loadView('qr.pdf',['usuario'=> $usuario]);

        // retornamos nuestra vista en formato PDF
        return $pdf->stream();

    }
}
