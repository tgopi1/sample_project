<?php
include ("ta_session.php");
include("../ta_connect.php");
//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may be different in your case
echo('<?xml version="1.0" encoding="utf-8"?>'); 
$fac_id=$_GET['fac_id'];
echo"<rows id='0'>";
if($sess_epf_flag=="1" || $sess_epf_flag=="2")
{
	$myQuery="select sno,bk_code,bk_name,bk_branch,nvl(status,0),nvl(gl_code,'') from $dbname.tbla_epf_bank where fac_id=$fac_id order by bk_code";
	$rs=$conn->Execute($myQuery);
	$sno=1;
	if(!$rs->EOF)
	{
		while(!$rs->EOF)
		{
			$id=$rs->fields[0];
			echo "<row id='".$rs->fields[0]."'>";
				echo "<cell>$sno</cell>";
				$material=preg_replace('/(&amp;)/',' and ',$rs->fields[1]);
				$material=preg_replace('/[&]/',' and ',$material);
				$material=str_replace("'"," ",$material);
				$material=str_replace('"'," ",$material);
				echo "<cell>".$material."</cell>";
				$material=preg_replace('/(&amp;)/',' and ',$rs->fields[2]);
				$material=preg_replace('/[&]/',' and ',$material);
				$material=str_replace("'"," ",$material);
				$material=str_replace('"'," ",$material);
				echo "<cell>".$material."</cell>";
				$material=preg_replace('/(&amp;)/',' and ',$rs->fields[3]);
				$material=preg_replace('/[&]/',' and ',$material);
				$material=str_replace("'"," ",$material);
				$material=str_replace('"'," ",$material);
				echo "<cell>".$material."</cell>";
				echo "<cell>".$rs->fields[4]."</cell>";
				echo "<cell>".$rs->fields[5]."</cell>";
			echo "</row>";
			$rs->MoveNext();
			$sno++;
		}
	}
	else
	{
		echo "<row id='-1'>";
		echo "<cell colspan='6' align='center' style='font-weight:bold;color:orange;background-color:#1B3B8E;'>NO RECORDS FOUND</cell>";
		echo "</row>";
	}
}
else
{
	echo "<row id='-1'>";
	echo "<cell colspan='6' align='center' style='font-weight:bold;color:orange;background-color:#1B3B8E;'>NO RECORDS FOUND</cell>";
	echo "</row>";
}
echo "</rows>";
?>