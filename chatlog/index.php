<?php

ini_set('auto_detect_line_endings',true);

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

$get_channel = $_GET['channel'];
# Security warning: without this, could expose private messages
if ($get_channel[0] != '#') {
    print('ERROR: # was not supplied in channel name');
    return;
}

echo '<html><head><title>CS169.2x IRC chat log</title></head></html>';

echo '<body><h1>CS169.2x IRC chat log</h1><table width="100%">';

if ($_GET['date']) {
   if ($_GET['date'] == 'all') {
       $date = null;
       echo '<p><a href=".">View logs restricted to single days</a></p>';
   } else if ($_GET['date'] == 'recent') {
       $date = 'recent';
       echo '<p><a href=".">View older logs</a></p>';
   } else {
       $date = new DateTime($_GET['date']);
       echo '<p><a href=".">View logs from other days</a></p>';
   }
} else {
   echo '<form name="input" action="." method="get">';
   $date = new DateTime();
   echo 'Date to show logs for (<i>all</i> for all, <i>recent</i> for last 8 hours): <input type="text" name="date" value="' . $date->format('Y-m-d') . '"><br/>';
   echo '<input type="submit" value="Submit">';
   echo '</form>';
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
    if ($date != null && $date != 'recent' && $time->format('Y-m-d') != $date->format('Y-m-d')) {
        continue;
    }
    $dateNow = new DateTime();
    $di = date_diff($dateNow, $time);
    # if ($date == 'recent' && !($di->y == 0 && $di->m == 0 && $di->d < 3)) {
    #     continue;
    # }
    $timeStr = $time->format('Y-m-d H:i:s');

    $row = '<tr>' . '<td>' . str_replace('-','&#8209;',str_replace(' ','&nbsp;',$timeStr)) . '</td>';

    if (0 && $command == 'JOIN') {
        # list($channel, $nick) = explode(" ", $args, 2);
        # $row .= '<td>' . htmlspecialchars($nick) . ' has joined ' . htmlspecialchars($channel) . '</td>';
    } else if (0 && $command == 'PART') {
        # list($channel, $nick, $message) = explode(" ", $args, 3);
        # $row .= '<td>' . htmlspecialchars($nick) . ' has left ' . htmlspecialchars($channel);
	# if (trim($message) != '') {
	#     $row .= ' (' . htmlspecialchars($message) . ')';
        # }
	# $row .= '</td>';
    } else if (0 && $command == 'QUIT') {
        # list($nick, $message) = explode(" ", $args, 2);
        # $row .= '<td>' . htmlspecialchars($nick) . ' has quit IRC';
	# if ($message != '') {
	#     $row .= ': ' . htmlspecialchars($message);
        # }
	# $row .= '</td>';
    } else if ($command == 'PRIVMSG') {
        list($nick, $target, $message) = explode(" ", $args, 3);
	if ($target == $get_channel) {
	   if (startsWith($message, "\1ACTION ")) {
	       list($action, $message) = explode(" ", str_replace("\1", '', $message), 2);
               $row .= '<td>' . htmlspecialchars($nick) . ' ' . htmlspecialchars($message) . '</td>';
           } else {
	       $row .= '<td>' . '&lt;' . htmlspecialchars($nick) . '&gt; ' . htmlspecialchars($message) . '</td>';
           }
	} else {
	   $row = '';
	}
    } else {
        $row = '';
    }

    if ($row != '') {
        $row .= '</tr>';
    }

    echo($row);
}
fclose($file);

echo '</table><a name="bottom" /></body>';

?>