<?php
// Sabai Technology - Apache v2 licence
// copyright 2014 Sabai Technology, LLC
		 
	if(isset($_REQUEST['act']) && $_REQUEST['act']!="")
	{
		$act=$_REQUEST['act'];

		$toDo= exec("sudo /var/www/bin/ssh.sh $act",$out);

		echo $toDo;
	}
?>
