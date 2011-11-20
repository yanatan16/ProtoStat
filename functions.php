<?php
// Prototype Status Web App
// Author: Jon Eisen (jonathan.eisen@ngc.com)
// 
// functions.php
// Function setup. These functions will be used in the prototype status web app

// Function check_error
// Set the error string.
// Parameters:
//	in	$str - Error string
// Returns: void
function set_error($str)
{
	global $error;
	$error = $str;
}

// Function check_error
// Check if there was an error creating this page. Print that error if it is there.
// Parameters: <none>
// Returns: boolean true if no error, false if there was an error.
function check_error()
{
	global $error;
	if (empty($error))
	{
		return true;
	}
	else
	{
		echo "<div id='error'><p><span>ERROR: " . $error . "</span></p></div>";
		return false;
	}
}

// Function str_arr_split
// Parse a two-dimensional array passed in as a string and out as an array
// Parameters: 
//	in	$str - The input array as a string
//	in	$delim_elem - The element delimeter in the array
//  in	$delim_row - The row delimeter in the array
//	out	$arr - The output array as a php array
// Returns: void
function str_arr_split($str, $delim_elem, $delim_row, &$arr)
{
	$temp = preg_split("/" . $delim_row . "/", $str, -1, PREG_SPLIT_NO_EMPTY);
	$count = 0;
	foreach ($temp as $row)
	{
		$arr[$count++] = preg_split("/" . $delim_elem . "/", $row, -1, PREG_SPLIT_NO_EMPTY);
	}
}

// Function get_table_from_array
// Get a table with values from a two-dimensional string array
// Parameters: 
//	in	$arr - The input string array
// Returns: HTML table
function get_table_from_array($arr)
{
	// Setup
	$table_start = "<table>\n";
	$table_stop = "</table>\n";
	$row_start = "\t<tr>\n";
	$row_stop = "\t</tr>\n";
	$elem_start = "\t\t<td>";
	$elem_stop = "</td>\n";
        $toplevel_elem_start = "\t\t<td class='toplevel'>";
	
	// Now parse and display
	$outstr = "";
	$outstr .= $table_start;
        $first = true;
	foreach ($arr as $row)
	{
		$outstr .= $row_start;
		foreach ($row as $elem)
		{
                    if ($first)
                    {
                        $outstr .= $toplevel_elem_start;
                    }
                    else
                    {
                        $outstr .= $elem_start;
                    }
                    $outstr .= $elem . $elem_stop;
		}
		$outstr .= $row_stop;
                $first = false;
	}
	$outstr .= $table_stop;
        return $outstr;
}

// Function get_list_from_array
// Get a list with values from a one-dimensional string array
// Parameters: 
//	in	$arr - The input string array
// Returns: The list as a string
function get_list_from_array($arr)
{
	// Setup
	$list_start = "<ul>\n";
	$list_stop = "</ul>\n";
	$item_start = "\t<li>";
	$item_stop = "</li>\n";
	
	// Now parse and display
	$outstr = "";
	$outstr .= $list_start;
	foreach ($arr as $item)
	{
		$outstr .= $item_start . $item . $item_stop;
	}
	$outstr .= $list_stop;
	
	return $outstr;
}

// Function make_get_args
// Make the arguments for a GET link
// Parameters: 
//	in	$arr - A map of argument keys to values
// Returns: string of arguments for a link
function make_get_args($arr)
{
        global $thispage;
	$first = true;
	$str = $thispage;
	foreach ($arr as $key => $val)
	{
		if ($first)
		{
			$str .= "?";
			$first = false;
		} 
		else
		{
			$str .= "&";
		}
		
		$str .= $key . "=" . $val;
	}
        return $str;
}