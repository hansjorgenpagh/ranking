<?php

function output($str) {

	echo $str;
	echo '<br>';
	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}
	
class Club
{
	public $name = '';
	public $id;

	
    // method declaration
    public function displayVar() {
        echo $this->var;
    }
}

function customError($errno, $errstr)
  {
  //output('<b>Error:</b> [$errno] $errstr');
  //output('Ending Script');
  die();
}

//set_error_handler("customError");
set_time_limit(0);
parse_str($_SERVER['QUERY_STRING']);

$link = mysqli_connect("localhost", "root", "maria1772", $database);

if (!$link) {
	output('not connected');
} else {
	output('Connected');
	output('Continuing');
}
//if (!$con)
//  {
//  die('Could not connect: ' . mysql_error());
//  }

output('Find alle kampe');
$result = mysqli_query($link, "SELECT * FROM Kampe");
output('Fundet alle kampe');
$kampe = array();
while($obj = mysqli_fetch_object($result)) {
	$kampe[] = $obj;
}
output('Find alle spillere');
$result = mysqli_query($link, "SELECT * FROM Spillere");
$arr = array();
while($obj = mysqli_fetch_object($result)) {
	//output($obj->Id);
	$arr[$obj->Id] = $obj;
}
if (isset($reset)) {
	if ($reset == "1") {
		foreach ($arr as $club) {
			$club->Point=0;
		}
	}
}
if (isset($count)) {
} else {
	$count = 100;
}
for ($i = 1; $i <= $count; $i++) {
	output('Run ' . $i); 
	//Nulstil spillere
	foreach ($arr as $club) {
		$club->Matches=0;
		$club->PointSum=0;
		$club->Won=0;
	}
	foreach ($kampe as $kamp) {
	if ($kamp->Valid == 'Y') {
		$HomeClub = $arr[($kamp->Spiller1)];
		$AwayClub = $arr[($kamp->Spiller2)];
		$MatchPoint = $HomeClub->Point + $AwayClub->Point;
		if ($MatchPoint <= 2.2) {
			$MatchPoint = 2.2;
		}
		//if ($kamp->Point1 > $kamp->Point2) {
			$HomeClub->PointSum = $HomeClub->PointSum + $HomeClub->Point + 32*(1-1/(1+pow(10,($AwayClub->Point - $HomeClub->Point)/200)));
			$HomeClub->Matches = $HomeClub->Matches + 1;
			$HomeClub->Won = $HomeClub->Won + 1;
			$AwayClub->Matches = $AwayClub->Matches + 1;
			$AwayClub->PointSum = $AwayClub->PointSum + $AwayClub->Point + 32*(0-1/(1+pow(10,($HomeClub->Point - $AwayClub->Point)/200)));
		//} else {
		//	$HomeClub->Matches = $HomeClub->Matches + 1;
		//	$HomeClub->PointSum = $HomeClub->PointSum + $HomeClub->Point + 32*(0-1/(1+pow(10,($AwayClub->Point - $HomeClub->Point)/200)));
		//	$AwayClub->Matches = $AwayClub->Matches + 1;
		//	$AwayClub->PointSum = $AwayClub->PointSum + $AwayClub->Point + 32*(1-1/(1+pow(10,($HomeClub->Point - $AwayClub->Point)/200)));
		//	$AwayClub->Won = $AwayClub->Won + 1;
		//}

	    //$result = mysqli_query($link, "UPDATE Spillere Set Point=300 WHERE Id=" . $club->Id);
	}
	}
	$change = 0;
	foreach ($arr as $club) {
		$PointOld = $club->Point;
		if ($club->Won == $club->Matches and $club->Matches > 0) {
		//	$club->Point = ($club->PointSum + $club->Matches) / ($club->Matches + 1);
			//$club->Matches = $club->Matches + 1;
			//Call c.addMatch(0,False)
			$club->PointSum = $club->PointSum + $club->Point + 32*(0-1/(1+pow(10,($club->Point - $club->Point)/200)));
		}
		//} elseif ($club->Matches > 0) {
			//if ($club->Licens == '8100') {
			//	//$club->Point = ($club->PointSum + $club->Matches) / ($club->Matches +1);
			//	$club->Point = ($club->PointSum) / ($club->Matches +1);
			//} else {
				//$club->Point = ($club->PointSum + $club->Matches) / $club->Matches;
		if ($club->PointSum < 0) {
			$club->Point = 0;
		} else {
			if ($club->Matches == 0) {
				$club->Point = 0;
			} elseif ($club->Won == 0) {
				$club->Point = 0;
			} elseif ($club->Won == $club->Matches) {
				$club->Point = ($club->PointSum) / ($club->Matches + 1);
			} else {
				$club->Point = ($club->PointSum) / $club->Matches;
			}
		}
			//}
		//} else {
		//	$club->Point = 0;
		//}
		if ($club->Licens == 'x9085') {
			echo $PointOld;
			echo '<br>';
			echo $club->Point;
			echo '<br>';
		}
		$change = $change + abs($club->Point - $PointOld);
		//Call c.addMatch(c.getMatches() + 1,True)
		//$club->Matches = $club->Matches + 1;
		//$club->Won = $club->Won + 1;
		//$club->PointSum = $club->PointSum + $club->Matches;
		if ($club->Won > 0 and $club->Matches > $club->Won and $club->PointSum < 0) {
			output($club->Navn . ': ' . $club->PointSum);
		}
	}
	//echo $i;
	output('Total change: ' . $change);
	output('');
}

function clubSort( $a, $b ) {
    return $a->Point == $b->Point ? 0 : ( $a->Point < $b->Point ) ? 1 : -1;
}

usort($arr, 'clubSort');

$count = 0;

foreach ($arr as $club) {
	++$count;
	$result = mysqli_query($link, "UPDATE Spillere Set Matches=" . $club->Matches . " WHERE Id=" . $club->Id);
	$result = mysqli_query($link, "UPDATE Spillere Set Won=" . $club->Won . " WHERE Id=" . $club->Id);
	$result = mysqli_query($link, "UPDATE Spillere Set Point=" . $club->Point . " WHERE Id=" . $club->Id);
	$result = mysqli_query($link, "UPDATE Spillere Set PointSum=" . $club->PointSum . " WHERE Id=" . $club->Id);
	$result = mysqli_query($link, "UPDATE Spillere Set Rank=" . $count . " WHERE Id=" . $club->Id);
}

mysqli_close($link);
echo '<br>Finished';
// some code
?>