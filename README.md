# LoginSystem
User login system for the webpages using PHP and SQL.

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
