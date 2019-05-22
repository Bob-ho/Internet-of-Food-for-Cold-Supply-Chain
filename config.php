<?php
$host='localhost';
$user='root';
$password='';

$connection=mysql_connect($host,$user,$password);
if(!$connection)
{
	die("connecton to the server could not establish");
}
$result=mysql_select_db("online_seafood");
if(!$result)
{
	die("database could not establish");
}	
?>
