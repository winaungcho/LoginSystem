<?php
/******
 * LoginSystem Class
 * User data are stored in SQL database
 * Script create table for member data if there is no table in database.
 * Script add admin acount with username and password both are "admin".
 * 
 * Usesage
 * Include this class file at the begining of your page.
 * Assign $mypage variable before including class.
 * Place 
 *  echo$logsys->getMsg()
 *  echo $logsys->getHtml();
 *  anywhere in page for the message and form of login status.
 * Look into index.php as an example. Replace index.php with your page.
 * 
 * This class is free for the educational use as long as maintain this header together with this class.
 * Author: Win Aung Cho
 * Contact winaungcho@gmail.com
 * version 1.0
 * Date: 28-9-2020
 ******/

$mypage=1;
include("userclass.php");
session_start();
//$logsys = new LoginSystem();
$logsys = new LoginSystem("localhost", "u739095826_user", "aungcho786", "u739095826_membe");
$logsys->runsystem();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .usercontent{ 
            margin: auto;
            width: 320px;
            border: 3px solid #73AD21;
            padding: 10px;
            overflow: auto;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="usercontent">
    <?php
    echo "<span style=\"color:red\">".$logsys->getMsg()."</span>";
    echo $logsys->getHtml();   ?>
    <p>This page is an example using LoginSystem class. At the begining of the page, include following code.</p>
    <code>
    &lt;?php <br/>
    &emsp; $mypage=1; <br/>
    &emsp; include("userclass.php"); <br/>
    &emsp; session_start(); <br/>
    &emsp; $logsys = new LoginSystem("localhost", "user", "password", "databasename"); <br/>
    &emsp; $logsys->runsystem(); <br/>
    ?&gt; <br/>
    </code>
    <p>
        Write 2 string variables at anywhere in page.
    </p>
    <pre>
        $logsys->getMsg();
        $logsys->getHtml();
    </pre>
    </div>    
</body>
</html>