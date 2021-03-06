<HTML>
	<HEAD>
		<TITLE>Weather Forecast</TITLE>
			<link rel="stylesheet" href="css/mobiscroll.javascript.min.css">
			<script src="js/mobiscroll.javascript.min.js"></script>
			<script  src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
			<meta charset="utf-8">
			<style>
				#weather_grid {
				  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
				  border-collapse: collapse;
				  width: 100%;
				}

				#weather_grid td, #weather_grid th {
				  border: 1px solid #ddd;
				  padding: 8px;
				}

				#weather_grid tr:nth-child(even){background-color: #f2f2f2;}

				#weather_grid tr:hover {background-color: #ddd;}

				#weather_grid th {
				  padding-top: 12px;
				  padding-bottom: 12px;
				  text-align: left;
				  background-color: #4CAF50;
				  color: white;
				}
				.mbsc-page{
					min-height:0%;
				}
			</style>
		<script>
			function resetData(){
				window.location=window.location;
			}
					
	</script>
		</HEAD>
	<Body>
		<h1><center>Weather Forcasting Data</center></h1>
		<form method="POST" name "search_data" id ="search_data">
			<div mbsc-page class="demo-mobile-desktop-usage">
    <div mbsc-form>
        <div class="mbsc-grid mbsc-form-grid">
            <div class="mbsc-row">
                <div class="mbsc-col-sm-12 mbsc-col-md-4">
                    <label>
                        Start Date
                        <input mbsc-input name ="start_date" id="demo-mobile"  value ="" data-input-style="box" placeholder="Start Date" />
                    </label>
                </div>
                <div class="mbsc-col-sm-12 mbsc-col-md-4">
                    <label>
                        End Date
                        <input mbsc-input  id="demo-desktop" name ="end_date" value ="" data-input-style="box" placeholder="End Date" />
                    </label>
                </div>
                <div class="mbsc-col-sm-12 mbsc-col-md-4">
                    <label>
                        City
                        <input mbsc-input  id="city_name" name="city_name" data-input-style="box" placeholder="Enter City..." />
                    </label>
                </div>
                <div class="mbsc-col-sm-12 mbsc-col-md-4">
                        <input type="submit" value ="Search"  id="search" name="search" data-input-style="box"/>
                        <input type="button" value ="Reset"  id="reset" name="reset"  onclick ="resetData();" data-input-style="box"/>
                </div>
            </div>
        </div>
    </div>
</div>	
</form>
<table id="weather_grid">
  <tr>
    <th>Weather</th>
    <th>City Name</th>
    <th>Time</th>
    <th>Temprature(<span>&#8451;</span>)</th>
    <th>Max/Min Temprature(<span>&#8451;</span>)</th>
    <th>Wind Speed(m/s)</th>
  </tr>
<?php	
	include("functions/functions.php");
	require_once('classes/Database.class.php');
	$DB 	= new Database();
	$DB_H 	= $DB->DB_CONNECT();
	//~ print_r($DB_H);
	$resp_data 	= $city	= $start_date  = $end_date='';
	
	if(isset($_POST) && !empty($_POST)){
		$case = "search";
		$city			= isset($_POST['city_name'])?trim($_POST['city_name']):'';
		$start_date		= isset($_POST['start_date'])?trim($_POST['start_date']):'';
		$end_date		= isset($_POST['end_date'])?trim($_POST['end_date']):'';
		$query			= '';

		if($end_date>=$start_date){
			if(!empty($start_date) && !empty($end_date) && !empty($city)){
					$start_unix = 	strtotime($start_date.'00:00:00');
					//~ $end_unix 	=	strtotime($end_date.date('h:i:s'));
					$end_unix 	=	strtotime($end_date.'23:59:59');
					if(($start_date==$end_date) && date("Y-m-d",$start_unix)==date("Y-m-d")){
						$data = array("q"=>$city,"APPID"=>"e9c4b34219d24634c8907639029d204b");
						$url = "https://api.openweathermap.org/data/2.5/weather";
						$method ="GET";
						$resp = callAPI($method,$url,$data);
						$resp_arr =json_decode($resp,true);
					// Pull data from API and Push to DB
						if($resp_arr['cod']=='404'){
							$resp_data = $resp_arr['message'];
						}else{
							$resp_data = getData($resp);	
						}				
						$query = "select * from weather_data_tbl where city_name = '".$city."' and unix_time >=".$start_unix." and unix_time<='".$end_unix."' order by id desc";
						//$query = "select * from weather_data_tbl where city_name = '".$city."' order by id desc";
					}else{
						$query = "select * from weather_data_tbl where city_name = '".$city."' and (unix_time >='".$start_unix."' and unix_time<='".$end_unix."') order by id desc";	
					}
				
			}else{
				 $resp_data = "Please select  filter for date and city";
			}
		}else{
			$resp_data = "End date should be greater than start date";
		}
	}else{
		$case="Default";
		$query = "select * from weather_data_tbl order by id desc";
	}
	if($query){
	$seleQuery = $query;
	$exeQ = mysqli_query($DB_H,$seleQuery);
	$rowcount=mysqli_num_rows($exeQ);
	if($rowcount){
		while($data =mysqli_fetch_array($exeQ)){
			echo "<tr><td>".$data['weather_condition']."</td><td>".$data['city_name']."</td><td>".date("Y-m-d H:i:s ",$data['unix_time'])."</td><td>".($data['main_temprature']-273)."</td><td>".($data['max_temprature']-273)."/".($data['min_temprature']-273)."</td><td>".$data['wind_speed']."</td></tr>";
		}
	}else{
			$resp_data = "Data not found";
	}
}
	echo "<b><font color='red'>".$resp_data."</font></b>";
?>		
</table>
	</Body>
</HTML>
<script type ="text/javascript">
	var case_data = "<?=$case ?>";
	var city = "<?=$city ?>";
	var start_date 	= "<?=$start_date?>";
	var end_date 	= "<?=$end_date?>";
	$(document).ready(function(){
		if(case_data=='search'){
			$("#city_name").val(city);
		   $("input[name='start_date']").val(start_date);
		   $("input[name='end_date']").val(end_date);
		}
	});
</script>

<script>

    mobiscroll.settings = {
        lang: 'en',            // Specify language like: lang: 'pl' or omit setting to use default
        theme: 'ios',          // Specify theme like: theme: 'ios' or omit setting to use default
        themeVariant: 'light'  // More info about themeVariant: https://docs.mobiscroll.com/4-9-1/javascript/calendar#opt-themeVariant
    };
    
    mobiscroll.calendar('#demo-mobile', {
        display: 'bubble'      // Specify display mode like: display: 'bottom' or omit setting to use default
    });
    
    mobiscroll.calendar('#demo-desktop', {
        display: 'bubble',     // Specify display mode like: display: 'bottom' or omit setting to use default
        touchUi: false         // More info about touchUi: https://docs.mobiscroll.com/4-9-1/javascript/calendar#opt-touchUi
    });

</script>

