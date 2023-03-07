<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../rental-system.css" charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html;" charset="UTF-8">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" type="text/css" href="mabel-rental.css" charset="UTF-8">

    <style type="text/css">

    </style>

    </head>


<?php
include("../conn.php");
//首先接受登录页面传进来的参数


//开始获取表格数据内容
//首先获取每一列的名字
$query = "SELECT type,designer,Manufacturer,color
        ,price_perday,is_lease,luxury_id FROM luxury";  
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
            <p> Mabel </p>
            <hr width="240px">
            <ul>
                <li><i class="fa fa-shopping-cart"></i> &nbsp; All Luxury</li>
                <li><i class="fa fa-shopping-cart"></i> &nbsp; <?php echo"<a href='Best-Cus.php'>Best-Cus</a>";?></li>
            </ul>
        </div>
        <content>
            <!-- 搜索框 -->
            <div class="search-box">
                <form action="" method="post">
                    <select name="choose" id="">
                        <option value="designer">Nothing</option>
                        <option value="manufacturer">Nothing too</option>
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
    echo "<th>Lease</th>";
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
              if($j < $table_row_num - 1)//这里是不用输出luxury的id和islease的参数
                {
                    echo "<td>$data_each_row[$j]</td>";//输出某一行的段数据
                }
         }
         if($data_each_row[5]){//判断是否已经被租借了
            //老板可以有权限删除包包的记录
             echo "
             <td>可租借</td>
             </tr>
             ";
         }else{//表示不可删除
            echo"
            <td><strike>已租借</strike></td>
            </tr>
            ";
         }
     }
}
?>

<!--   ----------------------   下面是 添加 表单的内容   -->



<!-- js校验代码 -->
<script>
function checkLuxuryList(){
    var type = document.querySelector('input[name="type"]').value;
    var designer = document.querySelector('input[name="designer"]').value;
    //var manufac = document.querySelector('input[name="manufacturer"]').value;
    var color = document.querySelector('input[name="color"]').value;
    var price = document.querySelector('input[name="price_perday"]').value;
    
    if(typeof type != "string" || typeof designer != "string" ||
    typeof color != "string"){
        alert("type等名称填写不合法");
        return false;
    }
    var Reg = /^\d+(\.\d{1,2})?$/.test(price);//匹配是否只有两位小数
    if(Reg){
        return true;
    }
    alert("价格输入不合法！只能输入1~2位的浮点型");
    return false;
}
</script>
<!-- -------------------------------- -->


<form action="<?php echo "all-luxury.php";?>"
            method='post'>
<?php


    if(isset($_POST["plus-syn"]) == "+"){//显示要添加的新的信息
        echo "<tr>";
        for($i=0 ;$i < $table_row_num - 2;$i++){
            echo "<td><input type='text' name='$table_row_names[$i]' id='$table_row_names[$i]'></td>";
        }
        //这里用onclick来实现内容校验，例如不能为空，价格必须是两位小数
        echo "
        <td><input type='submit' name='change'
                value='确定' id='submit-but' onclick='return checkLuxuryList();'>
        </td>
        <td><a href='all-luxury' id='cancel-but'> 取消</a></td>
        </tr>
        ";
    }
    if(isset($_POST["change"]) == "确定"){ //在这里完成数据库的插入
        //这里用存储过程来添加包的记录
        $row_data = array();//用来存储获取到的列数据
        for($i=0 ;$i < $table_row_num - 2;$i++){
            array_push($row_data,$_POST[$table_row_names[$i]]); //将获取的post数据放入这个数组当中
        }
        $temp_lease = 1;//isLease属性默认为真
        $proc04 = "call addLuxury('$row_data[1]','$row_data[2]','$row_data[0]','$row_data[3]',$row_data[4],$temp_lease);";//完成插入语句

        $result=mysqli_query($conn,$proc04) or die(mysqli_error($conn));
        
        if($result){
            echo 
            "<script language=javascript>
            alert('添加新的包记录成功！');
            window.location.href='all-luxury.php';</script>";
        }
        else{
             echo 
             "<script language=javascript>window.alert('增添失败!');
             window.location.href='all-luxury.php';</script>";
            //echo "$row_data[0],$row_data[1],$row_data[2],$row_data[3],$row_data[4]";
        }
    }   
?>
            </form>

            <!-- table结尾 -->
                </table>
                <div class="add-button">
                <form action="<?php echo "all-luxury.php"; ?>"
                        method="post">
                        <input type="hidden" name="pass-table-name" value="$TABLE_NAME">
                        <input type="submit" name="plus-syn" value="+">
                </form>
            </div>
            </div>
        </content>
    </body>
</html>