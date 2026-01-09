<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExpedientesExamenesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportacionExpedienteController extends Controller
{
    public function exportar()
    {
        return Excel::download(new ExpedientesExamenesExport, 
            'expedientes_examenes.xlsx'
        );
    }
}