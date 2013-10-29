<?
    include('../consent_settings.php');
    $dbh = init();

    $stmt = $dbh->prepare('SELECT COUNT(*) FROM consent WHERE consentedtime IS NOT NULL');
    $stmt->execute();
    print '<p><b>Number of users who consented:</b> ' . $stmt->fetch()[0] . '</p>';

    $stmt = $dbh->prepare('SELECT COUNT(*) FROM consent WHERE rejectedtime IS NOT NULL');
    $stmt->execute();
    print '<p><b>Number of users who declined:</b> ' . $stmt->fetch()[0] . '</p>';

    $stmt = $dbh->prepare('SELECT COUNT(*) FROM consent WHERE consentedtime IS NULL AND rejectedtime IS NULL AND nextpopuptime IS NOT NULL');
    $stmt->execute();
    print '<p><b>Number of users who deferred until later time:</b> ' . $stmt->fetch()[0] . '</p>';

    $stmt = $dbh->prepare('SELECT COUNT(*) FROM consent');
    $stmt->execute();
    print '<p><b>Total number of users:</b> ' . $stmt->fetch()[0] . '</p>';

    $count_by_group = array();
    $stmt = $dbh->prepare('SELECT username FROM consent WHERE consentedtime IS NOT NULL AND isadmin IS NULL');
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $group = get_group($row['username']);
        $group_by_user[$row['username']] = $group;
        $count_by_group[$group] += 1;
    }

    print '<p><b>All stats below exclude TAs and staff.</b></p>';

    print '<p><b>Users in Group 1 (no chat):</b> ' . $count_by_group[1] . '</p>';
    print '<p><b>Users in Group 2 (chat tab only):</b> ' . $count_by_group[2] . '</p>';
    print '<p><b>Users in Group 3 (embedded chat):</b> ' . $count_by_group[3] . '</p>';

    $file = fopen('/usr/local/ircd-hybrid/logs/activity.log','rt');
    $messages_by_group = array();
    $already_spoke = array();
    while(!feof($file)) { 
        $line = fgets($file);
        if ($line == '') { continue; }
        list($timeUnix, $command, $args) = explode(" ", $line, 3);

        $time = new DateTime();
        $time->setTimestamp($timeUnix);
        $dateNow = new DateTime();
        $di = date_diff($dateNow, $time);
        $timeStr = $time->format('Y-m-d H:i:s');

        $row = '<tr>' . '<td>' . str_replace('-','&#8209;',str_replace(' ','&nbsp;',$timeStr)) . '</td>';

        if ($command == 'PRIVMSG') {
            list($nick, $target, $message) = explode(" ", $args, 3);
            $nick = preg_replace('/_*$/', '', $nick);
            if ($target == '#cs1691x') {
		if (array_key_exists($nick, $group_by_user)) {
		    $messages_by_group[$group_by_user[$nick]] += 1;
		}
		if (!$already_spoke[$nick]) {
		    $active_users_by_group[$group_by_user[$nick]] += 1;
		    $already_spoke[$nick] = 1;
		}
            }
        }
    }

    for ($i=1; $i <= 3; $i++) {
        print '<p><b>Active users in Group ' . $i . ':</b> ' . $active_users_by_group[$i] . '</p>';
    }

    for ($i=1; $i <= 3; $i++) {
        print '<p><b>Messages from users in Group ' . $i . ':</b> ' . $messages_by_group[$i] . '</p>';
    }

    fclose($file);
?>