<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>表名列表</title>
    <style type="text/css">
        body {
            text-align: center;
            background-color: #f2f4f7;
            background-repeat: no-repeat;
        }

        .customer {
            display: inline-block;
            width: 300px;
            height: 300px;
            margin-top: 25px;
            background-color: white;
            box-shadow: 0px 0px 4px 2px rgb(187, 184, 184);
        }
        .customer p{       
            font-size:25px;
        }
        .Login-menu {
            text-align: center;
        }

        .Login-menu table {
            margin-left: 30px;
        }

        .Login-menu i {
            color: gray;
        }

        .Login-menu table input {
            border-bottom: 1px solid gray;
            border-left-width: 0;
            border-top-width: 0;
            border-right-width: 0;
            width: 150px;
            transition-duration: 0.3s;

        }

        /* 这里要用outline才能去除掉边框 */
        .Login-menu table input:focus {
            outline: none;
        }

        .Login-menu table input:hover {
            border-bottom: 1px solid #327fc4d8;
        }

        /* 按钮和右侧大图片 */
        .Login-but {
            border: 1px solid #40aff7;
            border-radius: 10px;
            margin-top: 12px;
            background-color: #40aff7;
            color: white;
            width: 140px;
            height: 40px;
            box-shadow: 1px 1px 5px 1px rgb(153, 151, 151);
            cursor: pointer;
            transition-duration: 0.6s;
            font-size:20px;
        }

        .Login-but:hover {
            background-color: #299be7;
        }
    </style>
</head>

<?php
if(isset($_GET['cus_id'])){
    $CUSID = $_GET['cus_id'];
    $LUXID = $_GET['luxury_id'];
}

//使用存储过程进行租赁记录的添加
if (isset($_POST['return_date'])) {//提交租赁信息的内容后进行处理
    include("../conn.php");//先连接数据库
    $return_date = $_POST['return_date'];
    if(isset($_POST['insurance']) == "yes"){
        $insurance = 1;
    }else{
        $insurance = 0;
    }

    //使用过程
    $proc02 = "call addRentals($CUSID,$LUXID,'$return_date',$insurance);";
    $res = mysqli_query($conn, $proc02) or die(mysqli_error($conn));//执行数据库
    $_proc02 = "call addRentals_Return($CUSID,$LUXID,'$return_date',$insurance);";
    $res = mysqli_query($conn, $_proc02) or die(mysqli_error($conn));//执行数据库
    //将luxury表当中对应的商品的is_lease属性改成0
    $query = "UPDATE luxury SET is_lease=0 WHERE luxury_id = $LUXID";
    $res02 = mysqli_query($conn, $query) or die(mysqli_error($conn));//执行数据库

    echo "<script>alert('租赁成功！');
    window.location.href='all-luxury.php?cus_id=$CUSID';
    </script>";
}else if(!empty($_POST)){
    echo "<script>alert('请先填写归还时间！'); </script>";
}
?>

<body>

    <br>
    <div class="customer">
        <p>Lease Apply</p>
        <div class="Login-menu">
            <form action="<?php echo"rent-form.php?cus_id=$CUSID&luxury_id=$LUXID"; ?>" 
                    method="post">
                <table cellpadding="5">
                    <tr>
                        <td>cus id</td>
                        <td>
                            <input type="text" value="<?php echo"$CUSID"?>" name="cus_id" readonly>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>lux id</td>
                        <td>
                            <input type="text" value="<?php echo"$LUXID"?>" name="luxury_id" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>return</td>
                        <td>
                            <input type="date" name="return_date" id="return-date" min="2022-01-01">
                            <script>
                                // 将min最小值更改为当前日期
                                const today = new Date();
                                const formattedDate = today.toISOString().slice(0, 10);
                                document.getElementById('return-date').min = formattedDate;
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td>insurance</td>
                        <td>
                            <input type="checkbox" name="insurance" value="yes" id="">
                        </td>
                    </tr>
                </table>
                <input type="submit" class="Login-but" value="Confirm">
            </form>
        </div>


</body>

</html>