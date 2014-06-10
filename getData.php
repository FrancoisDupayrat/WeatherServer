<?php require_once("utility.php");

	$forecast = getDataFromFile("forecast.json", false, false);

	$weather = getDataFromFile("weather.json", false, false);

	echo '{"weather":'.$weather.', "forecast":'.$forecast.'}';
?>