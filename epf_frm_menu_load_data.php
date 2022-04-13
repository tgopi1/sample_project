<?php
include ("ta_session.php");
include("../ta_connect.php");
//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may be different in your case
echo('<?xml version="1.0" encoding="utf-8"?>'); 
echo"<rows id='0'>";
$myQuery="select menu_id,menu_name,menu_path,nvl(is_parent,0),nvl(parent_node,0),nvl(status,0),menu_level,nvl(gen_flag,0),menu_order from $dbname.TBLA_EPF_MENU order by nvl(is_parent,0) desc,menu_level,menu_order";
$rs=$conn->Execute($myQuery);
$sno=1;
if(!$rs->EOF)
{
	while(!$rs->EOF)
	{
		echo "<row id='".$rs->fields[0]."'>";
			echo "<cell>$sno</cell>";
			echo "<cell>".$rs->fields[1]."</cell>";	
			echo "<cell>".$rs->fields[2]."</cell>";	
			echo "<cell>".$rs->fields[3]."</cell>";	
			echo "<cell>".$rs->fields[4]."</cell>";	
			echo "<cell>".$rs->fields[5]."</cell>";	
			echo "<cell>".$rs->fields[6]."</cell>";
			echo "<cell>".$rs->fields[7]."</cell>";	
			echo "<cell>".$rs->fields[8]."</cell>";
		echo "</row>";
		$rs->MoveNext();
		$sno++;
	}
}
else
{
	echo "<row id='-1'>";
	echo "<cell colspan='9' align='center' style='font-weight:bold;color:orange;background-color:#1B3B8E;'>NO RECORDS FOUND</cell>";
	echo "</row>";
}
echo "</rows>";
?>