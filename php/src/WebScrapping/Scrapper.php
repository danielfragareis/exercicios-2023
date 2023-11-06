<?php
namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

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

    $papersList = [];


    foreach ($aTags as $aTag) {

      if ($aTag->getAttribute('class') === 'paper-card p-lg bd-gradient-left') {
        $divVolumeInfo = $aTag->getElementsByTagName('div')->item($aTag->getElementsByTagName('div')->length - 1)->nodeValue;
        $university = $aTag->getElementsByTagName('span')->item(0)->getAttribute('title');
        $paperTitle = $aTag->getElementsByTagName('h4')->item(0)->nodeValue;
        $paperType = $aTag->getElementsByTagName('div')->item(1)->nodeValue;
        $authors = $aTag->getElementsByTagName('div')->item(0)->nodeValue;
        $authorsList = [];

        foreach (explode(';', $authors) as $authorInfo) {
          $authorInfo = trim($authorInfo);

          if (!empty($authorInfo)) {
            $authorsList[] = new Person($authorInfo, $university);
          }
        }
        $papersList[] = new Paper($divVolumeInfo, $paperTitle, $paperType, $authorsList);
      }
    }

    return $papersList;
  }
}
?>