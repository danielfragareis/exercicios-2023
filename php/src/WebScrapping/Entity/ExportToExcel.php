<?php
namespace Chuva\Php\WebScrapping\Entity;
require 'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class ExportToExcel
{
    public static function exportToExcel(array $papers, $filePath)
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray(['ID', 'Title', 'Type', 'Author 1', 'Author 1 Institution', 'Author 2', 'Author 2 Institution', 'Author 3', 'Author 3 Institution', 'Author 4', 'Author 4 Institution', 'Author 5', 'Author 5 Institution', 'Author 6', 'Author 6 Institution', 'Author 7', 'Author 7 Institution']);
        $writer->addRow($headerRow);

        foreach ($papers as $paper) {
            $writer->addRow(WriterEntityFactory::createRowFromArray([$paper->id, $paper->title, $paper->type]));

            $authors = $paper->authors;
            foreach ($authors as $author) {
                $writer->addRow(WriterEntityFactory::createRowFromArray(['', '', '', $author->name, $author->institution]));
            }
        }

        $writer->close();

        echo 'Planilha Excel exportada com sucesso!';
    }
}

// Exemplo de uso:
// $papers = [...]; // Preencha com os dados dos papers
// ExcelExporter::exportToExcel($papers, 'exemplo.xlsx');
?>