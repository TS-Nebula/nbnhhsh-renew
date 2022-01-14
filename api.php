<?php
$method = $_GET['method'];
$qwq = 'fbk';
$collections = 'hhsh';
function init($collections){
    $m = new MongoClient();
    $db = $m->nbnhhsh;
    $collection = $db->createCollection($collections);
}
function GETDATA($word,$collections){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://lab.magiconch.com/api/nbnhhsh/guess");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "text=".$word);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    $trans_array = json_decode($server_output,true);
    //print_r($trans_array[0]['trans']);
    $return_array = json_encode($trans_array[0]['trans']);
    echo $return_array;
    $m = new MongoClient();
    $db = $m->nbnhhsh;
    $collection = $db->$collections;
    $document = array(
        "text" => $word,
        "trans" => $return_array
    );
    $collection->insert($document);
}
function GetLocalData($word,$collections){
    $m = new MongoClient();
    $db = $m->nbnhhsh;
    $collection = $db->$collections;
    $cursor = $collection->find();
    foreach ($cursor as $document) {
        $trans_array=$document['trans'];
    }
    if($trans_array != ''){
        $return_array=$trans_array;
        printf($trans_array);
        echo $return_array;
    }
    else{
        GETDATA($word,$collections);
    }
}
if ($method == "search")
{
    $want = $_GET['text'];
    GetLocalData($want,$collections);
}
