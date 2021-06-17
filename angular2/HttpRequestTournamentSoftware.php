<?php

	include 'output.php';

	class Player
	{
		public $license = '';
		public $name;
		
		
	}
	
	Class Match {
		
		public $dateStr = '';
		public $player1Lic = '';
		public $player1Name = '';
		public $player2Lic = '';
		public $player2Name = '';
		public $resultat = '';
		
	}
	
	function output($str) {
		echo $str;
		echo '<br>';
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}
	
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	
	//echo '<br>Started4';
	// Create a stream
	parse_str($_SERVER['QUERY_STRING']);
	$opts = array(
	'http'=>array(
	'method'=>"GET",
	'header' => implode( "\n", array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Host: dtf.tournamentsoftware.com',
			'cookie: _ga=GA1.2.1664734092.1565977025; lvt=uF50ODlhh0aXRfUYsnGHVATucUd5KpocYoWVolKIOLoC2iPQch5EWZcnTV9+z0uLEZz91VOhMNk=; .ASPX_TOURNAMENT_WEBSITE=CDE700289AA143C13104CAA71DAEEB3D33B1C296E46B6ACE3C58BED7D298A9DB8B21703D245E20B271D5EDD2FE5479B3F29D0F381FD78C7D8C0CDF70599F4111F0E9DA9180B703A6C48059C4B8E7331A87825D8DD41E09348E25DEEAAF04EC34994FDB92; ASP.NET_SessionId=lbhzy2knkuknbece1hukrfuk; _gid=GA1.2.906509092.1597317565; _gat=1; st=l=1030&exp=44421.7159161227&s=0&c=1&cp=16'
		))
	)
	);
	echo('<table>');
	$tableCols = 5;
	$playerUrls = array();
	$context = stream_context_create($opts);
	$matchCount = 0;
	// Open the file using the HTTP headers set above
	//echo "The time is " . date("h:i:sa") . '<br>';
	//$file = file_get_contents('https://dtf.tournamentsoftware.com/sport/draws.aspx?id=B35DC49B-3156-4BA6-BE64-2A9B8C9735AD', false, $context);
	$file = file_get_contents('https://dtf.tournamentsoftware.com/sport/draws.aspx?id=' . $id, false, $context);
	//Find dato
	$startPos = strrpos($file, '<time datetime="');
	$startPos = strpos($file, '>', $startPos + 1);
	$endPos = strpos($file, '</time>', $startPos + 1);
	$dato = substr($file, $startPos + 1, $endPos - $startPos - 1);
	$dato = substr($dato,6,4) . substr($dato,3,2) . substr($dato,0,2);
	outputTdColspan('Turneringsdato: ' . $dato, $tableCols);
	$startPos = 0;
	$endPos = 0;
	do {
		$startPos = strpos($file, '<td class="drawname ">', $endPos);
		//echo $startPos;
		if ($startPos <> false) {
			$drawPosStart = strpos($file, '<a href="draw.aspx?', $startPos);
			$drawPosEnd = strpos($file, '" class="nowrap">', $drawPosStart);
			$drawLink = substr($file, $drawPosStart + strlen('<a href="draw.aspx?'), $drawPosEnd - $drawPosStart - strlen('<a href="draw.aspx?'));
			outputTdColspan('Link til turnering: ' . $drawLink, $tableCols);
			$drawPosEnd = strpos($file, '</a>', $drawPosStart);
			//$draw = substr($file, $drawPosStart + strlen('<a href="draw.aspx?'), $drawPosEnd - $drawPosStart - strlen('<a href="draw.aspx?'));
			$draw = substr($file, $drawPosStart, $drawPosEnd - $drawPosStart + strlen('</a>'));
			$draw = strip_tags($draw);
			outputTdColspan('Navn på turnering: ' . $draw, $tableCols);
			
			$validTurnering = true;
			if (strPos($draw, 'double') !== false) {
				$validTurnering = false;
			}
			if (strPos($draw, 'HD') !== false) {
				$validTurnering = false;
			}
			if (strPos($draw, 'DD') !== false) {
				$validTurnering = false;
			}
			if (strPos($draw, 'Tennis10') !== false) {
				$validTurnering = false;
			}
				//exit;
				//sleep(20);
				if ($validTurnering) {
	$opts = array(
	'http'=>array(
	'method'=>"GET",
	'header' => implode( "\n", array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Host: dtf.tournamentsoftware.com',
			'cookie: _ga=GA1.2.1664734092.1565977025; lvt=uF50ODlhh0aXRfUYsnGHVATucUd5KpocYoWVolKIOLoC2iPQch5EWZcnTV9+z0uLEZz91VOhMNk=; .ASPX_TOURNAMENT_WEBSITE=CDE700289AA143C13104CAA71DAEEB3D33B1C296E46B6ACE3C58BED7D298A9DB8B21703D245E20B271D5EDD2FE5479B3F29D0F381FD78C7D8C0CDF70599F4111F0E9DA9180B703A6C48059C4B8E7331A87825D8DD41E09348E25DEEAAF04EC34994FDB92; ASP.NET_SessionId=lbhzy2knkuknbece1hukrfuk; _gid=GA1.2.906509092.1597317565; _gat=1; st=l=1030&exp=44421.7159161227&s=0&c=1&cp=16'
		))
	)
	);
	$context = stream_context_create($opts);
				$matchesHtml = file_get_contents('https://dtf.tournamentsoftware.com/sport/drawmatches.aspx?' . $drawLink, false, $context);
				outputTdColspan('Link til lodtrækning: ' . 'https://dtf.tournamentsoftware.com/sport/drawmatches.aspx?' . $drawLink, $tableCols);
				//exit;
				$matchesStartPos = strpos($matchesHtml, '<table class="ruler matches">', $matchesHtmlEndPos);
				$matchesStartPos = strpos($matchesHtml, '<tbody>', $matchesStartPos);
				$matchesEndPos = strpos($matchesHtml, '</tbody>', $matchesStartPos);
				$matchesHtmlEndPos = 0;
				$mactchStartPos = @matchesStartPos;
				if (true) {
					do {
						$matchStartPos = strpos($matchesHtml, '<tr>', $matchStartPos);
						//echo $matchStartPos . '<br>';
						if ($matchStartPos <> false) {
							//echo 'checking match<br>';
							$trCount = 0;
							$count = 0;
							$trStart = $matchStartPos - 1;
							$trEnd = $matchStartPos - 1;
							do {
								$trStart = strpos($matchesHtml, '<tr>', $trStart);
								$trEnd = strpos($matchesHtml, '</tr>', $trEnd);
								//if ($count == 0) {
								//	echo '$count=0:' . $trStart . '-' . $trEnd . '-' . $matchStartPos . '<br>';
								//}
								if ($trStart == false) {
									$trStart = $trEnd + 1;
								}
								if ($trStart < $trEnd) {
									$trCount = $trCount + 1;
									$trStart = $trStart + 1;
									$trEnd = $trEnd - 1;
									} else {
									$trCount = $trCount - 1;
									$trEnd = $trEnd + 1;
									$trStart = $trStart - 1;
								}
								$count = $count + 1;
							} while ($trCount <> 0 AND $count <= 20);
							//echo '$count:' . $count . '<br>';
							//exit;
							$matchEndPos = $trEnd;
							$matchPlayer1PosStart = strpos($matchesHtml, '<a href="player.aspx?', $matchStartPos);
							$matchPlayer2PosStart = strpos($matchesHtml, '<a href="player.aspx?', $matchPlayer1PosStart + 1);
							$matchResultatStart = strpos($matchesHtml, '<span class="score">', $matchStartPos);
							if ($matchResultatStart == false) {
								$matchResultatStart = $matchEndPos;
							}
							//echo '$matchEndPos: ' . $matchEndPos . '<br>';
							//echo 'String:' . substr($matchesHtml, $matchEndPos, 100) . '<br>';
							//echo '$matchPlayer1PosStart: ' . $matchPlayer1PosStart . '<br>';
							//echo '$matchPlayer2PosStart: ' . $matchPlayer2PosStart . '<br>';
							//echo '$matchResultatStart: ' . $matchResultatStart . '<br>';
							if ($matchPlayer1PosStart < $matchEndPos AND $matchPlayer2PosStart < $matchEndPos AND $matchResultatStart < $matchEndPos) {
								$matchPlayer1PosEnd = strpos($matchesHtml, '">', $matchPlayer1PosStart);
								$matchPlayer1Link = substr($matchesHtml, $matchPlayer1PosStart + strlen('<a href="player.aspx?'), $matchPlayer1PosEnd - $matchPlayer1PosStart - strlen('<a href="player.aspx?'));
								//echo $matchPlayer1Link . '<br>';
								$matchPlayer2PosEnd = strpos($matchesHtml, '">', $matchPlayer2PosStart);
								$matchPlayer2Link = substr($matchesHtml, $matchPlayer2PosStart + strlen('<a href="player.aspx?'), $matchPlayer2PosEnd - $matchPlayer2PosStart - strlen('<a href="player.aspx?'));
								//echo $matchPlayer2Link . '<br>';
								$matchResultatEnd = strpos($matchesHtml, '</span></td>', $matchResultatStart);
								$resultat = substr($matchesHtml, $matchResultatStart + strlen('<span class="score">'), $matchResultatEnd - $matchResultatStart - strlen('<span class="score">'));
								$resultat = strip_tags($resultat);
								//echo $resultat . '<br>';
								//exit;
								If (stripos($resultat, 'rtd') === false) {
									If (stripos($resultat, 'w.o.') === false) {
									If (stripos($resultat, 'Ikke spillet') === false) {
										//sleep(20);
										//output('Resultat: ' . $resultat); 
										if (is_null($playerUrls[$matchPlayer1Link])) {
											//sleep(10); //didn't solve the problem
											//output('https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer1Link);
											//outputTdColspan('Link til spiller 1: https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer1Link, $tableCols);
											//$playerHtml = file_get_contents('https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer1Link, false, $context);
	$opts = array(
	'http'=>array(
	'method'=>"GET",
	'header' => implode( "\n", array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Host: dtf.tournamentsoftware.com',
			'cookie: _ga=GA1.2.1664734092.1565977025; lvt=uF50ODlhh0aXRfUYsnGHVATucUd5KpocYoWVolKIOLoC2iPQch5EWZcnTV9+z0uLEZz91VOhMNk=; .ASPX_TOURNAMENT_WEBSITE=CDE700289AA143C13104CAA71DAEEB3D33B1C296E46B6ACE3C58BED7D298A9DB8B21703D245E20B271D5EDD2FE5479B3F29D0F381FD78C7D8C0CDF70599F4111F0E9DA9180B703A6C48059C4B8E7331A87825D8DD41E09348E25DEEAAF04EC34994FDB92; ASP.NET_SessionId=lbhzy2knkuknbece1hukrfuk; _gid=GA1.2.906509092.1597317565; _gat=1; st=l=1030&exp=44421.7159161227&s=0&c=1&cp=16'
		))
	)
	);
	$context = stream_context_create($opts);
											$playerHtml = file_get_contents('https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer1Link, false, $context);
											
											$playerHtmlStart = strpos($playerHtml, '<div class="subtitle">', 0);
											$playerHtmlStart = strpos($playerHtml, '<a href=', $playerHtmlStart);
											$playerHtmlEnd = strpos($playerHtml, '</a>', $playerHtmlStart);
											$d = new DOMDocument();
											//output(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$d->loadHTML(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$prgs = $d->getElementsByTagName('a');
											//outputTdColspan('Link til spiller 1 profil: https://dtf.tournamentsoftware.com' . $prgs[0]->getAttribute('href'), $tableCols);
											$playerHtml = file_get_contents('https://dtf.tournamentsoftware.com' . $prgs[0]->getAttribute('href'),false, $context);
											$playerHtmlStart = strpos($playerHtml, '<span class="media__title-aside">', 0);
											$playerHtmlEnd = strpos($playerHtml, '</span>', $playerHtmlStart);
											$d = new DOMDocument();
											//output(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$d->loadHTML(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$prgs = $d->getElementsByTagName('span');
											$player1Licens = strrev(substr(strrev(substr($prgs[0]->nodeValue, 1 )),1));
											
											//if ($playerHtmlStart == FALSE) {
											//	$player1Licens = "0";
											//} else{
											//	$playerHtmlEnd = strpos($playerHtml, '" class="button">', $playerHtmlStart);
											//	$player1Licens = substr($playerHtml, $playerHtmlStart + strlen('<a href="/player/'), $playerHtmlEnd - $playerHtmlStart - strlen('<a href="/player/'));
											//	$player1Licens = substr($player1Licens, strpos($player1Licens, '/' , 0) + 1);
											
											//	$playerUrls[$matchPlayer1Link] = $player1Licens;
											//}
										} else {
											$player1Licens = $playerUrls[$matchPlayer1Link];
										}
										if (is_null($playerUrls[$matchPlayer2Link])) {
											//sleep(10); //didn't solve the problem
											//output('https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer2Link);
											$playerHtml = file_get_contents('https://dtf.tournamentsoftware.com/sport/player.aspx?' . $matchPlayer2Link, false, $context);
											$playerHtmlStart = strpos($playerHtml, '<div class="subtitle">', 0);
											$playerHtmlStart = strpos($playerHtml, '<a href=', $playerHtmlStart);
											$playerHtmlEnd = strpos($playerHtml, '</a>', $playerHtmlStart);
											$d = new DOMDocument();
											//output(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$d->loadHTML(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$prgs = $d->getElementsByTagName('a');
											$playerHtml = file_get_contents('https://dtf.tournamentsoftware.com' . $prgs[0]->getAttribute('href'), false, $context);
											$playerHtmlStart = strpos($playerHtml, '<span class="media__title-aside">', 0);
											$playerHtmlEnd = strpos($playerHtml, '</span>', $playerHtmlStart);
											$d = new DOMDocument();
											//output(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$d->loadHTML(substr($playerHtml,$playerHtmlStart,$playerHtmlEnd + 4));
											$prgs = $d->getElementsByTagName('span');
											$player2Licens = strrev(substr(strrev(substr($prgs[0]->nodeValue, 1 )),1));
											//if ($playerHtmlStart == FALSE) {
											//	$player2Licens = "0";
											//} else{
											//	$playerHtmlEnd = strpos($playerHtml, '" class="button">', $playerHtmlStart);
											//	$player2Licens = substr($playerHtml, $playerHtmlStart + strlen('<a href="/player/'), $playerHtmlEnd - $playerHtmlStart - strlen('<a href="/player/'));
											//	$player2Licens = substr($player2Licens, strpos($player2Licens, '/' , 0) + 1);
											//	$playerUrls[$matchPlayer2Link] = $player2Licens;
											//}
										} else {
											$player2Licens = $playerUrls[$matchPlayer2Link];
										}
										if (true) {
											//sleep(20);
											//echo $player2Licens . '<br>';
											echo('<tr>');
											outputTd($player1Licens);
											outputTd('-');
											outputTd($player2Licens);
											outputTd($resultat);
											
											$match = new Match;
											$data = array('database' => 'U12', 'Dato' => $dato, 'player1' => $player1Licens, 'player2' => $player2Licens, 'point1' => '2', 'point2' => '0', 'resultat' => $resultat, 'comment' => 'https://dtf.tournamentsoftware.com/sport/drawmatches.aspx?' . $drawLink);
											$url= 'http://192.168.0.2/u8/angular2/processNewMatch.php';
											//echo http_build_query($data);
											$opts = array(
											'http'=>array(
											'method'=>"POST",
											'header'=>"Accept-language: en\r\n" .
											"Content-Type: application/x-www-form-urlencoded\r\n" .
											"Cookie: __utma=256282518.1557752900.1487681509.1495025600.1506591136.2; _ga=GA1.2.1557752900.1487681509; ASP.NET_SessionId=hwjbt2jsuo0s2ixbr4kv5yth",
											'content' => http_build_query($data)
											//'content' => 'database=U12&Dato=' . '20181231' . '&player1=' . $player1Licens . '&player2=' . $player2Licens . '&point1=2&point2=0&resultat=' . $resultat
											)
											);
											//echo 'database=U12&Dato=' . '20181231' . '&player1=' . $player1Licens . '&player2=' . $player2Licens . '&point1=2&point2=0&resultat=' . $resultat . '<br>';
											$context = stream_context_create($opts);
											//output('context:' . $context);
											// Open the file using the HTTP headers set above
											if (true) {
												$file2 = file_get_contents($url, false, $context);
												//echo 'Query:' . http_build_query($data) . '<br>';
												//output($file2);
												//$matchStr = $winner->name . '(' . $winner->license . ')-' . $looser->name . '(' . $looser->license . ') ' . $resultat;
												$match->dateStr = '2018-10-17';
												$match->player1Lic = $player1Licens;
												//$match->player1Name = $winner->name;
												$match->player2Lic = $player2Licens;
												//$match->player2Name = $looser->name;
												$match->resultat = $resultat;
												$result = json_decode($file2);
												if ($result->{'result'} === true) {
													//Success
													outputTd('Success');
													array_push($success, $match);
												} else {	
													//Error
													outputTd('Error: ' . $result->{'errors3'});
													array_push($failed,$match);
												}
											}
											echo('</tr>');
										}
										$matchCount = $matchCount + 1;
											if ($matchCount > 3) {
										//exit;
										}
									}
									}
								}
							}
							$matchStartPos = $trEnd;
							
						}
					} while ($matchStartPos <> false);	
				}
			}
			$endPos = $startPos + 1;
		}
	} while ($startPos <> false);
	echo '</table>';
	exit;
	$players = array();
	$playersVect = array();
	//Collect all players
	do {
		//$startPos = strpos($file, 'abc');
		$startPos = strpos($file, '<div class="cupplayername">', $endPos);
		if ($startPos <> false) {
			//echo '<br>';
			//echo $startPos;
			$endPos = strpos($file, '</div>', $startPos);
			//echo '<br>';
			//echo $endPos;
			//echo '<br>';
			$player = new Player;
			$player->name = substr($file, $startPos + strlen('<div class="cupplayername">'), $endPos - $startPos - strlen('<div class="cupplayername">'));
			$startPos = strpos($file, '<div class="cupplayerlicense">',$endPos);
			$endPos = strpos($file, '</div>', $startPos);
			$player->license = substr($file, $startPos + strlen('<div class="cupplayerlicense">'), $endPos - $startPos - strlen('<div class="cupplayerlicense">'));
			$players[$player->license] = $player;
			array_push($playersVect,$player);
			//echo $player->name;
			//echo '-';
			//echo $player->license;
		}
		//$startPos = false;
	} while ($startPos <> false);
	
	$success = array();
	$failed = array();
	do {
		
		$startPos = strpos($file, '<div class="bottom">', $endPos);
		if ($startPos <> false) {
			$endPos = strpos($file, '</div>', $startPos);
			$lic = substr($file, $startPos + strlen('<div class="bottom">'), $endPos - $startPos - strlen('<div class="bottom">'));
			$spacePos = strpos($lic, ' ');
			$lic = substr($lic, 0, $spacePos);
			$startPos = strpos($file, '<div class="cupresult">', $startPos);
			$startPos = strpos($file, '<div>', $startPos+1);
			$endPos = strpos($file, '</div>', $startPos);
			$resultat = substr($file, $startPos + strlen('<div>'), $endPos - $startPos - strlen('<div>'));
			//echo '<br>';
			//echo $lic;
			$winner = $players[$lic];
			$looser = $playersVect[0];
			if ($looser->license == $lic) {
				$looser = $playersVect[1];
			}
			If ($winner->name <> 'Bye') {
				If ($looser->name <> 'Bye') {
					If (stripos($resultat, 'rtd') == false) {
						If (stripos($resultat, 'wo') == false) {
							$match = new Match;
							//echo '<br>';
							//echo $winner->name;
							//echo '-';
							//echo $looser->name;
							//echo ' ';
							//echo $resultat;
							$data = array('database' => 'U12', 'Dato' => '2018-12-31', 'player1' => $winner->license, 'player2' => $looser->license, 'point1' => '2', 'point2' => '0', 'resultat' => $resultat);
							$url= 'http://paghfam.dk/u8/angular2/processNewMatch.php';
							//echo http_build_query($data);
							$opts = array(
							'http'=>array(
							'method'=>"POST",
							'header'=>"Accept-language: en\r\n" .
							"Content-Type: application/x-www-form-urlencoded\r\n" .
							"Cookie: __utma=256282518.1557752900.1487681509.1495025600.1506591136.2; _ga=GA1.2.1557752900.1487681509; ASP.NET_SessionId=hwjbt2jsuo0s2ixbr4kv5yth",
							'content' => http_build_query($data)
							//'content' => 'database=U12&Dato=' . '20181231' . '&player1=' . $winner->license . '&player2=' . looser->license . '&point1=2&point2=0&resultat=' . $resultat
							)
							);
							$context = stream_context_create($opts);
							// Open the file using the HTTP headers set above
							$file2 = file_get_contents($url, false, $context);
							$matchStr = $winner->name . '(' . $winner->license . ')-' . $looser->name . '(' . $looser->license . ') ' . $resultat;
							$match->dateStr = '2018-12-31';
							$match->player1Lic = $winner->license;
							$match->player1Name = $winner->name;
							$match->player2Lic = $looser->license;
							$match->player2Name = $looser->name;
							$match->resultat = $resultat;
							$result = json_decode($file2);
							if ($result->{'result'} == 'true') {
								//Error
								array_push($failed,$match);
								} else {	
								//Success
								array_push($success, $match);
							}
							
							//objHttp.open "POST", url, False 
							//objHttp.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
							//req = |database=U12&Dato=| + dato + |&player1=| + winner.license + |&player2=| + looser.license + |&point1=2&point2=0&resultat=| + resultat
							//objHttp.send req
							//If objHttp.responseText = "running" Then
							//	Msgbox "FAILED: " + winner.playerName + "(" + winner.license + ") - " + looser.playerName + "(" + looser.license + ") : " + resultat
							//End If
							//$startPos = false;
						}
					}
				}
			}
			array_splice($playersVect,0,2);
			array_push($playersVect,$winner);
			//$startPos = false;
		}
		
	} while ($startPos <> false);
	//echo 'Failed:<BR>';
	//echo implode('<br>',$failed);
	//echo '<br>
	//echo '<br>Success:<BR>';
	//echo implode('<br>',$success);
	//echo '<br>Finished';
	echo json_encode($success);
	echo('</table>');
?>
