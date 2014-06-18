<?php
	function generateFinalUrl($myUrl, $parameters)
	{
		$finalUrl = $myUrl;
		$nextSeparator = "?";

		$arraySize = count($parameters);

		if($arraySize > 0)
		{
		    for($i=0;$i<$arraySize;$i++)
		    {
		        if(!empty($parameters[$i]))
		        {
		            $nextSeparator = ($i == 0) ? "?" : "&";
		            
		            $finalUrl .= $nextSeparator . $parameters[$i];
		        }
		    }
		}

		return $finalUrl;
	}

	function getDataFromFile($filename, $isRemote=false, $parse=true)
	{
		$fileExist = false;

		if($isRemote)
		{
			$headers = get_headers($filename);
			$responseCode = $headers[0];
			$fileExist = (strpos($responseCode,"200")) ? true : false ;
		}
		else if (!$isRemote && file_exists($filename) && is_readable ($filename))
		{
			$fileExist = true;
		}

		if ($fileExist)
		{
			$dataString = file_get_contents($filename);

			if($parse)
			{
				$parsedData = json_decode($dataString, true);

				switch (json_last_error())
				{
			        case JSON_ERROR_NONE:
			            return $parsedData;
			        break;
			        default:
			            //return json_last_error();
			        	return -1;
			        break;
			    }
			}
			else
			{
				return $dataString;
			}

		}
		else
			return -1;
	}

	function saveDataToFile($data, $filename)
	{
		if(!empty($data))
		{
			$dataString = json_encode($data);

			if(file_put_contents($filename, $dataString) === false)
			{
				return -1;
			}

			switch (json_last_error())
			{
		        case JSON_ERROR_NONE:
		            return 0;
		        break;
		        default:
		            //return json_last_error();
		        return -1;
		        break;
		    }

		}
		else
			return -1;
	}

	function getAPIKeys()
	{
		return getDataFromFile("pass.json");
	}

	function getWeatherCode($code)
	{
		    //Missings :
    /*
     Extreme
     ID	 Meaning
     900	 tornado
     901	 tropical storm
     902	 hurricane
     903	 cold
     904	 hot
     905	 windy
     906	 hail
     Additional
     ID	 Meaning
     950	 Setting
     951	 Calm
     952	 Light breeze
     953	 Gentle Breeze
     954	 Moderate breeze
     955	 Fresh Breeze
     956	 Strong breeze
     957	 High wind, near gale
     958	 Gale
     959	 Severe Gale
     960	 Storm
     961	 Violent Storm
     962	 Hurricane
     
     */
		switch ($code) {
			//Thunderstorm
			case 200:
			case 201:
			case 202:
			case 210:
			case 211:
			case 212:
			case 221:
			case 230:
			case 231:
			case 232:
			//Dizzle
			case 300:
			case 301:
			case 302:
			case 310:
			case 311:
			case 312:
			case 313:
			case 314:
			case 321:
			//Rain
			case 500:
			case 501:
			case 502:
			case 503:
			case 504:
			case 511:
			case 520:
			case 521:
			case 522:
			case 531:
				return 1;
				break;
			//Snow
			case 600:
			case 601:
			case 602:
			case 611:
			case 612:
			case 615:
			case 616:
			case 620:
			case 621:
			case 622:
			return 4;
			break;
			//Sun
			case 800:
			case 801:
				return 3;
				break;
			//Clouds
			case 802:
			case 803:
			case 804:
				return 2;
				break;
			default:
				return 0;
				break;
		}
	}
?>