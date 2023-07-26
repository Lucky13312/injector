<?php
    $db_host = 'localhost';
    $db_username = 'db_username';
    $db_name = 'db_name';
    $db_pass = 'db_pass';

    $username = $_POST['username'];
    $hwid = $_POST['hwid'];

    $connect = mysqli_connect($db_host, $db_username, $db_pass) or die("Couldn't connect to the database");
    mysqli_select_db($connect, $db_name) or die("Couldn't connect to the database");

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $date = time();

    $query = 'INSERT INTO `db_name`.`loader_log` (`username`, `hwid`, `ip`, `date`) VALUES ("'.$username.'", "'.$hwid.'", "'.$ip.'", "'.$date.'")';
    mysqli_query($connect, $query);

    $query = 'SELECT * FROM `xf_user` WHERE `username`="'.$username.'"';
    $res = mysqli_query($connect, $query);
    if(mysqli_num_rows($res) == 0)
    {
        http_response_code(403);
        die();
    }
    else
    {
        $row = mysqli_fetch_array($res);
        if($row['user_group_id'] < 3)
        {
            http_response_code(403);
            die();
        }
    }

    $query = 'SELECT * FROM `hwids` WHERE `username`="'.$username.'"';
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result) == 0)
    {
        $query2 = 'INSERT INTO `db_name`.`hwids` (`username`, `hwid`) VALUES ("'.$username.'", "'.$hwid.'")';
        mysqli_query($connect, $query2);
        http_response_code(303);
    }    
    else
    {
        $query2 = 'SELECT * FROM `hwids` WHERE `username`="'.$username.'" AND `hwid`="'.$hwid.'"';
        $res2 = mysqli_query($connect, $query2);
        if(mysqli_num_rows($res2) == 1)
        {
            http_response_code(303);
        }
        else
        {
            http_response_code(403);
        }
    }
?>