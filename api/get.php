<?php

require __DIR__ . '/data/comic.php';
require __DIR__ . '/lib/utils.php';

function showJson($filename) {
    if (file_exists($filename)) {
        readfile($filename);
    } else {
        http_response_code(404);
        echo '{"status": "Not Found"}';
    }
}
$comic = $_GET["comic"];
$chapter = $_GET["chapter"];

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if ($comic) {
    if ($chapter) {
        echo json_encode(getImages($comic, $chapter));
        // showJson(__DIR__ . '/data/' . $comic . '/' . $chapter . '.json');
    } else {
        // showJson(__DIR__ . '/data/' . $comic . '/titles.json');
        showJson(__DIR__ . '/data/' . $comic . '.json');
    }
} else {
    $result = array_map( function($item) {
        // $file = file_get_contents(__DIR__ . "/data/" . $item["key"] . "/titles.json");
        $file = file_get_contents(__DIR__ . "/data/" . $item["key"] . ".json");
        $chapter = json_decode($file);
        $item['last'] = $chapter[0];
        return $item;
    }, $comics);

    echo json_encode($result);
}
