<?php
$page = "Logger";
$user_agent = $_SERVER['HTTP_USER_AGENT'];
date_default_timezone_set('Asia/Kolkata');

function getOS()
{
    global $user_agent;
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser()
{
    global $user_agent;
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;
    return $browser;
}
function ip_get($allow_private = false)
{
    $proxy_ip = ['127.0.0.1'];
    $header = 'HTTP_X_FORWARDED_FOR';
    
    
    if (isset($_SERVER[$header])) {

        $chain = array_reverse(preg_split('/\s*,\s*/', $_SERVER[$header]));
        foreach ($chain as $ip)
            if (ip_check($ip, $allow_private, $proxy_ip))
                return $ip;
    }
    echo 'IP Check: '. ip_check($_SERVER['REMOTE_ADDR'], $allow_private, $proxy_ip).'<br>';
    if (ip_check($_SERVER['REMOTE_ADDR'], $allow_private, $proxy_ip))
        return $_SERVER['REMOTE_ADDR'];
    return $_SERVER['REMOTE_ADDR'];
}

function ip_check($ip, $allow_private = false, $proxy_ip = [])
{
    if (!is_string($ip) || is_array($proxy_ip) && in_array($ip, $proxy_ip))
        return false;
    $filter_flag = FILTER_FLAG_NO_RES_RANGE;
    if (!$allow_private) {
        if (preg_match('/^127\.$/', $ip))
            return false;
        $filter_flag |= FILTER_FLAG_NO_PRIV_RANGE;
    }
    return filter_var($ip, FILTER_VALIDATE_IP, $filter_flag) !== false;
}

try {
    $user_os = getOS();
    $user_browser = getBrowser();
    $cname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    echo 'CName: '.$cname.'<br>';
    echo 'Hostname: '.$_SERVER['REMOTE_ADDR'].'<br>';
    echo "HTTP_X_FORWARDED_FOR: ".$_SERVER['HTTP_X_FORWARDED_FOR']."<br>";
    $ip = ip_get();
    echo 'IP: '.$ip;
    $json = file_get_contents("http://ip-api.com/json/$ip");
    $json = json_decode($json, true);
    $ip = $json['query'];
    $country = $json['country'];
    $region = $json['regionName'];
    $city = $json['city'];
    $zip = $json['zip'];
    $isp = $json['isp'];
    $servername = "srv677.hstgr.io";
    $username = "u117204720_analytics";
    $password = "ex46Z>n?";
    $dbname = "u117204720_analytics";
    // $conn = new mysqli($servername, $username, $password, $dbname);
    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // }
    // $sql = "INSERT INTO track_log (user_count, date_time, page, hostname, ip, isp, city, region, country, zip, browser, os) VALUES (null,"
    //     . "'" . mysqli_real_escape_string($conn, date('Y-m-d H:i:s')) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $page) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $cname) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $ip) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $isp) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $city) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $region) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $country) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $zip) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $user_browser) . "', "
    //     . "'" . mysqli_real_escape_string($conn, $user_os). "');";
    // $conn->query($sql);
    // $conn->close();
} catch (Exception $e) {
}
?>