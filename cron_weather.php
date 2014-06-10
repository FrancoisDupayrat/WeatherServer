<?php require_once("utility.php");

	$keys = getAPIKeys();

	$openWeatherMapConfig = getDataFromFile("OpenWeatherMap.json");

	$openWeatherMapConfig["forecast"]["params"][] = $openWeatherMapConfig["APIParamName"].'='.$keys["OpenWeatherAPI"];

	$url = generateFinalUrl($openWeatherMapConfig["weather"]["url"], $openWeatherMapConfig["weather"]["params"]);

	$responseData = getDataFromFile($url, true);
	
	if($responseData != -1) {
		if($responseData["cod"] == "200") {
			$newObject = array();

			$newObject['date'] = $responseData["dt"];
			$newObject["sunrise"] = $responseData["sys"]["sunrise"];
			$newObject["sunset"] = $responseData["sys"]["sunset"];
			$newObject['code'] = getWeatherCode($responseData["weather"][0]["id"]);

			saveDataToFile($newObject, "weather.json");
		}
	}
?>