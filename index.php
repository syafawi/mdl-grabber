<?php
require 'vendor/autoload.php';
header("Access-Control-Allow-Origin: *");

use Stichoza\GoogleTranslate\TranslateClient;

$tr = new TranslateClient('en', 'id');
set_time_limit(0);
if (isset($_GET['id'])){
    $id = $_GET['id'];

    function post($id){
        $url = "https://api.mydramalist.com/v1/titles/{$id}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'mdl-api-key: API_KEY'
        ]);
        $result = curl_exec($ch);
            header('Content-Type: application/json');
            
        curl_close($ch);
        // echo $result;
        // trigger_error($result);
        return json_decode($result,true);
    }

    // Cast and Director

    function cast($id){
        $url = "https://api.mydramalist.com/v1/titles/".$id."/credits";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'mdl-api-key: 6e820ce645f4988dc5ec802984bba446dc1668c7'
        ]);
        $result = curl_exec($ch);
            header('Content-Type: application/json');
            
        curl_close($ch);
        // echo $result;
        // trigger_error($result);
        return json_decode($result,true);
    }

    $sinopsis = post($id)['synopsis'];

    $sin = $tr->translate($sinopsis);

    // $astart = post($id)['aired_start']; date("d-m-Y", strtotime(post($id)['aired_start']));
    // $aend = post($id)['aired_end']; date("d-m-Y", strtotime(post($id)['aired_end']));
    
    if (empty(post($id)['released'])) {
        $released = '';
    }else{
         $released = date("d-m-Y", strtotime(post($id)['released']));
    }

    if (empty(post($id)['aired_start'])) {
        $astart = '';
    }else{
        $astart = date("d-m-Y", strtotime(post($id)['aired_start']));
    }

    if (empty(post($id)['aired_end'])) {
        $aend = '';
    }else{
        $aend = date("d-m-Y", strtotime(post($id)['aired_end']));
    }

    if (post($id)['type'] == "Movie") {
        $aired = $released;
    }else if(post($id)['type'] == "Drama"){
        $aired = $astart.' - '.$aend;
        // $aired = date("d-m-Y", strtotime(post($id)['released']));
    }

    $scores = post($id)['rating'];

    $arr = Array (
        "id" => post($id)['id'],
        "title" => post($id)['title'],
        "original_title" => post($id)['original_title'],
        "alt_titles" => post($id)['alt_titles'],
        "images" => post($id)['images']['poster'],
        "permalink" => post($id)['permalink'],
        "episodes" => post($id)['episodes'],
        "year" => post($id)['year'],
        "type" => post($id)['type'],
        "country" => post($id)['country'],
        "synopsis" => $sin,
        "genres" => post($id)['genres'],
        "runtime" => post($id)['runtime'].' min',
        "score" => $scores,
        "rating" => post($id)['certification'],
        "status" => post($id)['status'],
        "air_start" => $astart,
        "air_end" => $aend,
        "aired" => $aired,
        "released" => $released,
        'People' => cast($id)
    );
    $json = json_encode($arr, JSON_PRETTY_PRINT);
    echo($json);

}else{
    echo "<br>";
    echo "Penggunan mdl.syafawi.my.id/?id=(id mydramalist)<br>";
    echo "Contoh pemakaian mdl.syafawi.my.id/?id=13386<br>";
    echo "contoh url mydramalist.com/13386-who-are-you-school-2015 (ID Mydramalist nya adalah 13386)";
}
?>