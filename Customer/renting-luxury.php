<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../rental-system.css" charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html;" charset="UTF-8">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style type="text/css">
        </style>

    </head>


<?php
include("../conn.php");
//首先接受登录页面传进来的参数
if(isset($_GET['cus_id'])){//获取用户的id，并且作为常量储存
    $CUSID = $_GET['cus_id'];
}
//开始获取表格数据内容
//首先获取每一列的名字
$query = "SELECT type,designer,color,price_perday,
        lease_time,return_time,l.luxury_id FROM rentals_return r,luxury l
        WHERE cus_id = $CUSID AND
        r.luxury_id = l.luxury_id";  
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));
$table_row_names = array();
$table_row_num = mysqli_num_fields($res);
for($i=0 ;$i < $table_row_num ;$i++){ //用一个数组存储所有列的名字
    $table_each_row = mysqli_fetch_field($res); //获取第i个列名字
    array_push($table_row_names,$table_each_row->name); //添加数组到尾部
}
?>
    <body>
        <div class="left-nav">
            <p class="top-logo">
                <i class="fa fa-shopping-bag"></i>
                <a href="../Login.php">租 赁 系 统</a>
            </p>
            <br>
            <p> Customer </p>
            <hr width="240px">
            <ul>
                <li><i class="fa fa-shopping-cart"></i> &nbsp; <?php echo"<a href='all-luxury.php?cus_id=$CUSID'>All Luxury</a>";?></li>
                <li><i class="fa fa-shopping-cart"></i> &nbsp; Renting Luxury</li>
                <li><i class="fa fa-shopping-cart"></i> &nbsp; <?php echo"<a href='rental-cost.php?cus_id=$CUSID'>Rental Cost</a>";?></li>
            </ul>
        </div>
        <content>
            <!-- 搜索框 -->
            <div class="search-box">
                <form action="" method="post">
                    <select name="choose" id="">
                        <option value="">Nothing</option>
                        <option value="">Nothing too</option>
                    </select>
                    <div class= "search-bar">
                        <input type="text" name="search-content" class="search-txt" placeholder=" 请输入搜索内容">
                        <button type="submit" class="search-btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <hr>
                </form>
            </div>
            <div class="table-box">
                <table class="lux-table" cellpadding="8px" cellspacing="8px">
                    <!-- 表格表头 -->
                    <tr>
<?php
for($i=0 ;$i < $table_row_num - 1;$i++){
    echo "<th>$table_row_names[$i]</th>";
}
    echo "<th>Return</th>";
?>
                    </tr>

                    <!-- 表格内容，很多行 -->
<?php

$row = mysqli_num_rows($res);    //查询一共有多少行
if($row){  //在这里输出整张数据表!!!
     for($i = 0 ;$i < $row ;$i++){
         $dbrow=mysqli_fetch_array($res); //获取每一行的段数据
         $data_each_row = array();//用来存储每一行的段数据
         echo "<tr>";
         for($j = 0 ;$j < $table_row_num ;$j++){
              array_push($data_each_row,$dbrow[$table_row_names[$j]]);
              if($j < $table_row_num - 1) //这里是不用显示最后的luxury_id
                {
                    echo "<td>$data_each_row[$j]</td>";//输出某一行的段数据
                }
         }
        //租借功能涉及到添加租赁记录，用存储过程来完成添加
        echo "
        <td><a href=\"delete-return.php?cus_id=$CUSID&luxury_id=$data_each_row[6]\"
          onclick=\"return confirm('确定现在退还吗？');\" >退还</a></td>
        </tr>
        ";
     }
}
?>

                </table>
            </div>
        </content>
    </body>
</html>