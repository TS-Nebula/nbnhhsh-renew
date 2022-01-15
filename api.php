<?php
$method = $_GET['method'];
function GetData($word){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://lab.magiconch.com/api/nbnhhsh/guess");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "text=".$word);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    $trans_array = json_decode($server_output,true);
    $return_array = json_encode($trans_array[0]['trans']);
    echo $return_array;
    $document = ['_id' => new MongoDB\BSON\ObjectID, 'text' => $word,'trans' => $return_array];
    $bulk = new MongoDB\Driver\BulkWrite;
    $_id= $bulk->insert($document);
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $manager->executeBulkWrite('nbnhhsh.nbnhhsh', $bulk, $writeConcern);
}
function GetLocalData($word){
    $m = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $filter = ['text' => $word];
    $options = [
        'projection' => ['_id' => 0],
        'sort' => ['x' => -1],
    ];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $m->executeQuery('nbnhhsh.nbnhhsh', $query);
    $trans_array = '';
    foreach ($cursor as $document) {
        $trans_array = $document->trans;
    }
    if($trans_array != ''){
        $return_array=$trans_array;
        echo $return_array;
    }
    else{
        GetData($word);
    }
}
if ($method == "search")
{
    $want = $_GET['text'];
    GetLocalData($want);
}
