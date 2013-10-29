<?
    include('../consent_settings.php');
    $dbh = init();
    $username = $_GET['username'];
    $group = get_group($username);
    $stmt = $dbh->prepare('SELECT nextpopuptime,consentedtime,rejectedtime,isadmin FROM consent WHERE username=:username');
    $stmt->execute(array(':username' => $username));
    $result = $stmt->fetch();
    if ($result) {
        $now = new DateTime("now");
	$is_time_for_next_popup = ($result["nextpopuptime"] == null) || ($now >= new DateTime($result["nextpopuptime"]));
        $arr = array("chatconsented" => false, "shownextpopup" => $is_time_for_next_popup, "consented" => $result["consentedtime"] != null, "rejected" => $result["rejectedtime"] != null, "isadmin" => $result["isadmin"], "group" => $group);
    } else {
        $stmt = $dbh->prepare('INSERT INTO consent(username,nextpopuptime,consentedtime,rejectedtime) VALUES (:username,NULL,NULL,NULL)');
        $stmt->execute(array(':username' => $username));
        $arr = array("chatconsented" => false, "shownextpopup" => true, "consented" => false, "rejected" => false, "isadmin" => false, "group" => $group);
    }
    echo 'jsonCallback(' . json_encode($arr) . ');';
?>