<?php
namespace Chuva\Php\WebScrapping\Entity;
require 'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Class ExportToExcel
 *
 * Esta classe é responsável por exportar dados para um arquivo Excel (.xlsx) usando a biblioteca Spout.
 * Ela permite exportar uma lista de papers com informações detalhadas, incluindo detalhes dos autores, para um arquivo Excel.
 */

class ExportToExcel
{
    /**
     * Exporta dados para um arquivo Excel.
     *
     * @param array $papers   Um array de objetos Paper contendo as informações a serem exportadas.
     * @param string $filePath O caminho completo do arquivo Excel de destino.
     *
     * @throws \Box\Spout\Common\Exception\IOException Caso haja um erro ao criar ou escrever no arquivo Excel.
     */
    public static function exportToExcel(array $papers, $filePath)
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = ['ID', 'Title', 'Type'];

        foreach (range(1, 18) as $i) {
            $headerRow[] = "Author $i";
            $headerRow[] = "Author ${i} Institution";
        }

        $writer->addRow(WriterEntityFactory::createRowFromArray($headerRow));
        foreach ($papers as $paper) {
            $rowData = [$paper->id, $paper->title, $paper->type];
        
            $authors = $paper->authors;
            foreach ($authors as $author) {
                $rowData[] = $author->name;
                $rowData[] = $author->institution;
            }
        
            $writer->addRow(WriterEntityFactory::createRowFromArray($rowData));
        }

        $writer->close();

        echo 'Planilha Excel exportada com sucesso!';
    }
}
?>