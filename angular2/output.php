<?php
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

	function outputTdColspan($str, $colspan) {
		echo('<tr>');
		echo('<td colspan="' . $colspan . '">');
		echo $str;
		echo('</td>');
		echo('</tr>');		
		//echo '<br>';
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}
?>
