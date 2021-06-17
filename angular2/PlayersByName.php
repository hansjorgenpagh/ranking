<?php
parse_str($_SERVER['QUERY_STRING']);
$con = mysql_connect("localhost","root","maria1772");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("U12", $con);

$arr = array();



$result = mysql_query("SELECT @rownum := @rownum + 1 AS Row , Spillere.* Rank FROM Spillere, (SELECT @rownum := 0) r WHERE Spillere.Aargang>=$min AND Spillere.Aargang<=$max ORDER BY Spillere.Navn ASC");

while($obj = mysql_fetch_object($result)) {
	$obj->Row = (float)$obj->Row; 
	$obj->Rank = (float)$obj->Rank; 
	$obj->Point = (float)$obj->Point; 
	$arr[] = $obj;

}

echo '{"items":'.json_encode($arr).'}';


mysql_close($con);
// some code
?>