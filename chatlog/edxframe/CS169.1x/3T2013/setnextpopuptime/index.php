<?
    include('../consent_settings.php');
    $dbh = init();
    $username = $_GET['username'];
    $secs_from_now = $_GET['secsfromnow'];
    $nextpopuptime = new DateTime("now");
    $nextpopuptime->add(new DateInterval('PT' . $secs_from_now . 'S'));
    $stmt = $dbh->prepare('UPDATE consent SET nextpopuptime=:nextpopuptime WHERE username=:username');
    $stmt->execute(array(':username' => $username, ':nextpopuptime' => $nextpopuptime->format('Y-m-d H:i:s')));
?>