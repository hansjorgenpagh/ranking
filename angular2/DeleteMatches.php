<?php

include ('output.php');
	
function customError($errno, $errstr)
  {
  //output('<b>Error:</b> [$errno] $errstr');
  //output('Ending Script');
  die();
}

//set_error_handler("customError");
set_time_limit(0);

parse_str($_SERVER['QUERY_STRING']);

$link = mysqli_connect("localhost", "root", "maria1772", "U12");

if (!$link) {
	outputTdColspan('not connected',1);
} else {
	outputTdColspan('Connected',1);
	outputTdColspan('Continuing',1);
}
//if (!$con)
//  {
//  die('Could not connect: ' . mysql_error());
//  }


outputTdColspan('Sletter kampe',1);
outputTdColspan($dato,1);
$result = mysqli_query($link, "DELETE FROM Kampe WHERE Dato<=" . $dato);
//$result = mysqli_query($link, "DELETE FROM KampeTest WHERE Id=18548");
outputTdColspan($result,1);
outputTdColspan(mysqli_error($link));
outputTdColspan('Slettet',1);
mysqli_close($link);
?>