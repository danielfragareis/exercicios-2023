<?php
namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Scraps paper information from HTML and returns an array with the data.
 */
class Scrapper
{

  /**
   * Loads paper information from the HTML and returns an array with the data.
   *
   * @return array of Papers with papers information
   * @param $dom
   */
  public function scrap(\DOMDocument $dom): array
  {
    $aTags = $dom->getElementsByTagName('a');
    //echo 'Debug: Found ' . count($aTags) . ' <a> tags in the document.' . PHP_EOL;
    $papersList = [];


    foreach ($aTags as $aTag) {
      if ($aTag->getAttribute('class') === 'paper-card p-lg bd-gradient-left') {
        $divVolumeInfo = (int) $aTag->getElementsByTagName('div')->item(0)->nodeValue;
        $university = $aTag->getElementsByTagName('span')->item(0)->getAttribute('title');
        $paperTitle = $aTag->getElementsByTagName('h4')->item(0)->nodeValue;
        $paperType = $aTag->getElementsByTagName('div')->item(1)->nodeValue;
        $authors = $aTag->getElementsByTagName('div')->item(2)->nodeValue;

        echo 'Debug: divVolumeInfo: ' . $aTag->getElementsByTagName('div')->item(0)->nodeValue . PHP_EOL;
        echo 'Debug: university: ' . $aTag->getElementsByTagName('span')->item(0)->getAttribute('title') . PHP_EOL;
        echo 'Debug: paperTitle: ' . $aTag->getElementsByTagName('h4')->item(0)->nodeValue . PHP_EOL;
        echo 'Debug: paperType: ' . $aTag->getElementsByTagName('div')->item(1)->nodeValue . PHP_EOL;
        echo 'Debug: authors: ' . $aTag->getElementsByTagName('div')->item(2)->nodeValue . PHP_EOL;
        $authorsList = [];

        foreach (explode(';', $authors) as $authorInfo) {
          echo 'Debug: Found a ";" separator in authors: ' . $authorInfo . PHP_EOL;
          $authorInfo = trim($authorInfo);
          $authorInfoParts = explode(',', $authorInfo, 2);
          if (count($authorInfoParts) >= 2) {
            echo '-------------------------------------------------';
            list($authorName, $university) = $authorInfoParts;
            $authorsList[] = new Person(trim($authorName), trim($university));
          } else {
            // Lida com casos em que as partes não estão disponíveis
            echo 'Debug: Missing author or university information' . PHP_EOL;
          }
          //list($authorName, $university) = explode(',', $authorInfo, 2);
          //$authorsList[] = new Person(trim($authorName), trim($university));
        }

        $papersList[] = new Paper($divVolumeInfo, $paperTitle, $paperType, $authorsList);
      }
    }

    return $papersList;
  }
}
?>