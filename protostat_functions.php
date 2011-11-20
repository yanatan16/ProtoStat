<?php
// Prototype Status Web App
// Author: Jon Eisen (jonathan.eisen@ngc.com)
// 
// protostat_functions.php
// ProtoStat-specific function setup

include('Net/SSH2.php');

// Function get_hosts_from_system
// Get an array of hosts from a system type
// Note: Possible values are mdk, pp1, pp2, js21, tp
// Parameters: 
//	in	$sys - The system type
// Returns: Array of hosts
function get_hosts_from_system($sys)
{
    switch ($sys)
    {
        case "tp":
        case "mdk":
            return array("auxdp", "iadp", "sp1", "sp2", "sp3");
        case "pp1":
            return array("auxdp", "iadp", "sp1", "sp2", "sp3", 
                "cdauxdp", "cdiadp", "sp4", "sp5", "sp6");
        case "pp2":
            return array("auxdp", "iadp", "sp1", "sp2", "sp3", "cdauxdp", "cdiadp");
        case "js21":
            return array("jblade1", "jblade2", "jblade3", "flm1", "flm2", "flm3");
        case "tp":
            return array("auxdp", "iadp", "sp1", "sp2", "sp3", "drex");
        default:
            return null;
    }
}

function ssh_login($ip)
{
    global $username, $password;
    $ssh = new Net_SSH2($ip);
    if (!$ssh->login($username, $password)) {
        return null;
    }
    return $ssh;
}

// Function run_remote_script
// Run a script on a remote host
// Note: The host must have ssh capabilities, and have a no-password rsa key set up for that host.
// Parameters: 
//	in	$ip - The IP address for querying the server
// 	in	$script - The script to run on the remote host
//	in	$ssh - The ssh session (or null)
// Returns: The output of the script
function run_remote_script($ip, $script, $ssh)
{
    global $username, $password;
    if ($ssh == null)
    {
        $ssh = ssh_login($ip);
        if ($ssh == null)
        {
            return null;
        }
    }
    
    return $ssh->exec($script);
}

// Function check_online
// Check whether a server is online
// Parameters: 
//	in	$ip - The IP address for querying the server
// Returns: boolean true if the server is online, false if not.
function check_online($ip)
{
    $ssh = ssh_login($ip);
    if ($ssh == null) {
        return false;
    } else {
        return true;
    }
}

// Function check_idle
// Check whether a server is idle
// Parameters: 
//	in	$ip - The IP address for querying the server
// Returns: boolean true if the server is idle, false if not.
function check_idle($ip)
{
    global $minimum_idle_time;
    $output = run_remote_script($ip, 'who | cut -d" " -f10', null);
    
    if ($output != null)
    {
        $idle_times = preg_split("/\n/", $output);
        $mintime = strtotime($minimum_idle_time);
        if (!$mintime)
        {
            set_error("Minimum Idle Time (" . $minimum_idle_time . ") must be in a valid format!");
        }

        // No open sessions
        foreach ($idle_times as $time)
        {
            $t = strtotime($time);
            if ($t && $t < $mintime)
            {
                return false;
            }
        }
        
        // No running SNA
        $output = run_remote_script($ip, 'ps | grep -v "grep" | grep AceTaoCiao', null);
        if ($output != '')
        {
            return false;
        }
        
        return true;
    } else {
        return false;
    }
}

// Function get_stat_as_table
// Get a table with values from parsing a command run on a remote host
// Parameters: 
//	in	$ip - The IP address for querying the server
// 	in	$stat - The name this status query
// 	in	$stat - The status script to run on the remote host
//	in	$ssh - The ssh session
// Returns: HTML-formatted table
function get_stat_as_table($ip, $statname, $stat, $ssh)
{
    $output = run_remote_script($ip, $stat, $ssh);
    if ($output == null) {
        $output = "SSH Failed";
    }
    $array = array();
    str_arr_split($output, " ", "\n", $array);
    $arr_stat = array(array($statname));
    $array = array_merge($arr_stat, $array);
    return get_table_from_array($array);
}

// Function server_stats
// Get status from a server and return as a list of html-formatted content
// Parameters: 
//	in	$ip - The ip of the remote host
// Returns: List of content
function server_stats($ip)
{
    global $host_queries;
    
    $count = 0;
    $content = array();
    
    $ssh = ssh_login($ip);
    if ($ssh == null)
    {
        return $content;
    }
    
    foreach ($host_queries as $query_name => $query)
    {
        $content[$count++] = get_stat_as_table($ip, $query_name, $query, $ssh);
    }
        
    return $content;
}

// Function bool_to_icon
// Translate a boolean to an html tag for css to put in an icon
// Parameters: 
//	in	$bool - Boolean to represent
// Returns: html tag to be css'ed into an icon
function bool_to_icon($bool)
{
    if ($bool)
    {
        return $str = "<font color='green'>true</font>";
    }
    else
    {
        return $str = "<font color='red'>false</font>";
    }
}

// Function server_stats
// Get status from a list of servers and return as a list of html-formatted content
// Parameters: 
//	in	$ips - The ip of the remote host
// Returns: List of content
function system_stats($ips)
{
    $count = 0;
    $status = array();
    
    $status[$count++] = array("Host", "IP", "Online", "Idle");
    
    foreach ($ips as $ip)
    {
        $link = "<a href='/" . make_get_args(array("host" => $ip)) . "'>" . $ip . "</a>";
        $trueip = gethostbyname($ip);
        $online = bool_to_icon(check_online($ip, 22));
        $idle = bool_to_icon(check_idle($ip));
        
        $status[$count++] = array($link, $trueip, $online, $idle);
    }
    
    return array(get_table_from_array($status));
}

?>