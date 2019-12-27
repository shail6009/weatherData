<?php
// Curl Request Data Function START //
	function CallAPI($method, $url, $data){
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //~ curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //~ curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
// Curl Request Data Function END //


// Push  Data in DB START  //
function getData($api_resp){

	require_once('classes/Database.class.php');
	$DB_H = new Database();
	$DB = $DB_H->DB_CONNECT();
	
	$resp_arr =json_decode($api_resp,true);
	
	$lon 			 = isset($resp_arr['coord']['lon'])?$resp_arr['coord']['lon']:'';
	$lat	 		 = isset($resp_arr['coord']['lat'])?$resp_arr['coord']['lat']:'';
	$weather_condi 	 = isset($resp_arr['weather'][0]['main'])?$resp_arr['weather'][0]['main']:'';
	$main_temp 		 = isset($resp_arr['main']['temp'])?$resp_arr['main']['temp']:'';
	$max_temp 		 = isset($resp_arr['main']['temp_max'])?$resp_arr['main']['temp_max']:'';
	$min_temp 		 = isset($resp_arr['main']['temp_min'])?$resp_arr['main']['temp_min']:'';
	$pressure 		 = isset($resp_arr['main']['pressure'])?$resp_arr['main']['pressure']:'';
	$humidity 		 = isset($resp_arr['main']['humidity'])?$resp_arr['main']['humidity']:'';
	$wind_speed 	 = isset($resp_arr['wind']['speed'])?$resp_arr['wind']['speed']:'';
	$clouds			 = isset($resp_arr['clouds']['all'])?$resp_arr['clouds']['all']:'';
	$unix_time 		 = isset($resp_arr['dt'])?$resp_arr['dt']:'';
	$country_name 	 = isset($resp_arr['sys']['country'])?$resp_arr['sys']['country']:'';
	$city_name 	 	 = isset($resp_arr['name'])?$resp_arr['name']:'';
	$city_id 	 	 = isset($resp_arr['id'])?$resp_arr['id']:'';

	$str_data = "('{$lon}','{$lat}','{$weather_condi}','{$main_temp}','{$max_temp}','{$min_temp}','{$pressure}','{$humidity}','{$wind_speed}','{$clouds}','{$unix_time}','{$country_name}','{$city_id}','{$city_name}')";
	
	$query = "INSERT INTO weather_data_tbl (longitude,latitude,weather_condition,main_temprature,max_temprature,min_temprature,perssure,humidity,wind_speed,clouds,unix_time,country,city_id,city_name) values ".$str_data;
	$insert_data = mysqli_query($DB,$query);
	
	if($insert_data){
		$respns = "Data Updated from API";
	}else{
		 $respns =  "data error :".mysqli_error($DB);
	}
	return $respns;
}
// Push  Data in DB START  //
?>













