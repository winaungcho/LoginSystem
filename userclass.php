
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
    class Users {
        var $servername = "localhost";
        var $username = "user";
        var $password = "pass";
        var $dbname = "database";
        var $tablename = "members";
        var $conn;
        var $loginfo;
        var $userinfo;
        function __construct0()
        {
            $a = func_get_args();
            $i = func_num_args();
            $this->loginfo = array();
            
            if ($i == "4")
                $this->__construct4($a);
            else
                $this->initTable();
        }
        // constructure with server, user, password and database name
        function __construct($h, $u, $p, $d) {
            $this->servername = $h;
            $this->username = $u;
            $this->password = $p;
            $this->dbname = $d;
            $this->loginfo = array();
            $this->initTable();
        }
        // initialize for data connection and table creation
        function initTable(){
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            // Check connection
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            $query = "SELECT id FROM $this->tablename";
            $result = $this->conn->query($query);

            if(empty($result)){
                $sql1 = "CREATE TABLE IF NOT EXISTS $this->tablename (
                    `id` int(11) AUTO_INCREMENT,
                    `username` varchar(32),
                    `password` varchar(64),
                    `email` varchar(255),
                    `token` varchar(32),
                    `role` varchar(12),
                    `logindate` datetime,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `username` (`username`),
                    UNIQUE KEY `email` (`email`))";
                if($this->conn->query($sql1) === TRUE) {
                    $this->loginfo[] = "Database and Table Online.";
                    $query = "ALTER TABLE $this->tablename ADD `last` long NOT NULL AFTER `logindate`";
                    if($this->conn->query($query) === TRUE) {
                        $this->loginfo[] = "Table Updated by adding last.";
                    }
                    $password = md5("aungcho");
                    $dt=date("Y-m-d H:i:s");
                    $query = "INSERT INTO $this->tablename (username, password, email, logindate, role, last) VALUES ('aungcho', '$password', 'aungcho@mydomain.com', '$dt', 'admin', ".time().")";
                    if ($this->conn->query($query) === TRUE) {
                        $this->loginfo[] = "New record created successfully";
                    } else {
                        $this->loginfo[] = "Error: " . $query . " " . $this->conn->error;
                    }
                }else{
                    $this->loginfo[] = "Database and Table Offline" . $this->conn->error;
                }
            } else {
                $this->loginfo[] = "$this->tablename Table already exists";
            }
            
        }
        // create database
        function createDatabase(){
            // Create connection
            $this->conn = new mysqli($this->servername, $this->username, $this->password);
            // Check connection
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            // Create database
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            if ($this->conn->query($sql) === TRUE) {
                $this->loginfo[] = "Success creating database ";
                return TRUE;
            } else {
                $this->loginfo[] = "Error creating database: " . $this->conn->error;
            }
            return FALSE;
        }
        function __destruct(){
            $this->conn->close();
        }
        // print errors and information through user process
        function printLog(){
            for ($i=0;$i<count($this->loginfo);$i++){
                echo $this->loginfo[$i]."<br/>";
            }
        }
        function getLogInfo(){
            $html="";
            
            if (!isset($this->loginfo))
                return $html;
            for ($i=0;$i<count($this->loginfo);$i++){
                $html .= $this->loginfo[$i]."<br/>";
            }
            
            return $html;
        }
        function clearLog(){
            $this->loginfo = array();
        }
        function getUserinfo(){
            return $this->userinfo;
        }
        // check username and password in table
        function login($un, $p){
            $err=0;
            $this->clearLog();
            if (empty($un)){
                $this->loginfo[] = "User name is empty";
                $err++;
            }
            if (empty($p)){
                $this->loginfo[] = "Password is empty";
                $err++;
            }
            if ($err==0){
                $ps = md5($p);
                $query = "SELECT * FROM $this->tablename WHERE username='$un' AND password='$ps' LIMIT 1";
			    $results = $this->conn->query($query);

			    if ($results->num_rows == 1) { // user found
				    // check if user is admin or user
				    $this->userinfo = $results->fetch_assoc();
				    $this->loginfo[] = "Username is valid.";
				    return TRUE;
			    }
            }
			$this->loginfo[] = "invalid user";
            return FALSE;
        }
        // create new user
        function register($un, $p, $e){
            $err=0;
            $this->clearLog();
            if (empty($un)){
                $this->loginfo[] = "User name is empty";
                $err++;
            }
            if (empty($p)){
                $this->loginfo[] = "Password is empty";
                $err++;
            }
            if (empty($e)){
                $this->loginfo[] = "Email is empty";
                $err++;
            }
            if ($err == 0){
            $pass = md5($p);
            $dt=date("Y-m-d H:i:s");
            echo $un.":".$p."<br/>";
            $query = "INSERT INTO $this->tablename (username, password, email, logindate, last) VALUES ('$un', '$pass', '$e', '$dt', ".time().")";
            return $this->conn->query($query);
            }
            return FALSE;
        }
        // update field by username
        function updateUser($un, $col, $val){
            if ($col == "password")
                $val = md5($val);
            $query = "UPDATE $this->tablename SET $col='$val' WHERE username='$un'";
            return $this->conn->query($query);
        }
    }
?>
<?php
/*
    session_start();
    $db->printLog();
    echo "All tasks finished<br/>";
    $db->updateUser("aungcho","email","aungcho@yahoo.com");
    if ($db->login("aungcho", md5("aungcho"))){
        echo "User name:".$db->getUserinfo()["username"];
    } else echo "not Logged In";
    echo "<br/>";
    print_r($db->getUserinfo());
    */
// class for the login process
class LoginSystem extends Users{
    var $htmlform="";
    var $msg="";
    var $login = FALSE;
    
