<?php
$method = $_GET['method'];
$qwq = 'fbk';
function GETDATA($word){
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
}
if ($method == "search")
{
    $want = $_GET['text'];
    GETDATA($want);

}
