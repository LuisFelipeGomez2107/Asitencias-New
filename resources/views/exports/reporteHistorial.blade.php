<style>
    td {
        padding: 10px;
        margin: 3em;
    }
</style>
<table>
    <thead>

    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>

<h5 style="background-color: #00FFFF;">0 → falta | 1 → Asistencia | 2 → Retardo | 3 → Solo una evidencia</h5>

<table>
    <thead>
        <tr>
            <th class="static">Nombre</th>
            @if (isset($countMonths))
                @if (isset($meses))
                    @php
                        $contador = 1;
                        $rcv = 0;
                    @endphp
                    @foreach ($meses as $item)
                        @for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++)
                            @if ($contador == 1)
                                @if ($i == 1)
                                    <th>{{ $i }}</th>
                                @elseif($i == $item['ultimoDia'])
                                    <th>{{ $i }}</th>
                                @else
                                    <th>{{ $i }}</th>
                                @endif
                            @else
                                @if ($i == 1)
                                    <th>{{ $i }}</th>
                                @elseif($i == $item['ultimoDia'])
                                    <th>{{ $i }}</th>
                                @else
                                    <th>{{ $i }}</th>
                                @endif
                            @endif
                        @endfor
                        @php
                            $contador++;
                            $rcv++;
                        @endphp
                    @endforeach
                    {{-- Conteos --}}
                    <th>Total Asistencias</th>
                    <th>Total Retardos</th>
                    <th>Total Faltas</th>
                    <th>Total Solo una Evidencia</th>
                    {{-- Conteos --}}
                @endif
            @endif
        </tr>

    </thead>
    <tbody>
        <div>
            @foreach ($usuarios as $users)
                <tr>
                    <td>{{ $users->name }}</td>
                    @php
                        $contador = 0;
                        $rcv = 0;
                        $asistencia = 0;
                        $retardo = 0;
                        $falta = 0;
                        $SoloUnaEvidencia = 0;
                        
                    @endphp
                    @foreach ($meses as $item)
                        @for ($i = $item['primerDia']; $i <= $item['ultimoDia']; $i++)
                            @php
                                
                                $date = date('Y-' . $item['mes'] . '-' . $i);
                                $date = date('Y-m-d', strtotime($date));
                                
                            @endphp
                            @php
                                $j = 0;
                                $flag = false;
                                $count = count($collection) - 1;
                                foreach ($collection as $collect) {
                                    if ($collect->date == $date) {
                                        if ($collect->id_user == $users->id) {
                                            $data = TestFacades::countImages($users->id, $collect->all_date, $collectionjustificaciones, $users->id_area, $i, $collect, $collection);
                                            $flag = true;
                                            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                                
                                            echo "<td>$data</td>";
                                            if ($data == 1) {
                                                $asistencia++;
                                            }
                                            if ($data == 2) {
                                                $asistencia++;
                                                $retardo++;
                                            }
                                            if ($data == 0) {
                                                $falta++;
                                            }
                                            if ($data == 3) {
                                                $SoloUnaEvidencia++;
                                            }
                                
                                            break;
                                        }
                                    }
                                    if ($j == $count && $flag == false) {
                                        $a = 0;
                                        if ($i < 10) {
                                            $a = '0' . $i;
                                        } else {
                                            $a = $i;
                                        }
                                        $data = TestFacades::countfaltaYear($i, $collection, $users->id, $collectionjustificaciones, $item, $collect);
                                        echo "<td>$data</td>";
                                        if ($data == 1) {
                                            $asistencia++;
                                        }
                                        if ($data == 2) {
                                            $asistencia++;
                                            $retardo++;
                                        }
                                        if ($data == 0) {
                                            $falta++;
                                        }
                                        if ($data == 3) {
                                            $SoloUnaEvidencia++;
                                        }
                                    }
                                    $j++;
                                }
                            @endphp
                        @endfor

                        @php
                            
                            $contador++;
                            $rcv++;
                            
                        @endphp
                    @endforeach
                    {{-- Conteos --}}
                    <td id="totalAsistencia{{ $users->id }}">{{ $asistencia }}</td>
                    <td>{{ $retardo }}</td>
                    <td>{{ $falta }}</td>
                    <td>{{ $SoloUnaEvidencia }}</td>
                </tr>
            @endforeach

        </div>
    </tbody>
</table>
