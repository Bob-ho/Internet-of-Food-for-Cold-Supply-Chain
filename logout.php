<?php
if(!isset($_SESSION))
{
	session_start();
}
session_destroy();
if(isset($_GET["cp"]))
{
	header("location:index.php?pg=login.php");
}
else
{
	header("location:index.php");
}
?>