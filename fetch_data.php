<?php

$user = "fill_in";
$password = "fill_in";
$host = "fill_in";
$database = "fill_in";

$connection = new mysqli($host,$user,$password,$database);

if ($connection->connect_error) {
    die($error_server);
}


$sqlStmt="SELECT * FROM fieldtable";//select from table

if ($st = $connection->prepare($sqlStmt)){
	//you can alter to use binding incase of condition e.g. WHERE
	$st->execute();
	$st->store_result();
	
	if ($st-> num_rows > 0) {
	
		$allData = array();
		
		//select fields from the table and assign field names as you deem fit
		while ($row = $result->fetch_assoc()){

			$allData[] = array(
								"_date" => $row['datdate'],
								"_themecategory" => $row['datthemecat'],
								"_themespecific" => $row['datthemespc'],
								"_comment" => $row['datcom'],
								"_latitude" => $row['datlat'],#make sure to stick to the selected field name for latitude
								"_longitude" => $row['datlon'],#make sure to stick to the selected field name for longitude
								"_imagename" => $row['datpicnm'],
								"_status" => $row['datstatus']
							);
		}
		
		$tempJSONFile = 'data.json';//temporary file to hold the array as a JSON
		file_put_contents($tempJSONFile, json_encode($allData));
		
		ob_start();
		passthru('python GeoJSONFormater.py');//make sure python is installed on server; it gets the tempJSONFile data
		$geoJSONOutput = ob_get_clean();//the geoJSON data
		file_put_contents("fieldtableData.geojson",$geoJSONOutput);//save the geoJSON data
	
	}
	
	$st->close();
    $connection->close();		
}


?>
