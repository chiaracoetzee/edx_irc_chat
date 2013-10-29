<?
    include('../consent_settings.php');
    $dbh = init();
    $username = $_GET['username'];

    $stmt = $dbh->prepare('SELECT nextpopuptime,consentedtime,rejectedtime,isadmin FROM consent WHERE username=:username');
    $stmt->execute(array(':username' => $username));
    $result = $stmt->fetch();
    if ($result) {
        $stmt = $dbh->prepare('UPDATE consent SET isadmin=1 WHERE username=:username');
        $stmt->execute(array(':username' => $username));
        echo 'User "' . $username . '" has been made an admin and can access the chat tab and embedded chat freely.';
    } else {
        $stmt = $dbh->prepare('INSERT INTO consent(username,nextpopuptime,consentedtime,rejectedtime,isadmin) VALUES (:username,NULL,NULL,NULL,1)');
        $stmt->execute(array(':username' => $username));
        echo 'User "' . $username . '" has been made an admin and can access the chat tab and embedded chat freely as soon as they complete the consent form.';
    }
?>