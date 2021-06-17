<?php

include 'HoldkampPage.php';

$hjp = new HoldkampPage('https://dtf.tournamentsoftware.com/league/F0AAB424-C7BB-4EA4-AB08-05132BDCD9E1/team-match/2817');
$match = $hjp->getNextMatch();
echo $match->player1Name;
echo '<br>';
echo $match->player2Name;
echo '<br>';
echo $match->resultat;
echo '<br>';
$match = $hjp->getNextMatch();
echo $match->player1Name;
echo '<br>';
echo $match->player2Name;
echo '<br>';
echo $match->resultat;
echo '<br>';
$match = $hjp->getNextMatch();
echo $match->player1Name;
echo '<br>';
echo $match->player2Name;
echo '<br>';
echo $match->resultat;
echo '<br>';
$match = $hjp->getNextMatch();
if ($match) {
	echo $match->player1Name;
	echo '<br>';
	echo $match->player2Name;
	echo '<br>';
	echo $match->resultat;
	echo '<br>';
}
$match = $hjp->getNextMatch();
if ($match) {
	echo $match->player1Name;
	echo '<br>';
	echo $match->player2Name;
echo '<br>';
echo $match->resultat;
	echo '<br>';
}
$match = $hjp->getNextMatch();
if ($match) {
	echo $match->player1Name;
	echo '<br>';
echo $match->player2Name;
	echo '<br>';
	echo $match->resultat;
echo '<br>';
}
$match = $hjp->getNextMatch();
if ($match) {
	echo $match->player1Name;
	echo '<br>';
echo $match->player2Name;
echo '<br>';
	echo $match->resultat;
	echo '<br>';
}
?>
