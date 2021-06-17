<?php
parse_str($_SERVER['QUERY_STRING']);
$link = mysqli_connect("localhost", "root", "maria1772", $database);
mysqli_set_charset('utf8',$link);
if ($licens=="") {
$players = mysqli_query($link, "SELECT * FROM (SELECT Kampe.Id Id,Spillere.Navn Spiller1,Spillere.Klub Klub1,Spillere.Point Spiller1Point,Spillere.Licens Licens1,Point1,Point2,Dato,Resultat,Valid,Comment FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller1 and Dato<='$max' and Dato>='$min')  Tabel1 LEFT JOIN (SELECT Kampe.Id Id2,Spillere.Navn Spiller2,Spillere.Klub Klub2,Spillere.Point Spiller2Point,Spillere.Licens Licens2 FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller2) Tabel2 ON Tabel1.Id=Tabel2.Id2");
} else {
$players = mysqli_query($link, "SELECT * FROM (SELECT Kampe.Id Id,Spillere.Navn Spiller1,Spillere.Klub Klub1,Spillere.Point Spiller1Point,Spillere.Licens Licens1,Point1,Point2,Dato,Resultat,Valid,Comment FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller1 and Dato<='$max' and Dato>='$min')  Tabel1 LEFT JOIN (SELECT Kampe.Id Id2,Spillere.Navn Spiller2,Spillere.Klub Klub2,Spillere.Point Spiller2Point,Spillere.Licens Licens2 FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller2) Tabel2 ON Tabel1.Id=Tabel2.Id2 WHERE Licens1='$licens'
Union
SELECT * FROM (SELECT Kampe.Id Id,Spillere.Navn Spiller1,Spillere.Klub Klub1,Spillere.Point Spiller1Point,Spillere.Licens Licens1,Point1,Point2,Dato,Resultat,Valid,Comment FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller1 and Dato<='$max' and Dato>='$min')  Tabel1 LEFT JOIN (SELECT Kampe.Id Id2,Spillere.Navn Spiller2,Spillere.Klub Klub2,Spillere.Point Spiller2Point,Spillere.Licens Licens2 FROM `Kampe`,Spillere WHERE Spillere.Id=Kampe.Spiller2) Tabel2 ON Tabel1.Id=Tabel2.Id2 WHERE Licens2='$licens'
");
}
header('Content-type: text/html; charset=utf-8');
$arr = array();

while($obj = mysqli_fetch_object($players)) {
	if ($includeMatchPoint=="true") {
	$obj->Spiller1Point = (float)$obj->Spiller1Point; 
	$obj->Spiller2Point = (float)$obj->Spiller2Point;
	if ($obj->Point1 > $obj->Point2) {
		$obj->Spiller1MatchPoint = 32*(1-1/(1+pow(10,($obj->Spiller2Point - $obj->Spiller1Point)/200)));
		$obj->Spiller2MatchPoint = 32*(0-1/(1+pow(10,($obj->Spiller1Point - $obj->Spiller2Point)/200)));
	} else {
		$obj->Spiller1MatchPoint = 32*(0-1/(1+pow(10,($obj->Spiller2Point - $obj->Spiller1Point)/200)));
		$obj->Spiller2MatchPoint = 32*(1-1/(1+pow(10,($obj->Spiller1Point - $obj->Spiller2Point)/200)));
	}
}
	$arr[] = $obj;

}

echo '{"items":'.json_encode($arr).'}';

mysqli_close($link);
// some code
?>