<?php
// Prototype Status Web App
// Author: Jon Eisen (jonathan.eisen@ngc.com)
//
// index.php
// Main status page of ProtoStat
 
// Get Configuration
require "config.php";

// Define Functions
require "functions.php";
require "protostat_functions.php";

// To make a page with the base.php system,
// Me must fill the variables listed in the comments at the top of base.php

// Title and site-wide stuff
$author = "Jon Eisen";

// Footer
$webmaster_link = "<a href='mailto:" . $webmaster_email . "'>". $webmaster . "</a>";
$footline = "This app was developed for for querying the status of the prototype processor." .
		" For questions, please contact the " . $webmaster_link . ".";
$copyright  = "Copyright &copy; Northrop Grumman Electronic Systems 2011";

// Get the host variable as passed in
$host = null;
if (isset($_GET["host"])) {
    $host = $_GET["host"];
}

// Get the list of host names
$system_hosts = get_hosts_from_system($system_type);

// Make the link list
$count = 0;
$menu_links = array();
$menu_links[$count++] = "<a href='/' class='toplevel'>System Overview</a>";
foreach ($system_hosts as $h)
{
	$link = "/" . make_get_args(array("host" => $h));
	if ($host == $h)
	{
		$menu_links[$count++] = "<a href='" . $link . "' class='thispage'>" . $h . "</a>";
	}
	else
	{
		$menu_links[$count++] = "<a href='" . $link . "'>" . $h . "</a>";
	}
}
$menu = "<h1><span>System Menu</span></h1>\n" . get_list_from_array($menu_links);

// Do System Status page or Host Status page?
if (empty($host))
{
	// System status page
	$page_title = "System Status";
	$summary = "This page shows the status of each of this system's hosts.";
	$content = system_stats($system_hosts);
}
elseif (in_array($host, $system_hosts))
{
	// Single Server status page
	$page_title = "Status of " . $host;
	$summary = "This page shows the full status the " . $host . " host.";
	$content = server_stats($host);
}
else
{
	set_error("Host not in system. Did you mean to go to the <a href='/'>system status page</a>? Check argument or contact the webmaster: " . $webmaster_link);
}

// Include the base html file
if (check_error())
{
	require "base.php";
}
?>
