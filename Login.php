<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>租赁系统登录</title>
    <style type="text/css">
        body {
            text-align: center;
            background-color: #f2f4f7;
            background-repeat: no-repeat;
        }

        .mabel {
            display: inline-block;
            text-align: center;
            margin-top: 80px;
            padding: 20px 10px 20px 10px;
            background-color: white;
            width: 200px;
            box-shadow: 0px 0px 4px 2px rgb(187, 184, 184);
            font-size:25px;
        }
        .mabel a{
            text-decoration: none;
            color: black;
        }
        .customer {
            display: inline-block;
            width: 220px;
            height: 220px;
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

if (!empty($_POST)) {
    include("conn.php");
    //首先接受登录页面传进来的参数
    $last_name = $_POST["last-name"];
    $name = $_POST["name"];
    //这里要用存储过程getCusId获取id，如果获取为
    $proc01 = "call getCusId('$last_name','$name')";
    $res = mysqli_query($conn, $proc01) or die(mysqli_error($conn));//执行数据库
    $dbrow = mysqli_fetch_array($res);//获取一行数据
    $cusId = $dbrow['cus_id'];
    if(isset($cusId)){
        echo "<script>
        alert('登录成功！');
        window.location.href='Customer/all-luxury.php?cus_id=$cusId';
        </script>";//登录成功，传cusId过去
    }else{
        echo "<script>alert('找不到该用户，请重新输入用户信息');
            </script>";
    }    
}

?>

<body>

    <div class="mabel">
        <a href="Mabel/all-luxury.php">Mabel</a>
    </div>
    <br>
    <div class="customer">
        <p>Customer</p>
        <div class="Login-menu">
            <form action="<?php echo "Login.php";?>" method="post">
                <table cellpadding="5">
                    <tr>
                        <td>
                            <input type="text" placeholder="Last Name" name="last-name" class="username">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" placeholder="Name" name="name">
                        </td>
                    </tr>
                </table>
                <input type="submit" class="Login-but" value="Login">
            </form>
        </div>
</body>

</html>