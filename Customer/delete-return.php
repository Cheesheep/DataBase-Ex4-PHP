<?php

include("../conn.php");
if(isset($_GET['luxury_id'])){
    $luxury_id = $_GET['luxury_id'];
}
if(isset($_GET['cus_id'])){
    $CUSID = $_GET['cus_id'];
}
$query = "SELECT (l.price_perday + is_insurance)*
DATEDIFF(DATE_FORMAT(return_time,'%Y-%m-%d'), DATE_FORMAT(lease_time,'%Y-%m-%d'))
AS totalCost FROM luxury l , rentals r WHERE l.luxury_id = r.luxury_id 
 AND l.luxury_id = $luxury_id";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));
$dbrow = mysqli_fetch_array($res);
$total = $dbrow['totalCost'];

echo "<script>
alert('本次租赁一共花费了：$total 美元');
</script>";

$query = "DELETE FROM rentals_return WHERE luxury_id = $luxury_id";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));


if($res){
    echo "
    <script>
    alert('退还成功！');
    window.location.href='renting-luxury.php?cus_id=$CUSID';
    </script>
    ";
}
?>