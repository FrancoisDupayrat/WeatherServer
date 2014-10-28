<?php require_once("utility.php");

	$config = getConfig();

	$openWeatherMapConfig = getDataFromFile("OpenWeatherMap.json");

	if($config != -1 || $openWeatherMapConfig != -1)
	{
		if(!empty($openWeatherMapConfig["forecast"]["params"]) &&
			!empty($openWeatherMapConfig["APIParamName"]) &&
			!empty($openWeatherMapConfig["forecast"]["url"]) &&
			!empty($config["OpenWeatherAPI"]))
		{
			$openWeatherMapConfig["forecast"]["params"][] = $openWeatherMapConfig["APIParamName"].'='.$config["OpenWeatherAPI"];

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
							mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather during saving the file');
						}
					}
					else
					{
						//mail
						mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather during checking the response validity');
					}
				}
				else
				{
					//mail
					mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather during checking response code api');
				}
			}
			else
			{
				//mail
				mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather during checking if the api server respond');
			}
		}
		else
		{
			//mail
			mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather during parsing the config file');
		}
	}
	else
	{
		mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_weather: fatal error');
	}
?>