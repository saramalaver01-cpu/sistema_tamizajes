<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ReporteExcel
{
    private const MORADO = '5420A8';

    public function generar(array $d): Spreadsheet
    {
        $libro = new Spreadsheet();

        $this->hojaResumen($libro->getActiveSheet(), $d);
        $this->hojaNiveles($libro->createSheet(), $d);
        $this->hojaTendencia($libro->createSheet(), $d);
        $this->hojaSociodemografica($libro->createSheet(), $d);

        $libro->setActiveSheetIndex(0);

        return $libro;
    }

    private function hojaResumen(Worksheet $h, array $d): void
    {
        $h->setTitle('Resumen');

        $this->fila($h, 1, [$d['titulo']]);
        $h->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        $this->fila($h, 3, ['Periodo', $d['periodo']]);
        $this->fila($h, 4, ['Filtros aplicados', $d['filtros'] ?? 'Ninguno']);
        $this->fila($h, 5, ['Generado el', $d['generado_en']]);
        $this->fila($h, 6, ['Generado por', $d['generado_por']]);
        $h->getStyle('A3:A6')->getFont()->setBold(true);

        $this->fila($h, 8, ['Indicadores generales']);
        $h->getStyle('A8')->getFont()->setBold(true)->setSize(12);

        $this->fila($h, 9, ['Total de tamizajes', $d['total']]);
        $this->fila($h, 10, ['Participantes distintos', $d['participantes']]);
        $this->fila($h, 11, ['Tamizajes sin datos del participante', $d['sin_participante']]);

        $this->fila($h, 13, ['Resultados por instrumento']);
        $h->getStyle('A13')->getFont()->setBold(true)->setSize(12);

        $this->encabezado($h, 14, [
            'Instrumento', 'Tamizajes', '% del total', 'Puntaje promedio', 'Puntaje mínimo', 'Puntaje máximo', 'Rango posible',
        ]);

        $fila = 15;
        foreach ($d['modulos'] as $m) {
            $this->fila($h, $fila++, [
                $m['nombre'], $m['total'], $m['porcentaje'], $m['promedio'], $m['minimo'], $m['maximo'], $m['rango'],
            ]);
        }

        $fila++;
        $this->fila($h, $fila++, ['Notas']);
        $h->getStyle('A'.($fila - 1))->getFont()->setBold(true);
        $this->fila($h, $fila++, ['• Los resultados son orientadores y no constituyen un diagnóstico psicológico ni médico.']);
        $this->fila($h, $fila++, ['• Un puntaje mayor indica más riesgo, malestar o carga. Los puntajes no son comparables entre instrumentos (cada uno tiene su propia escala).']);
        $this->fila($h, $fila++, ['• En el Violentómetro el nivel también depende de las preguntas más graves, no solo del puntaje total.']);
        $this->fila($h, $fila++, ['• Información agregada y anonimizada. No incluye datos que permitan identificar a una persona.']);

        $h->getColumnDimension('A')->setWidth(38);
        foreach (['B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $h->getColumnDimension($col)->setWidth(18);
        }
    }

    private function hojaNiveles(Worksheet $h, array $d): void
    {
        $h->setTitle('Niveles por instrumento');

        $fila = 1;

        foreach ($d['distribucion'] as $m) {
            $this->fila($h, $fila, [$m['nombre'].' ('.$m['total'].' tamizajes)']);
            $h->getStyle("A$fila")->getFont()->setBold(true)->setSize(12);
            $fila++;

            $this->encabezado($h, $fila++, ['Nivel', 'Tamizajes', '% del instrumento']);

            foreach ($m['niveles'] as $n) {
                $this->fila($h, $fila++, [$n['etiqueta'], $n['total'], $n['porcentaje']]);
            }

            $fila++;
        }

        $h->getColumnDimension('A')->setWidth(40);
        $h->getColumnDimension('B')->setWidth(14);
        $h->getColumnDimension('C')->setWidth(20);
    }

    private function hojaTendencia(Worksheet $h, array $d): void
    {
        $h->setTitle('Tendencia');

        $fila = 1;

        $this->fila($h, $fila, ['Tamizajes por mes']);
        $h->getStyle("A$fila")->getFont()->setBold(true)->setSize(12);
        $fila++;

        $this->encabezado($h, $fila++, ['Mes', 'Tamizajes']);

        if (! $d['tendencia_mes']) {
            $this->fila($h, $fila++, ['Sin tamizajes en el periodo']);
        }
        foreach ($d['tendencia_mes'] as $f) {
            $this->fila($h, $fila++, [$f['periodo'], $f['total']]);
        }

        $fila += 2;

        $this->fila($h, $fila, ['Tamizajes por día (solo días con actividad)']);
        $h->getStyle("A$fila")->getFont()->setBold(true)->setSize(12);
        $fila++;

        $this->encabezado($h, $fila++, ['Fecha', 'Tamizajes']);

        foreach ($d['tendencia_dia'] as $f) {
            $this->fila($h, $fila++, [$f['periodo'], $f['total']]);
        }

        $h->getColumnDimension('A')->setWidth(26);
        $h->getColumnDimension('B')->setWidth(14);
    }

    private function hojaSociodemografica(Worksheet $h, array $d): void
    {
        $h->setTitle('Sociodemográfico');

        $fila = 1;

        $this->fila($h, $fila++, ['Tamizajes según las características de quienes los respondieron']);
        $h->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $conDatos = $d['total'] - $d['sin_participante'];
        $nota = 'Solo incluye tamizajes con datos del participante ('.$conDatos.' de '.$d['total'].').';
        if ($d['min_grupo'] > 1) {
            $nota .= ' Los grupos con menos de '.$d['min_grupo'].' tamizajes se muestran como «< '.$d['min_grupo'].'» para proteger el anonimato.';
        }
        $this->fila($h, $fila++, [$nota]);
        $fila++;

        foreach ($d['desgloses'] as $bloque) {
            $this->fila($h, $fila, [$bloque['titulo']]);
            $h->getStyle("A$fila")->getFont()->setBold(true);
            $fila++;

            $this->encabezado($h, $fila++, ['Categoría', 'Tamizajes', '%']);

            if (! $bloque['filas']) {
                $this->fila($h, $fila++, ['Sin datos']);
            }

            foreach ($bloque['filas'] as $f) {
                $this->fila($h, $fila++, [$f['categoria'], $f['total'] ?? $f['texto'], $f['porcentaje']]);
            }

            $fila++;
        }

        $h->getColumnDimension('A')->setWidth(42);
        $h->getColumnDimension('B')->setWidth(14);
        $h->getColumnDimension('C')->setWidth(10);
    }



    private function fila(Worksheet $h, int $fila, array $valores): void
    {
        foreach (array_values($valores) as $i => $valor) {
            $celda = Coordinate::stringFromColumnIndex($i + 1).$fila;

            if (is_int($valor) || is_float($valor)) {
                $h->setCellValueExplicit($celda, $valor, DataType::TYPE_NUMERIC);
            } else {
                $h->setCellValueExplicit($celda, $valor === null ? '—' : (string) $valor, DataType::TYPE_STRING);
            }
        }
    }

    private function encabezado(Worksheet $h, int $fila, array $titulos): void
    {
        $this->fila($h, $fila, $titulos);

        $ultima = Coordinate::stringFromColumnIndex(count($titulos));

        $h->getStyle("A{$fila}:{$ultima}{$fila}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::MORADO]],
        ]);
    }
}
