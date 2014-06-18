<?php require_once("utility.php");

	$keys = getAPIKeys();

	$openWeatherMapConfig = getDataFromFile("OpenWeatherMap.json");

	if($keys != -1 || $openWeatherMapConfig != -1)
	{
		if(!empty($openWeatherMapConfig["forecast"]["params"]) &&
			!empty($openWeatherMapConfig["APIParamName"]) &&
			!empty($openWeatherMapConfig["forecast"]["url"]) &&
			!empty($keys["OpenWeatherAPI"]))
		{
			$openWeatherMapConfig["forecast"]["params"][] = $openWeatherMapConfig["APIParamName"].'='.$keys["OpenWeatherAPI"];

			$url = generateFinalUrl($openWeatherMapConfig["weather"]["url"], $openWeatherMapConfig["weather"]["params"]);

			$responseData = getDataFromFile($url, true);
			
			if($responseData != -1)
			{
				if($responseData["cod"] == "200")
				{
					$newObject = array();

					if(!empty($responseData["dt"]) && !empty($responseData["sys"]["sunrise"]) && !empty($responseData["sys"]["sunset"]) && !empty($responseData["weather"][0]["id"]))
					{
						$newObject['date'] = $responseData['dt'];
						$newObject['sunrise'] = $responseData['sys']['sunrise'];
						$newObject['sunset'] = $responseData['sys']['sunset'];
						$newObject['code'] = getWeatherCode($responseData['weather'][0]['id']);

						if(saveDataToFile($newObject, 'weather.json') == -1)
						{
							//mail
						}
					}
					else
					{
						//mail
					}
				}
			}
		}
		else
		{
			//mail
		}
	}
	else
	{
		//mail
	}
?>