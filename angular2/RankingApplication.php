<?php

	echo 'loading class';
	
	Class RankingApplication {
		
		private $localDomain = 'http://192.168.0.12';
		
		function getLocalDomain() {
			echo 'returning local domain';
			return $this->localDomain;
		}
		
	}
?>
