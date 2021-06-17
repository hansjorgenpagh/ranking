<?php
$errors = array();  // array to hold validation errors
$data = array();        // array to pass back data

// validate the variables ========
if (empty($_POST['Dato']))
  $errors['date'] = 'Date is required.';
if (empty($_POST['player1']))
  $errors['name1'] = 'Player 1 is required.';
if (empty($_POST['player2']))
  $errors['name2'] = 'Player 2 is required.';
if (empty($_POST['point1']) && !is_numeric($_POST['point1']))
  $errors['point1'] = 'Point 1 is required.';
if (empty($_POST['point2']) && !is_numeric($_POST['point2']))
  $errors['point2'] = 'Point 2 is required.';


// return a response ==============

// response if there are errors
if ( ! empty($errors)) {

  // if there are items in our errors array, return those errors
  $data['success'] = false;
  $data['errors']  = $errors;
  //$data['hjp'] = $_POST;
} else {

	$link = mysqli_connect("localhost", "root", "maria1772", $_POST["database"]);
	//if (!$link) {
		$data['connected'] = 'Yes';
		$date = new DateTime($_POST["Dato"]);
		$result = mysqli_query($link, "INSERT INTO Kampe (Dato, Spiller1, Spiller2, Point1, Point2) VALUES (" . $date->format('Ymd') . ", " . $_POST["player1"] . ", " . $_POST["player2"] . ", " . $_POST["point1"] . ", " . $_POST["point2"] . ")");
		$data['result'] = $result;
	//echo $_POST["Dato"];
	//echo $_POST["Spiller1"];
	//echo "Match added: ";
	//echo $date->format('Ymd');
		mysqli_close($link);
	//}

  // if there are no errors, return a message
  $data['success'] = true;
  $data['message'] = 'Success!';
  $data['dato'] = $date->format('Ymd');
}

// return all our data to an AJAX call
echo json_encode($data);
// some code
?>