<?php

	include ('Player.php');
	
	Class PlayerPage {
		
		private $url;
		private $file;
		private $nodes = array();
		
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
  		}
  		
  		function getPlayer() {
  		}
	}
?>
