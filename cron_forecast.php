<?php require_once("utility.php");

	$keys = getAPIKeys();

	$openWeatherMapConfig = getDataFromFile("OpenWeatherMap.json");

	$openWeatherMapConfig["forecast"]["params"][] = $openWeatherMapConfig["APIParamName"].'='.$keys["OpenWeatherAPI"];

	$url = generateFinalUrl($openWeatherMapConfig["forecast"]["url"], $openWeatherMapConfig["forecast"]["params"]);

	$responseData = getDataFromFile($url, true);

	if($responseData != -1) {
		if($responseData["cod"] == "200") {
			$sizeList = count($responseData["list"]);
			$newObject = array();

			for($i=0;$i<$sizeList;$i++) {
				$newObject[$i] = array();
				$newObject[$i]['date'] = $responseData["list"][$i]["dt"];
				$newObject[$i]['code'] = getWeatherCode($responseData["list"][$i]["weather"][0]["id"]);

				$sun_info = date_sun_info($newObject[$i]['date'], 48.6333, 2.45);//Evry coord
				
				$newObject[$i]['sunset'] = $sun_info["sunset"];
				$newObject[$i]['sunrise'] = $sun_info["sunrise"];
			}

			saveDataToFile($newObject, "forecast.json");
		}
	}
?>