<?php
	$errors = array();  // array to hold validation errors
	$data = array();        // array to pass back data
	
	// validate the variables ========
	if (empty($_POST['licens'])) {
		$errors['licens'] = 'Licens is required.';
		array_push($errors2, 'Licens is required.');
	}
	if (empty($_POST['navn']))
	$errors['name1'] = 'Navn is required.';
	array_push($errors2, 'Navn is required.');
	if (empty($_POST['aargang']))
	$errors['name2'] = 'Årgang is required.';
	array_push($errors2, 'Årgang is required.');
	if (empty($_POST['gender']))
	$errors['name2'] = 'Gender is required.';
	array_push($errors2, 'Gender is required.');
	if (empty($_POST['klub']))
	$errors['name2'] = 'Klub is required.';
	array_push($errors2, 'Klub is required.');
	
	
	// return a response ==============
	
	// response if there are errors
	if ( ! empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
		$data['hjp'] = false;
	} else {
		//$data['hjp'] = $_POST;
		$link = mysqli_connect("localhost", "root", "maria1772", $_POST["database"]);
		//if (!$link) {
		if ($result1 = mysqli_query($link, "SELECT * FROM Spillere WHERE Licens = '". $_POST["licens"] . "'")) {
			if (mysqli_num_rows($result1) == 1) {
				$row1 = mysqli_fetch_assoc($result1);
				if ($row1["Klub"] == $_POST["klub"]) {
					$data['response'] = 'Player updated';
				} else {
					$data['response'] = 'Klub updated from ' . $row1["Klub"] . ' to ' . $_POST["klub"];
				}
				$sql = "UPDATE Spillere SET Navn='" . $_POST["navn"] . "', Aargang=" . $_POST["aargang"] . ", Klub='" . $_POST["klub"] . "', Gender='" . $_POST["gender"] . "', tsid='" . $_POST["tsid"]. "'";
				if ($_POST["pointsum"] != "") {
					$sql = $sql .  ", PointSum='" . $_POST["pointsum"] . "'";
				}
				if ($_POST["point"] != "") {
					$sql = $sql .  ", Point='" . $_POST["point"] . "'";
				}
				if ($_POST["matches"] != "") {
					$sql = $sql .  ", Matches='" . $_POST["matches"] . "'";
				}
				if ($_POST["won"] != "") {
					$sql = $sql .  ", Won='" . $_POST["won"] . "'";
				}
				if ($_POST["rank"] != "") {
					$sql = $sql .  ", Rank='" . $_POST["rank"] . "'";
				}
				$sql = $sql . " WHERE Licens='" . $_POST["licens"] . "'";
				//$data['Request'] = "UPDATE Spillere SET Navn='" . $_POST["navn"] . "', Aargang=" . $_POST["aargang"] . ", Klub='" . $_POST["klub"] . "', Gender='" . $_POST["gender"] . "', tsid='" . $_POST["tsid"] . "' WHERE Licens='" . $_POST["licens"] . "'";
				$data['Request'] = $sql;
				$result = mysqli_query($link, $sql);
				$data['result'] = $result;
				if (!$result) {
					$data['response'] = mysqli_error($link);
				}
				$data['connected'] = 'Yes';
				//$data['Request'] = "INSERT INTO Spillere (Licens, Navn, Aargang, Klub, Gender) VALUES('" . $licens . "', '" . $navn . "', " . $aargang . ", '" . $klub . "','" . $genders[$y] . "') ON DUPLICATE KEY UPDATE Navn='" . $navn . "', Aargang=" . $aargang . ", Klub='" . $klub . "', Gender='" . $genders[$y] . "'" ;
						
				} else {
				$data['response'] = 'Player created';
				$result = mysqli_query($link, "INSERT INTO Spillere (Licens, Navn, Aargang, Klub, Gender, tsid) VALUES('" . $_POST["licens"] . "', '" . $_POST["navn"] . "', " . $_POST["aargang"] . ", '" . $_POST["klub"] . "','" . $_POST["gender"] . "','" . $_POST["tsid"] . "')");
				$data['result'] = $result;
				if (!$result) {
					$data['response'] = mysqli_error($link);
				}
			}
			} else {
			$data['response'] = 'Player created';
		}
		//}
		//$data['Request'] = "INSERT INTO Spillere (Licens, Navn, Aargang, Klub, Gender) VALUES('" . $_POST["licens"] . "', '" . $_POST["navn"] . "', " . $_POST["aargang"] . ", '" . $_POST["klub"] . "','" . $_POST["gender"] . "') ON DUPLICATE KEY UPDATE Navn='" . $_POST["navn"] . "', Aargang=" . $_POST["aargang"] . ", Klub='" . $_POST["klub"] . "', Gender='" . $_POST["gender"] . "'";
		//$result = mysqli_query($link, "INSERT INTO Spillere (Licens, Navn, Aargang, Klub, Gender) VALUES('" . $_POST["licens"] . "', '" . $_POST["navn"] . "', " . $_POST["aargang"] . ", '" . $_POST["klub"] . "','" . $_POST["gender"] . "') ON DUPLICATE KEY UPDATE Navn='" . $_POST["navn"] . "', Aargang=" . $_POST["aargang"] . ", Klub='" . $_POST["klub"] . "', Gender='" . $_POST["gender"] . "'");
		mysqli_close($link);
		//$data['result'] = $result;
		// if there are no errors, return a message
		//$data['dato'] = $date->format('Ymd');
	}
	if ( ! empty($errors)) {
		$data['success'] = false;
		$data['message'] = 'Failed!';
		$data['errors'] = $errors;
		$data['errors_count'] = count($errors);
		$data['errors3'] = implode(" ",$errors);
		$data['errors2'] = implode(" ",$errors2);
		$data['errors2_count'] = count($errors2);
	} else {
		//$data['result'] = true;
		$data['success'] = true;
		$data['message'] = 'Success!';
	}
	// return all our data to an AJAX call
	echo json_encode($data);
	// some code
?>		
