WeatherServer
=========

Introduction
--

WeatherServer goal is to have OpenWeatherMap data pulled to auticiel.com so that Auticiel apps can query the weather and forecast whenever they want. The 2 main tasks:

1. get around OpenWeatherMap rate limitation
2. offer retreated weather code

How it works
--

The whole repo can be copied on the server. It is currently available on http://api.auticiel.com/weather

A cron is executed every day at 2am and 4am (in case first one fail) to refresh data. The cron is configured on ovh manager since we do not have a dedicated server.

To get the data, use getData.php. 

The cron files (cron_forecast.php and cron_weather.php) are publicly accessible to do an external refresh if needed. They will generate weather.json and forecast.json files, which store last known weather and forecast.

Weather codes
--

To view OpenWeatherMap full code list, refer to http://bugs.openweathermap.org/projects/api/wiki/Weather_Condition_Codes

The codes returned by WeatherServer are:

* UnknownWeather = 0,
* Rainy = 1,
* SmallStorm = 2,
* Storm = 3,
* SmallRain = 4,
* Cloudy = 5,
* Sunny = 6,