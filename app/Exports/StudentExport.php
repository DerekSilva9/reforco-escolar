<?php

namespace App\Exports;

use App\Models\Student;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\BorderName;
use OpenSpout\Common\Entity\Style\BorderStyle;
use OpenSpout\Common\Entity\Style\BorderWidth;

class StudentExport
{
    protected $teamId;

    public function __construct($teamId = null)
    {
        $this->teamId = $teamId;
    }

    public function export($filePath)
    {
        $writer = new Writer();
        $writer->openToFile($filePath);

        // Criar bordas negras para headers
        $headerBorderParts = [
            new BorderPart(BorderName::BOTTOM, Color::BLACK, BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::TOP, Color::BLACK, BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::LEFT, Color::BLACK, BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::RIGHT, Color::BLACK, BorderWidth::THIN, BorderStyle::SOLID),
        ];
        $headerBorder = new Border(...$headerBorderParts);

        // Estilo para headers (Azul com texto branco)
        $headerStyle = new Style(
            fontBold: true,
            fontSize: 12,
            fontColor: Color::WHITE,
            backgroundColor: Color::BLUE,
            cellAlignment: CellAlignment::CENTER,
            border: $headerBorder,
        );

        // Bordas cinzas para dados
        $dataBorderParts = [
            new BorderPart(BorderName::BOTTOM, Color::rgb(192, 192, 192), BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::TOP, Color::rgb(192, 192, 192), BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::LEFT, Color::rgb(192, 192, 192), BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::RIGHT, Color::rgb(192, 192, 192), BorderWidth::THIN, BorderStyle::SOLID),
        ];
        $dataBorder = new Border(...$dataBorderParts);

        // Estilo para dados normais
        $dataStyle = new Style(
            border: $dataBorder,
            cellAlignment: CellAlignment::LEFT,
        );

        // Estilo para dados alternados (fundo claro)
        $alternateStyle = new Style(
            backgroundColor: Color::rgb(245, 245, 245),
            cellAlignment: CellAlignment::LEFT,
            border: $dataBorder,
        );

        // Adicionar headers
        $headers = [
            'Nome do Aluno',
            'Turma',
            'Horário',
            'Ano Escolar',
            'Responsável',
            'Telefone',
            'Data de Nascimento',
            'Mensalidade (R$)',
            'Status',
        ];

        $headerCells = array_map(fn ($header) => new StringCell($header, $headerStyle), $headers);
        $writer->addRow(new Row($headerCells));

        // Buscar e adicionar dados
        $query = Student::query()
            ->with(['team', 'responsavel']);

        if ($this->teamId) {
            $query->where('team_id', $this->teamId);
        }

        $students = $query->orderBy('active', 'desc')
            ->orderBy('name')
            ->get();

        $rowIndex = 0;
        foreach ($students as $student) {
            $row = [
                $student->name,
                $student->team?->name ?? '-',
                $student->team?->time ?? '-',
                $student->school_year ?? '-',
                $student->responsavel?->name ?? $student->parent_name ?? '-',
                $student->responsavel?->phone ?? $student->phone ?? '-',
                $student->birth_date ? $student->birth_date->format('d/m/Y') : '-',
                number_format((float) $student->fee, 2, ',', '.'),
                $student->active ? 'Ativo' : 'Inativo',
            ];
            
            // Alternar cores das linhas
            $currentStyle = $rowIndex % 2 === 0 ? $dataStyle : $alternateStyle;
            $cells = array_map(fn ($cell) => new StringCell((string) $cell, $currentStyle), $row);
            $writer->addRow(new Row($cells));
            
            $rowIndex++;
        }

        // Ajustar largura das colunas
        $sheet = $writer->getCurrentSheet();
        $columnWidths = [25, 15, 12, 15, 25, 15, 18, 18, 12];
        
        foreach ($columnWidths as $index => $width) {
            $sheet->setColumnWidth($width, $index + 1);
        }

        $writer->close();
    }
}
