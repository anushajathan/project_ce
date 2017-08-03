<?php
include_once '../include/db_connect.php';
$conn=getConn();
if(!empty($_POST['ratingPoints'])){
    $user_id = $_POST['user_id'];
    $ratingNum = 1;
    $ratingPoints = $_POST['ratingPoints'];
    
    //Check the rating row with same post ID
  echo  $prevRatingQuery = "SELECT * FROM post_rating WHERE user_id = ".$user_id;
    $prevRatingResult = mysqli_query($conn,$prevRatingQuery);
    if(mysqli_num_rows($prevRatingResult)> 0):
        $prevRatingRow = mysqli_fetch_assoc($prevRatingResult);
        $ratingNum = $prevRatingRow['rating_number'] + $ratingNum;
        $ratingPoints = $prevRatingRow['total_points'] + $ratingPoints;
        //Update rating data into the database
        $query = "UPDATE post_rating SET rating_number = '".$ratingNum."', total_points = '".$ratingPoints."', modified = '".date("Y-m-d H:i:s")."' WHERE user_id = ".$user_id;
        $update = mysqli_query($conn,$query);
    else:
        //Insert rating data into the database
        $query = "INSERT INTO post_rating (user_id,rating_number,total_points,created,modified) VALUES(".$user_id.",'".$ratingNum."','".$ratingPoints."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
        $insert = mysqli_query($conn,$query);
    endif;
    
    //Fetch rating deatails from database
    $query2 = "SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM post_rating WHERE user_id = ".$user_id." AND status = 1";
    $result = mysqli_query($conn,$query2);
    $ratingRow = mysqli_fetch_assoc($result);
    
    if($ratingRow){
        $ratingRow['status'] = 'ok';
    }else{
        $ratingRow['status'] = 'err';
    }
    
    //Return json formatted rating data
    echo json_encode($ratingRow);
}
?>