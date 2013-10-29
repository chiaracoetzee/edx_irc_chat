<?
    include('../consent_settings.php');
    $dbh = init();
    $username = $_GET['username'];
    $stmt = $dbh->prepare('UPDATE consent SET rejectedtime=NOW() WHERE username=:username');
    $stmt->execute(array(':username' => $username));
?>