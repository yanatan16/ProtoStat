<?php

// Prototype Status Web App
// Author: Jon Eisen (jonathan.eisen@ngc.com)
//
// config.php
// Configuration of ProtoStat
// To configure ProtoStat, fill in the following global variables.

//----- System Configuration ------

// Type of system - mdk, pp1, pp2, tp
// NOTE: If you need to add a system_type, go to protostat_functions.php: get_hosts_from_system()
$system_type = "pp1";

// Username to log on to all remote systems with
// NOTE: Make sure an rsa key is setup between 
//  the webservers user and the remote hosts.
$username = "ituser";
$password = "ituser"; //TODO upgrade to RSA

// A string representation of the time before a host is declared "idle"
$minimum_idle_time = "00:20";


//----- Site Configuration ------

// Site title and tagline
$site_title = "ProtoStat (BETA)";
$tagline = "Query the status of the Prototype Processor";

// Webmaster information
$webmaster = "Jon Eisen";
$webmaster_email = "jonathan.eisen@ngc.com";

// Site CSS file
$css = "protostat.css";

//------ Information queries ------
// These are the commands to run on the remote hosts
$host_queries = array(
    'Date'                  => 'date', 
    'SNA Version'           => 'cat /usr/share/SNA/VERSION',
    'CEEL Version'          => 'cat /etc/version',
    'Uptime'                => 'uptime', 
    'Sessions'              => 'who', 
    'Memory'                => 'free', 
    'Processes'             => 'ps | grep -v root | grep -v ps',
    'Network Connections'   => 'netstat',
    'Filesystem'            => 'df'
    );

?>
