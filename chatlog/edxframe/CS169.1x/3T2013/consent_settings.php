<?
function init()
{
    header('Access-Control-Allow-Origin: *');
    return new PDO('mysql:host=localhost;dbname=cs169.1x.T32013.chat;charset=utf8', 'chatconsent', 'gLc4HDUC');
}

function get_group($username)
{
    $hash = sha1('rkdQbjh8JZd4' . $username);
    return (intval($hash[0],16) % 3) + 1;
}
?>