<?php

ini_set('display_errors', 1);
//ini_set("session.cookie_lifetime", "0"); //an hour

session_start();

// if(!$_POST) {
// 	echo "<pre>";
// 	print_r($_SESSION);
// 	echo "</pre>";
// }

//DB login details
$DBusername = "root";
$DBpassword = "my_101";
$DBdatabase = "videoScheduler";

//Define link to DB
$link = mysql_connect('localhost', $DBusername, $DBpassword);

function deleteSession($uid, $VID) {
	//echo($user . ":" . $pass);

	global $DBusername, $DBpassword, $DBdatabase, $auth, $link;

	//Attempt to connect to DB
	if ($link) {
		$db_selected = mysql_select_db($DBdatabase, $link);
		if ($db_selected) {

			$query = mysql_query("DELETE FROM Sessions WHERE ID='$uid' AND videoID='$VID'");

			if($query){
				//Successful deletion
				echo(true);
			}else{
				//Unsuccessful deletion
				echo(false);
			}

		}
	}

	//close link
	mysql_close($link);
}

function addSession($vid, $start, $end) {
	//echo($user . ":" . $pass);

	global $DBusername, $DBpassword, $DBdatabase, $auth, $link;

	//Attempt to connect to DB
	if ($link) {
		$db_selected = mysql_select_db($DBdatabase, $link);
		if ($db_selected) {

			$query = mysql_query("INSERT INTO Sessions (videoID, startTime, endTime) VALUES ('$vid', '$start', '$end')") or die(mysql_error());

			if($query){
				//Successful addition
				$queryMax = mysql_query("SELECT MAX(ID) FROM Sessions");

				if($queryMax){
					//this is the newest row to be added, so it will have the new biggest number, so we need to report what the current biggest is.
					$row = mysql_fetch_array($queryMax, MYSQL_NUM);
					echo($row[0]); 
				}
			}else{
				//Unsuccessful deletion
				echo("false");
			}

		}
	}

	//close link
	mysql_close($link);
}

function updateSession($vid, $sid, $start, $end) {
	//echo($user . ":" . $pass);

	global $DBusername, $DBpassword, $DBdatabase, $auth, $link;

	//Attempt to connect to DB
	if ($link) {
		$db_selected = mysql_select_db($DBdatabase, $link);
		if ($db_selected) {

			$query = mysql_query("UPDATE Sessions SET startTime='$start', endTime='$end' WHERE ID='$sid' AND videoID='$vid'") or die(mysql_error());

			if($query){
				//Successful deletion
				echo(true);
			}else{
				//Unsuccessful deletion
				echo(false);
			}

		}
	}

	//close link
	mysql_close($link);
}

function updateCourse($ID, $CN, $VT, $FP) {
	//echo($user . ":" . $pass);

	global $DBusername, $DBpassword, $DBdatabase, $auth, $link;

	//Attempt to connect to DB
	if ($link) {
		$db_selected = mysql_select_db($DBdatabase, $link);
		if ($db_selected) {

			$query = mysql_query("UPDATE Video SET course='$CN', title='$VT', path='$FP' WHERE ID='$ID'") or die(mysql_error());

			if($query){
				//Successful deletion
				echo(true);
			}else{
				//Unsuccessful deletion
				echo(false);
			}

		}
	}

	//close link
	mysql_close($link);
}

if($_SESSION['isLoggedIn']) {

	if ($_POST) {
		//Delete session entry
		if($_POST['type'] == 'deleteSession'){
			deleteSession($_POST['uid'], $_POST['vid']);
		}else if($_POST['type'] == 'addSession'){
			addSession($_POST['vid'], $_POST['start'], $_POST['end']);
		}else if($_POST['type'] == 'updateSession'){
			updateSession($_POST['vid'], $_POST['sid'], $_POST['start'], $_POST['end']);
		}else if($_POST['type'] == 'updateCourse'){
			updateCourse($_POST['ID'], $_POST['CN'], $_POST['VT'], $_POST['FP']);
		}
	}
}
?>