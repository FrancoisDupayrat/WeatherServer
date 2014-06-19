<?php require_once("utility.php");

	$forecast = getDataFromFile("forecast.json", false, false);

	$weather = getDataFromFile("weather.json", false, false);

	$string = '{';

	if($weather != -1)
	{
		$string.='"errorweather":"0","weather":'.$weather.',';
	}
	else
	{
		$string.='"errorweather":"1","weather":"",';
	}

	if($weather != -1)
	{
		$string.='"errorforecast":"0","forecast":'.$forecast;
	}
	else
	{
		$string.='"errorforecast":"1","forecast":""';
	}

	$string.='}';

	echo $string;
?>