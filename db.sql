CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;
CREATE TABLE `weather_data_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` varchar(100) DEFAULT '',
  `latitude` varchar(100) DEFAULT '',
  `weather_condition` varchar(100) DEFAULT '',
  `main_temprature` varchar(100) DEFAULT '',
  `max_temprature` varchar(100) DEFAULT '',
  `min_temprature` varchar(100) DEFAULT '',
  `perssure` varchar(100) DEFAULT '',
  `humidity` varchar(100) DEFAULT '',
  `wind_speed` varchar(100) DEFAULT '',
  `clouds` varchar(100) DEFAULT '',
  `unix_time` int(11) DEFAULT '0',
  `country` varchar(100) DEFAULT '',
  `city_id` varchar(50) DEFAULT '',
  `city_name` varchar(100) DEFAULT '',
  `weather_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
