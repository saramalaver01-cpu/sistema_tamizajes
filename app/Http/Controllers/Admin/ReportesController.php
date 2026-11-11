<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EstadisticasService;
use App\Services\ReporteExcel;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController extends Controller
{
    public function excel(
        Request $request,
        EstadisticasService $estadisticas,
        ReporteService $reporte,
        ReporteExcel $excel
    ) {
        $ctx = $estadisticas->contexto($request->query());

        // Si algún filtro no es válido, vuelve a Estadísticas: allí se muestra el motivo
        if ($ctx['errores']) {
            return redirect()->route('admin.estadisticas', $request->query());
        }

        $datos  = $reporte->datos($ctx, $request->user()->name);
        $writer = new Xlsx($excel->generar($datos));

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $this->nombreArchivo($ctx, 'xlsx'),
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    public function pdf(
        Request $request,
        EstadisticasService $estadisticas,
        ReporteService $reporte
    ) {
        $ctx = $estadisticas->contexto($request->query());

        if ($ctx['errores']) {
            return redirect()->route('admin.estadisticas', $request->query());
        }

        $datos = $reporte->datos($ctx, $request->user()->name);

        return Pdf::loadView('admin.reportes.pdf', ['d' => $datos])
            ->setPaper('a4', 'portrait')
            ->download($this->nombreArchivo($ctx, 'pdf'));
    }

    private function nombreArchivo(array $ctx, string $extension): string
    {
        $desde = $ctx['desde'] ? $ctx['desde']->format('Ymd') : 'inicio';
        $hasta = $ctx['hasta'] ? $ctx['hasta']->format('Ymd') : now()->format('Ymd');

        return "reporte_tamizajes_{$desde}_{$hasta}.{$extension}";
    }
}
