<?php

require __DIR__ . '/simple_html_dom.php';

function getTitles($key) {
    $url = sprintf(URL_TITLE_TEMPLATE, $key);
    // Retrieve the DOM from a given URL
    $html = file_get_html($url);

    // Find the DIV tag with an id of "myId"
    $dataMentah = array();
    foreach($html->find('tr.c3') as $tr) 
    {
        $chapterSrec = $tr->find('a.chaptersrec', 0);

        $href = $chapterSrec->href;
        $leftTrim = strpos($href, 'baca-komik') + strlen($key) + 12;
        $chapter = substr($href, $leftTrim, strpos($href, '-', $leftTrim) - $leftTrim);

        $title = $chapterSrec->innertext;
        $titleImage = strpos($title, '<img'); 
        if (strpos($title, '<img')) {
            $title = substr($title, 0, $titleImage - 1);
        }

        $date = $tr->find('td.c2', 0)->innertext;

        $dataMentah[] = array("chapter" => $chapter, "title" => $title, "date" => $date);
    }

    return $dataMentah;
}

function storeTitles($key, $filename) {
    $dataMentah = getTitles($key);
    $allData = json_encode($dataMentah);

    $fh = fopen($filename,"w");
    fwrite($fh,$allData);
    fclose($fh);

    return $dataMentah;
}

function getImages($comic, $chapter) 
{
    $url = sprintf(URL_IMAGE_TEMPLATE, $comic, $chapter, $chapter + 1, $comic, $chapter);

    $html = file_get_html($url);
    
    $imageArray = [];
    foreach($html->find('div#manga img') as $k=>$e) {
        $imageUrl = $e->src;
        $firstUrl = substr($imageUrl, 0, 4);
        if ($firstUrl != "http") {
            $imageUrl = BASE_DOMAIN . $imageUrl;
        }
        array_push($imageArray, $imageUrl); 
    }

    return $imageArray;
}

function storeImages($comic, $chapter, $dir) 
{
    $filename = $dir . "/" . $chapter . '.json';
    if ( file_exists($filename) ) {
        return;
    }

    $imageArray = getImages($comic, $chapter);
    if (empty($imageArray)) {
        return;
    }
    
    //save the file...
    $fh = fopen($filename, "w");
    fwrite($fh, json_encode($imageArray));
    fclose($fh);
}
