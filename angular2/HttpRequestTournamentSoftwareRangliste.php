<?php
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
		//echo '<br>';
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}
	function outputTd($str) {
		echo('<td>');
		echo $str;
		echo('</td>');		
		//echo '<br>';
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}
	ob_implicit_flush(true);
	$link = mysqli_connect("localhost", "root", "maria1772", "U12");
	$rankings = array("2216", "2217", "2532", "2539");
	$genders = array("M","F","M","F");
	//Open league/
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
	//output($id);
	output('<table>');
	for($y = 0; $y < count($rankings); $y++) {
		for ($x = $min; $x <= $max; $x++) {
			//output($x);
			$context = stream_context_create($opts);
			//output('https://dtf.tournamentsoftware.com/ranking/category.aspx?id=' . $id . '&category=' . $rankings[$y] . '&p=' . $x . '&ps=100');
			$fileDraw = file_get_contents('https://dtf.tournamentsoftware.com/ranking/category.aspx?id=' . $id . '&category=' . $rankings[$y] . '&p=' . $x . '&ps=100', false, $context);
			//output($fileDraw);
			//output(strlen($fileDraw));
			$rankLineStartPos = strpos($fileDraw, '<td class="rank">',0);
			$rankColumnStartPos = 0;
			while ($rankLineStartPos != false) {
				//output('rank found');
				output('<tr>');
				for ($z = 1; $z <= 16; $z++) {
					$rankColumnStartPos = strpos($fileDraw, '<td',$rankColumnStartPos + 1);
					switch ($z) {
						case 3: //Navn
							$rankColumnStartPos = strpos($fileDraw, '<a',$rankColumnStartPos + 1);
							$rankColumnStartPos = strpos($fileDraw, '>',$rankColumnStartPos + 1);
							$tdEnd = strpos($fileDraw, '</a',$rankColumnStartPos);
							$navn = substr($fileDraw, $rankColumnStartPos + 1, $tdEnd - $rankColumnStartPos - 1);
							outputTd($navn);
							break;
						case 4: //Profile
							$rankColumnStartPos = strpos($fileDraw, '<a',$rankColumnStartPos + 1);
							//$rankColumnStartPos = strpos($fileDraw, '>',$rankColumnStartPos + 1);
							$tdEnd = strpos($fileDraw, '</a',$rankColumnStartPos);
							$xml = substr($fileDraw, $rankColumnStartPos , $tdEnd - $rankColumnStartPos + 4);
							//outputTd($xml);
							$dom = new DOMDocument;
							$dom->loadHTML($xml);

							$tags = $dom->getElementsByTagName('a');
							foreach ($tags as $teamMatchTag) {
								outputTd('found one');
								outputTd('https://dtf.tournamentsoftware.com/' . $teamMatchTag->getAttribute('href'));
								$tsid = substr(strrchr($teamMatchTag->getAttribute('href'), "/"),1);
								outputTd($tsid);
								$fileDrawPlayer = file_get_contents('https://dtf.tournamentsoftware.com/' . $teamMatchTag->getAttribute('href'), false, $context);
							}
							$startPos = strpos($fileDrawPlayer, '<a href="/association',0);
							$endPos = strpos($fileDrawPlayer, '</a>',$startPos);
							$xml = substr($fileDrawPlayer, $startPos , $endPos - $startPos + 4);
							//outputTd($xml);
							$dom = new DOMDocument;
							$dom->loadHTML($xml);
							$tags = $dom->getElementsByTagName('span');
							foreach ($tags as $teamMatchTag) {
								$klub = $teamMatchTag->nodeValue;
							}
							outputTd($klub);

							//$fileDraw = file_get_contents('https://dtf.tournamentsoftware.com/ranking/category.aspx?id=' . $id . '&category=' . $rankings[$y] . '&p=' . $x . '&ps=100', false, $context);
							break;
						case 5: //Licens
							$tdEnd = strpos($fileDraw, '</td',$rankColumnStartPos);
							$licens = substr($fileDraw, $rankColumnStartPos + 4, $tdEnd - $rankColumnStartPos - 4);
							outputTd($licens);
							break;
						case 6: //Årgang
							$tdEnd = strpos($fileDraw, '</td',$rankColumnStartPos);
							$aargang = substr($fileDraw, $rankColumnStartPos + 18, $tdEnd - $rankColumnStartPos - 24);
							outputTd($aargang);
							break;
						case 8: //Klub
							$rankColumnStartPos = strpos($fileDraw, '<a',$rankColumnStartPos + 1);
							$rankColumnStartPos = strpos($fileDraw, '>',$rankColumnStartPos + 1);
							$tdEnd = strpos($fileDraw, '</a',$rankColumnStartPos);
							//$klub = substr($fileDraw, $rankColumnStartPos + 1, $tdEnd - $rankColumnStartPos - 1);
							//outputTd($klub);
					}
				}
				
			//output($navn);
															$url= 'http://192.168.0.2/u8/angular2/processNewPlayer.php';
															$data = array('database' => 'U12', 'navn' => $navn, 'licens' => $licens, 'klub' => $klub, 'aargang' => $aargang, 'gender' => $genders[$y], 'tsid' => $tsid);
															$opts2 = array(
															'http'=>array(
															'method'=>"POST",
															'header'=>"Accept-language: en\r\n" .
															"Content-Type: application/x-www-form-urlencoded\r\n" .
															"Cookie: __utma=256282518.1557752900.1487681509.1495025600.1506591136.2; _ga=GA1.2.1557752900.1487681509; ASP.NET_SessionId=hwjbt2jsuo0s2ixbr4kv5yth",
															'content' => http_build_query($data)
															)
															);
															$context2 = stream_context_create($opts2);
															// Open the file using the HTTP headers set above
															$file2 = file_get_contents($url, false, $context2);
															$result = json_decode($file2);
															if ($result->{'result'} === true) {
																//Success
																//output ('Success: ' . $dato3->format('Ymd') . ': ' . $player1Licens . ' - ' . $player2Licens . ' ' . $resultat);
																outputTd('Success');
																outputTd($result->{'response'});
																//outputTd($result->{'Request'});
																//outputTd($file2);
															} else {	
																outputTd('Error');
																outputTd($result->{'response'});
																//outputTd($result->{'sqlError'});
																//outputTd($file2);
																//Error
																//output('Error: ' . $player1Licens . ' - ' . $player2Licens . ' ' . $resultat . ': ' . implode(', ',$result->{'errors'}) . '<br>');
																//output('Error: ' . $dato3->format('Ymd') . ': ' . $player1Licens . ' - ' . $player2Licens . ' ' . $resultat . ': ' . $result->{'errors3'});
															}			
				output('</tr>');
			//$result = mysqli_query($link, "INSERT INTO Spillere (Licens, Navn, Aargang, Klub, Gender) VALUES('" . $licens . "', '" . $navn . "', " . $aargang . ", '" . $klub . "','" . $genders[$y] . "') ON DUPLICATE KEY UPDATE Navn='" . $navn . "', Aargang=" . $aargang . ", Klub='" . $klub . "', Gender='" . $genders[$y] . "'");
			//exit;
			$rankLineStartPos = strpos($fileDraw, '<td class="rank">',$rankLineStartPos+1);
		}
	}
	}
	output('</table>');
	//output('Finished');
?>											
