<!-- 
	ProtoStat
	Author: Jon Eisen (jonathan.eisen@ngc.com)
	
	Base html page
	This is the base html page. 
	Any page simply needs to fill a few variables to display content
	and include this file at the bottom.
	
	$site_title - Title of the web site
	$tagline - Tagline of the web site
	$author - Author of the web page
	$css - Link to CSS file
	$page_title - Title of the page
	$summary - Summary for this page
	$content - Content for this page
	$footline - Footer comment
	$copyright - Copyright line
	$menu - List of links for the menu
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="<? echo $author; ?>" />
	<meta name="description" content="<? echo $tagline; ?>" />

	<title><? echo $site_title . " - " . $tagline; ?></title>

	<!-- to correct the unsightly Flash of Unstyled Content. http://www.bluerobot.com/web/css/fouc.asp -->
	<script type="text/javascript"></script>
	
	<style type="text/css" media="all">
		@import "<? echo $css; ?>";
	</style>
	
</head>

<body onload="window.defaultStatus='<? echo $site_title . " - " . $tagline; ?>';" id="proto-stat">

<div id="container">
	<div id="header">
		<h1><span><? echo $site_title; ?></span></h1>
		<h2><span><? echo $page_title; ?></span></h2>
	</div>

	<div id="summary">
		<p><span><? echo $summary ?></span></p>
	</div>

	<div id="content">
		<? 
                    foreach ($content as $paragraph) 
                    { 
                        echo "<p><span>" . $paragraph . "</span></p>\n";
                    }
                ?>
	</div>

	<div id="footer">
		<p><span><? echo $footline; ?></span></p> &nbsp; 
		<p><span><? echo $copyright; ?></span></p>
	</div>
	
	<div id="linkList">
		<? echo $menu ?>
	</div>

</div>

<div id="extraDiv"><span></span></div>

</body>
</html>
