<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class DashExport implements FromView,ShouldAutoSize,WithDrawings,WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $usuarios;
    private $lastDayMonth;
    private $countMonths;
    private $meses;
    private $images;
    private $dateInitial;
    private $requestDateInicio;
    private $requestDateFinal;
    private $requestArea;
    private $collection;
    private $collectionjustificaciones;
    
    public function __construct($usuarios ,$lastDayMonth,$countMonths,$collection,$collectionjustificaciones)
    {
        $this->usuarios = $usuarios;
        $this->lastDayMonth = $lastDayMonth;
        $this->countMonths = $countMonths;
        $this->collection = $collection;
        $this->collectionjustificaciones= $collectionjustificaciones;
        // $this->collection = $images;
    }
    public function collection()
    {
        // dd("||||");
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Asistencias-Fay');
        $drawing->setPath(public_path('images/icons/logo-fay.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    public function startCell(): string
    {
        return 'A7';
    }
    public function view(): View {
            return view('exports.reporteIndex',[
                'collection'=>$this->collection,
                'usuarios' => $this->usuarios,
                'lastDayMonth' => $this->lastDayMonth,
                'countMonths' =>$this->countMonths,
                'meses' => $this->meses,
                'images' => $this->images,
                'dateInitial' => $this->dateInitial,
                'requestDateInicio' => $this->requestDateInicio,
                'requestDateFinal' => $this->requestDateFinal,
                'requestArea' => $this->requestArea,
                'collectionjustificaciones'=> $this->collectionjustificaciones,
            ]);
    }
}
