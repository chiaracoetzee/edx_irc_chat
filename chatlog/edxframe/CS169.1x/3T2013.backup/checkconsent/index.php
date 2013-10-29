<?
    header('Access-Control-Allow-Origin: *');
    $username = $_GET['username'];
    if ($username == 'tansaku' || $username == 'dcoetzee') {
        echo 'jsonCallback({ "chatconsented": true });';
    } else {
        echo 'jsonCallback({ "chatconsented": false });';
    }
?>