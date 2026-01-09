<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;


class PdfExpedienteController extends Controller
{

    public function download($id)
    {
        $expediente = Expediente::with([
            'paciente.persona',
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria',
        ])->findOrFail($id);

        // Logos como base64 (evita rutas externas)
        $logo1 = 'data:image/png;base64,'.base64_encode(@file_get_contents(public_path('images/logo1.png')));
        $logo2 = 'data:image/png;base64,'.base64_encode(@file_get_contents(public_path('images/logo2.png')));

        $pdf = Pdf::loadView('logica.admin.expediente-completo', [
                'expediente' => $expediente,
                'isPdf'      => true,
                'logo1'      => $logo1,
                'logo2'      => $logo2,
            ])
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled'   => true,
                'isRemoteEnabled'        => true,
                'dpi'                    => 96,
                'defaultFont'            => 'DejaVu Sans',
                'isFontSubsettingEnabled'=> true,
                // 'chroot'               => public_path(), // opcional si cargas archivos locales
            ]);

        $p = $expediente->paciente->persona;
        $filename = Str::slug(trim(($p->nombre ?? '').' '.($p->apellido ?? '')) ?: 'paciente')
                    .'_'.$expediente->codigo.'.pdf';

        return $pdf->download($filename);
    }

    public function downloadViaChrome($id)
    {
        $expediente = Expediente::with([
            'paciente.persona','encargado.persona','enfermera','doctor',
            'signosVitales','consulta.examenesMedicos.examen.categoria'
        ])->findOrFail($id);

        $logo1 = $this->base64Image(public_path('images/logo1.png'));
        $logo2 = $this->base64Image(public_path('images/logo2.png'));

        $html = view('logica.admin.expediente-completo', [
            'expediente' => $expediente,
            'isPdf'      => true,
            'logo1'      => $logo1,
            'logo2'      => $logo2,
        ])->render();

        $p = optional(optional($expediente->paciente)->persona);
        $filename = \Illuminate\Support\Str::slug(trim(($p->nombre ?? '').' '.($p->apellido ?? '')) ?: 'Paciente')
                    .'_'.$expediente->codigo.'.pdf';

        $chromePath = env('BROWSERSHOT_CHROME_PATH');            // C:\Program Files\Google\Chrome\Application\chrome.exe
        $nodePath   = env('BROWSERSHOT_NODE_PATH');              // C:\laragon\bin\nodejs\node-v18\node.exe

        return response()->streamDownload(function () use ($html, $chromePath, $nodePath) {
            echo \Spatie\Browsershot\Browsershot::html($html)
                ->setChromePath($chromePath)                    // <- usa Chrome del sistema
                ->setNodeBinary($nodePath)                      // <- usa Node de Laragon
                ->emulateMedia('print')
                ->showBackground()
                ->format('Letter')
                ->margins(0.45, 0.45, 0.45, 0.45, 'cm')
                ->setOption('waitUntil', 'networkidle0')
                ->setOption('args', ['--no-sandbox','--disable-setuid-sandbox'])
                ->timeout(180)                                  // 180s
                ->pdf();
        }, $filename, ['Content-Type' => 'application/pdf']);
    }


    private function b64($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'png';
        $data = @file_get_contents($path);
        return $data ? "data:image/{$ext};base64,".base64_encode($data) : '';
    }

    // Helper privado en el mismo controlador
    private function base64Image(?string $path): ?string
    {
        if (!$path || !is_file($path)) return null;
        $mime = @mime_content_type($path) ?: 'image/png';
        $data = @file_get_contents($path);
        if ($data === false) return null;
        return "data:{$mime};base64,".base64_encode($data);
    }
}

