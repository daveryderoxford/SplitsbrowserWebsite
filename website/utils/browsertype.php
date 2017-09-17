<?
function getBrowser() {
    if (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != "")) {
        $visitor_user_agent = $_SERVER["HTTP_USER_AGENT"];
    } else {
        $visitor_user_agent = "Unknown";
    }
    $bname = 'Unknown';
    $version = "0.0.0";

    // Next get the name of the useragent yes seperately and for good reason
    if (eregi('MSIE', $visitor_user_agent) && !eregi('Opera', $visitor_user_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (eregi('Firefox', $visitor_user_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (eregi('Chrome', $visitor_user_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (eregi('Safari', $visitor_user_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (eregi('Opera', $visitor_user_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (eregi('Netscape', $visitor_user_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (eregi('Seamonkey', $visitor_user_agent)) {
        $bname = 'Seamonkey';
        $ub = "Seamonkey";
    } elseif (eregi('Konqueror', $visitor_user_agent)) {
        $bname = 'Konqueror';
        $ub = "Konqueror";
    } elseif (eregi('Navigator', $visitor_user_agent)) {
        $bname = 'Navigator';
        $ub = "Navigator";
    } elseif (eregi('Mosaic', $visitor_user_agent)) {
        $bname = 'Mosaic';
        $ub = "Mosaic";
    } elseif (eregi('Lynx', $visitor_user_agent)) {
        $bname = 'Lynx';
        $ub = "Lynx";
    } elseif (eregi('Amaya', $visitor_user_agent)) {
        $bname = 'Amaya';
        $ub = "Amaya";
    } elseif (eregi('Omniweb', $visitor_user_agent)) {
        $bname = 'Omniweb';
        $ub = "Omniweb";
    } elseif (eregi('Avant', $visitor_user_agent)) {
        $bname = 'Avant';
        $ub = "Avant";
    } elseif (eregi('Camino', $visitor_user_agent)) {
        $bname = 'Camino';
        $ub = "Camino";
    } elseif (eregi('Flock', $visitor_user_agent)) {
        $bname = 'Flock';
        $ub = "Flock";
    } elseif (eregi('AOL', $visitor_user_agent)) {
        $bname = 'AOL';
        $ub = "AOL";
    } elseif (eregi('AIR', $visitor_user_agent)) {
        $bname = 'AIR';
        $ub = "AIR";
    } elseif (eregi('Fluid', $visitor_user_agent)) {
        $bname = 'Fluid';
        $ub = "Fluid";
    } else {
        $bname = 'Unknown';
        $ub = "Unknown";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $visitor_user_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($visitor_user_agent, "Version") < strripos($visitor_user_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $visitor_user_agent,
        'name' => $bname,
        'version' => $version,
        'pattern' => $pattern
    );
}

function getOS() {
    if (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != "")) {
        $visitor_user_agent = $_SERVER["HTTP_USER_AGENT"];
    } else {
        $visitor_user_agent = "Unknown";
    }
    // Create list of operating systems with operating system name as array key
    $oses = array(
        'Mac OS X(Apple)' => '(iPhone)|(iPad)|(iPod)|(MAC OS X)|(OS X)',
        'Apple\'s mobile/tablet' => 'iOS',
        'BlackBerry' => 'BlackBerry',
        'Android' => 'Android',
        'Java Mobile Phones (J2ME)' => '(J2ME/MIDP)|(J2ME)',
        'Java Mobile Phones (JME)' => 'JavaME',
        'JavaFX Mobile Phones' => 'JavaFX',
        'Windows Mobile Phones' => '(WinCE)|(Windows CE)',
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
        'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD' => 'OpenBSD',
        'Sun OS' => 'SunOS',
        'Linux' => '(Linux)|(X11)',
        'Macintosh' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS/2' => 'OS/2',
        'ROBOT' => '(Spider)|(Bot)|(Ezooms)|(YandexBot)|(AhrefsBot)|(nuhk)|
                    (Googlebot)|(bingbot)|(Yahoo)|(Lycos)|(Scooter)|
                    (AltaVista)|(Gigabot)|(Googlebot-Mobile)|(Yammybot)|
                    (Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)|
                    (Ask Jeeves/Teoma)|(Java/1.6.0_04)'
    );
    foreach ($oses as $os => $pattern) {
        if (eregi($pattern, $visitor_user_agent)) {
            return $os;
        }
    }
    return 'Unknown';
}
?>

