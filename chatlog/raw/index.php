<?php

ini_set('auto_detect_line_endings',true);

$get_channel = $_GET['channel'];
# Security warning: without this, could expose private messages
if ($get_channel[0] != '#') {
    print('ERROR: # was not supplied in channel name');
    return;
}
$file = fopen('/usr/local/ircd-hybrid/logs/activity.log','rt');
fseek($file, -500000, SEEK_END);
while(!feof($file)) { 
    $line = fgets($file);
    if ($line == '') { continue; }
    list($timeUnix, $command, $args) = explode(" ", $line, 3);

    $time = new DateTime();
    $time->setTimestamp($timeUnix);
    $dateNow = new DateTime();
    $di = date_diff($dateNow, $time);
    if (!($di->y == 0 && $di->m == 0 && $di->d == 0 && $di->h < 24)) {
        continue;
    }

    if ($command == 'CONNECT') {
        # echo $line;
    } else if ($command == 'JOIN') {
        # list($channel, $nick) = explode(" ", $args, 2);
	# if ($channel == $get_channel) {
            # echo $line;
        # }
    } else if ($command == 'PART') {
        # list($channel, $rest) = explode(" ", $args, 2);
	# if ($channel == $get_channel) {
	    # echo $line;
        # }
    } else if ($command == 'QUIT') {
	# echo $line;
    } else if ($command == 'PRIVMSG') {
        list($nick, $target, $message) = explode(" ", $args, 3);
	if ($target == $get_channel) {
	    echo $line;
        }
    }
}
fclose($file);

?>