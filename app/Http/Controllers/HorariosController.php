<?php

namespace App\Http\Controllers;

use App\Models\Tolerancia;
use Illuminate\Http\Request;

class HorariosController extends Controller
{
    public function index(Request $request){
   

        $horarios=Tolerancia::where('status','active')
        ->get();
        
      

        return view('/content/apps/calendar/app-calendar' , compact('horarios'));
    }

    public function indexSupervisor(Request $request){
   

        $horarios=Tolerancia::where('status','active')
        ->get();
        
      

        return view('/content/apps/calendar/app-calendar-supervisor' , compact('horarios'));
    }


    public function update(Request $request){

        $horarios =Tolerancia::find($request->id);
        $horarios->area = $request->nombre;
        $horarios->area_id =$request->id_area;
        $horarios->tolerancia =$request->Tolerancia;
        $horarios->status = 'inactive';
        $horarios->save();
        


        $horarios =new Tolerancia();
        $horarios->area = $request->nombre;
        $horarios->area_id =$request->id_area;
        $horarios->tolerancia =$request->Tolerancia;
        $horarios->status = 'active';
        $horarios->updated_at = date('Y-m-d H:i:s');
        $horarios->created_at =date('Y-m-d H:i:s');
        $horarios->save();

        return back();

    }

}

