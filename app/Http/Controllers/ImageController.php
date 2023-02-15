<?php

namespace App\Http\Controllers;


use App\Models\Locations;
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\UsersExport;
use App\Models\Justificaciones;
use Maatwebsite\Excel\Facades\Excel;

class ImageController extends Controller
{
    public function upluadImage(Request $request){
        $insertLocation = new Locations();
        $insertLocation->latitude = $request->latitude;
        $insertLocation->logitude = $request->longitude;
        $insertLocation->address =  $request->address;
        $insertLocation->created_at =  $request->date;
        $insertLocation->updated_at =  $request->date;

        $insertLocation->save();
        $imagen = $request->imageName;
        if(strlen($imagen) > 0){
            $img_a_guardar = base64_decode(preg_replace('/^[^,]*,/', '', $imagen));
            $nombre_foto = Str::uuid()->toString().".jpg";
            //$ruta_foto = "../storage/app/public/images/".$nombre_foto;
            //file_put_contents($ruta_foto, $img_a_guardar);
            $img = Img::make($img_a_guardar);
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk(env('ROUTE'))->put('images/'.$nombre_foto, (string)$img->encode('jpg', 75));
            $insertImages = new Images();
            $id = Auth::id();
            $insertImages->user_id = $id;
            $insertImages->location_id = $insertLocation->id;
            $insertImages->name = $nombre_foto;

            $insertImages->created_at = $request->date;
            $insertImages->updated_at = $request->date;


            $insertImages->save();
        }
        return redirect()->route('test',['success'=> 'Data saved successfully!']);
    }

    public function consultarImagenes(Request $request)
    {
        $start = $request->date." 00:00:00";
        $end = $request->date." 23:59:59";
        $image = Images::whereBetween('images.created_at', [$start, $end])
        ->join('locations','images.location_id','locations.id')
        ->select('images.id as imageId','locations.address as address','images.name as name','images.user_id as imageUserId','images.created_at as created_at','images.user_id as user_id')
        ->where('user_id', $request->user_id)
        ->get();
        $imagenes = array();
        $a = 0;
        foreach($image as $i) {
            $imagenes[$a]['url'] = Storage::disk(env('ROUTE'))->url('images/'.$i->name);
            $imagenes[$a]['id'] = $i->imageId;
            $imagenes[$a]['name'] = $i->name;
            $imagenes[$a]['address'] = $i->address;
            $imagenes[$a]['user_id'] = $i->user_id;
            $porciones = explode(" ",$i->created_at);
            $imagenes[$a]['created_at'] = $porciones[1];
            $imagenes[$a]['fecha'] = $porciones[0];
            // Consultar Justificantes
            $imagenes[$a]['justificacion'] = $this->consultarJustificantes($i->imageId);
            // Consultar Justificantes

            $a++;
        }

        return $imagenes;
    }
    public  static function countImages($date,$user_id) {
        $start = date($date." ". '00:00:00');
        $end = date($date." ". '23:59:59');
        $images = Images::whereBetween('created_at',[$start, $end])
        ->where('user_id',$user_id)
        ->orderBy('id')
        ->first();
        if($images){
            $hora = date("h:i:s",strtotime("09:15:59"));
            $horaIn = date("H:i:s",strtotime($images->created_at));
            // dd($horaIn." ".$hora);
            if($horaIn <= $hora){
            $result = 1;

            }else{
            $result = 2;

            }
        }else{
            $result =0;
        }
        return $result;
        // return $horaIn ."\t".$hora."\t".$result;

     }
     public function consultarJustificantes($id){
        $justificaciones = Justificaciones::where('images_id',$id)
        ->first();
        if($justificaciones){
            return 1;
        }
        else{
            return 2;
        }
        // return $justificaciones;
     }
     public function consultarJustificaciones(Request $request){
        $justificaciones = Justificaciones::where('images_id',$request->image_id)
        ->join('users','justificaciones.user_id','users.id')
        ->select('users.name as userName','justificaciones.id as id','justificaciones.name as name','justificaciones.comentario as comentario')
        ->get();
        $justificacion = array();
        $a = 0;
        foreach($justificaciones as $j) {
            $justificacion[$a]['url'] = Storage::disk(env('ROUTE'))->url('justificantes/'.$request->user_id.'/'.$j->name);
            $justificacion[$a]['id'] = $j->id;
            $justificacion[$a]['name'] = $j->name;
            $justificacion[$a]['comentario'] = $j->comentario;
            $justificacion[$a]['userJ'] = $j->userName;
      

            $a++;
        }
        return $justificacion;
     }
}