    // html login form string
    var $loginformhtml = "<h2>Login</h2>
        <p>Please fill form</p>
        <form action=\"_page_\" method=\"post\">
            <div>
                <label>Username</label>
                <input type=\"text\" name=\"username\" >
            </div>    
            <div>
                <label>Password</label>
                <input type=\"password\" name=\"password\">
            </div>
            <div>
                <input type=\"submit\" name=\"login\" value=\"Login\">
            </div>
            <p>Don't have an account? <a href=\"?signup=1\">Sign up now</a>.</p>
        </form>";
    // html register form string
    var $registerformhtml = "<h2>Register</h2>
        <p>Please fill form</p>
        <form action=\"_page_\" method=\"post\">
            <div>
                <label>Username</label>
                <input type=\"text\" name=\"username\" >
            </div>
            <div>
                <label>Email</label>
                <input type=\"email\" name=\"email\" >
            </div>    
            <div>
                <label>Password</label>
                <input type=\"password\" name=\"password1\">
            </div>
            <div>
                <label>Confirm Password</label>
                <input type=\"password\" name=\"password2\">
            </div>
            <div>
                <input type=\"submit\" name=\"register\" value=\"Register\">
            </div>
            <p>Have an account? <a href=\"?signin=1\">Sign in now</a>.</p>
        </form>";
    // html password reset form string
    var $resetformhtml = "<h2>Reset Password</h2>
        <p>Please fill form</p>
        <form action=\"_page_\" method=\"post\">
            <div>
                <label>Password</label>
                <input type=\"password\" name=\"password1\">
            </div>
            <div>
                <label>Confirm Password</label>
                <input type=\"password\" name=\"password2\">
            </div>
            <div>
                <input type=\"submit\" name=\"resetpassword\" value=\"Reset Password\">
            </div>
            <p>Log out? <a href=\"?signout=1\">Sign Out</a>.</p>
        </form>";
    function __construct($h, $u, $p, $d) {
        parent::__construct($h, $u, $p, $d);
        $this->clearLog();
        $page=htmlspecialchars($_SERVER["PHP_SELF"]);
        
        $this->loginformhtml = str_replace("_page_", $page, $this->loginformhtml);
        $this->registerformhtml = str_replace("_page_", $page, $this->registerformhtml);
        $this->resetformhtml = str_replace("_page_", $page, $this->resetformhtml);
        
    }
    // get form string
    function getHtml()
    {
        return $this->htmlform;
    }
    // get message through login process
    function getMsg(){
        return $this->msg;
    }
    // check login status and userdata manipulation
    function runsystem(){
        $this->login = FALSE;
        
        if (isset($_SESSION["username"])){
            $this->login = TRUE;
            $this->htmlform = "login as ".$_SESSION["username"]."💗"."<br/>";
            $this->htmlform .= " ☓ Logout <a href=\"?signout=1\">Sign out now</a><br/>";
            $this->htmlform .= " 💫 Reset Password <a href=\"?resetpassword=1\">Reset Password</a>";
            if (isset($_GET["signout"])){
                $_SESSION = array();
                // Destroy the session.
                session_destroy();
                $this->login = FALSE;
                $this->htmlform = $this->loginformhtml;
            }
            if (isset($_GET["resetpassword"])){
                $this->htmlform = $this->resetformhtml;
            }
            if (isset($_POST["resetpassword"])){
                $username = $_SESSION["username"];
                $password1 = $_POST["password1"];
                $password2 = $_POST["password2"];
                if ($password1 == $password2){
                    $password = $password1;
                    if ($this->updateUser($username, "password", $password)){
                        $_SESSION = array();
                        $this->login = FALSE;
                        $this->htmlform = $this->loginformhtml;
                    } else {
                        $this->msg = "Something went wrong!😢";
                        $this->htmlform = $this->resetformhtml;
                    }
                } else {
                    $this->msg = "Password not matched.😜";
                    $this->htmlform = $this->resetformhtml;
                }
            }
        }
        else {
            if (isset($_POST["login"])){
                $username = $_POST["username"];
                $password = $_POST["password"];
                if ($this->login($username, $password)){
                    $_SESSION["username"] = $username;
                    $_SESSION["userinfo"] = $this->getUserinfo();
                    $this->login = TRUE;
                }
            }
            if (isset($_POST["register"])){
                $username = $_POST["username"];
                $email = $_POST["email"];
                $password1 = $_POST["password1"];
                $password2 = $_POST["password2"];
                if ($password1 == $password2){
                    $password = $password1;
                    if ($this->register($username, $password, $email)){
                        $_SESSION["username"] = $username;
                        $_SESSION["userinfo"] = $this->getUserinfo();
                        $this->login = TRUE;
                    }
                }
            }
            
            $this->htmlform = "";
        
            if (!$this->login){
                $this->msg = $this->getLogInfo();
                if (isset($_GET["signup"]) || isset($_POST["register"])){
                    if(isset($password1))
                        $this->msg .= "<br/>Password doesn't match.😛";
                    $this->htmlform = $this->registerformhtml;
                } else
                    $this->htmlform = $this->loginformhtml;
            } else {
                $this->htmlform = "login as ".$_SESSION["username"]."💗"."<br/>";
                $this->htmlform .= " ☓ Logout <a href=\"?signout=1\">Sign out now</a><br/>";
                $this->htmlform .= " 💫 Reset Password <a href=\"?resetpassword=1\">Reset Password</a>";
            }
        }
    }
}
    
?>

<?php
if (!isset($mypage)){
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
        }
    </style>
</head>
<body>
    <div class="usercontent">
    <?php
    echo "<span style=\"color:red\">".$logsys->getMsg()."</span>";
    echo $logsys->getHtml();   ?>
    </div>    
</body>
</html>
<?php
}
?>
