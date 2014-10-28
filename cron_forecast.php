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

			$url = generateFinalUrl($openWeatherMapConfig["forecast"]["url"], $openWeatherMapConfig["forecast"]["params"]);

			$responseData = getDataFromFile($url, true);

			if($responseData != -1)
			{
				if($responseData["cod"] == "200")
				{

					if(!empty($responseData["list"]))
					{
						$sizeList = count($responseData["list"]);
						$newObject = array();

						for($i=0;$i<$sizeList;$i++)
						{
							$newObject[$i] = array();

							if(!empty($responseData["list"][$i]["dt"]) && !empty($responseData["list"][$i]["weather"][0]["id"]))
							{
								$newObject[$i]['date'] = $responseData["list"][$i]["dt"];
								$newObject[$i]['code'] = getWeatherCode($responseData["list"][$i]["weather"][0]["id"]);

								$sun_info = date_sun_info($newObject[$i]['date'], 48.6333, 2.45);//Evry coord
								
								$newObject[$i]['sunset'] = $sun_info["sunset"];
								$newObject[$i]['sunrise'] = $sun_info["sunrise"];
							}
							else
							{
								//mail
								mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during parsing response');
								break;
							}

						}

						if(saveDataToFile($newObject, "forecast.json") == -1)
						{
							//mail
							mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during saving file');
						}
					}
					else
					{
						//mail
						mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during checking data structure');
					}
				}
				else
				{
					//mail
					mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during checking response code api');
				}
			}
			else
			{
				//mail
				mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during checking if the api server respond');
			}
		}
		else 
		{
			//mail
			mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem in cron_forecast during parsing the config file');
		}
	}
	else
	{
		mail($config['mailTo'], 'Weather Server bug repport', 'Weather Server encountered a problem');
	}
?>