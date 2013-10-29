<?
    include('../consent_settings.php');
    $dbh = init();
    $username = $_GET['username'];
    $stmt = $dbh->prepare('UPDATE consent SET consentedtime=NULL WHERE username=:username');
    $stmt->execute(array(':username' => $username));
    $stmt = $dbh->prepare('UPDATE consent SET rejectedtime=NULL WHERE username=:username');
    $stmt->execute(array(':username' => $username));
    $stmt = $dbh->prepare('UPDATE consent SET nextpopuptime=NULL WHERE username=:username');
    $stmt->execute(array(':username' => $username));
?>