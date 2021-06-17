<?php

	include ('match.php');
	
	Class HoldkampPage {
		
		private $url;
		private $file;
		private $liTags;
		private $liStartTag;
		private $nodes = array();
		private $liTagCount;
		
		function __construct($url) {
        		$this->url = $url;
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
        		$this->file = file_get_contents($url, false, $context);
			$dom = new DOMDocument;
			$dom->loadHTML($this->file);
        		$liTags = $dom->getElementsByTagName('li');
			foreach($liTags as $node) {
				$this->nodes[] = $node;
			}
   			$this->liTagCount = -1;
		}
		
		
		function getNextMatch() {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
			do {
				$this->liTagCount = $this->liTagCount +1;
				if ($this->liTagCount >= count($this->nodes)) {
					return false;
				}
				$this->liStartTag = $this->nodes[$this->liTagCount];
				if ($this->liStartTag->getAttribute("class") == "match-group__item") {
					echo 'Match found<br>';
					$match;
					$match = new Match();
					$divTags = $this->liStartTag->getElementsByTagName('div');
					foreach ($divTags as $divTag) {
						$winner;
						$looser;
						if ($divTag->getAttribute('class') == "match__row has-won") {
							$winner = true;
							$looser = false;
						} elseif ($divTag->getAttribute('class') == "match__row ") {
							$winner = false;
							$looser = true;
						} else {
							$winner = false;
							$looser = false;
						}
						if ($winner or $looser) {
							$spanWarningTags = $divTag->getElementsByTagName('span');
							foreach ($spanWarningTags as $spanWarningTag) {
								if ($spanWarningTag->getAttribute("class") == 'tag--warning tag match__message') {
									$match = NULL;
									break 2;
								}
							}
							$tags = $divTag->getElementsByTagName('a');
							$playerTags = array();
							foreach ($tags as $playerTag) {
								if (substr($playerTag->getAttribute('href'),0,8) == '/league/') {
									$playerTags[] = $playerTag;
								}
							}							
							if (count($playerTags) == 1) {
								$liResultTags = $divTag->getElementsByTagName('li');
								$liResultTagsArray = array();
								foreach ($liResultTags as $liResultTag) {
									$liResultTagsArray[] = $liResultTag;
								}
								if ($winner) {
									$liResultTagsWinnerArray = $liResultTagsArray;
									$spanTagPlayer1 = $playerTags[0]->firstChild;
									$match->player1Name = $spanTagPlayer1->nodeValue;
								} else {
									$liResultTagsLooserArray = $liResultTagsArray;
									$spanTagPlayer2 = $playerTags[0]->firstChild;
									$match->player2Name = $spanTagPlayer2->nodeValue;
								}
							} else {
								$match = NULL;
								break 1;
								return false;
							}
						}
					}
					if ($match) {
						$result = "";
						for ($x = 0; $x < count($liResultTagsWinnerArray); $x++) {
							if ($x > 0) {
								$result = $result . ' ';
							}
							$result = $result . $liResultTagsWinnerArray[$x]->nodeValue . '-' . $liResultTagsLooserArray[$x]->nodeValue;
						}
						$match->resultat = $result;
						return $match;
					}
				}
			} while (true);
		}		
		
	}
?>
