<?php

include("../dbconnect.php");

$cusid = $_GET["cusid"];

$query="select car.carid,name,cartype,status,images from car inner join images where car.carid=images.carid and
 images=(select images from images where carid=car.carid limit 1) order by uploadedtime desc"; //to select cars along with its status

if($result = $conn->query($query))
{
    $arr = array();

    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {

        //setting link 
        if($row["cartype"]==='new')
        {
            $row["link"]="newcar.php?carid=".$row["carid"];
        }

        else if($row["cartype"]==='resale')
        {
            $row["link"]="resalecar.php?carid=".$row["carid"];
        }

        else
        {
            $row["link"]="rentalcar.php?carid=".$row["carid"];
        }


        $featurequery = "select features from features where carid=".$row["carid"]." limit 3";
        $featureresult = $conn->query($featurequery);
        $featuresarr= array();

        if($featureresult)
        {
        while($features = $featureresult->fetch_array(MYSQLI_ASSOC))
        {
               $featuresarr[] = $features["features"];   
        }

        $row["features"] =  $featuresarr;
        }
    
        $arr[] = $row;
    }
    
    echo json_encode($arr);
}

?>