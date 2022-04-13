<?php
include ("ta_session.php");
include("../ta_connect.php");
$myQuery_group="select menu_id,menu_name from $dbname.TBLA_EPF_MENU where nvl(is_parent,0)=1 order by MENU_ORDER ";
$rs_group=$conn->Execute($myQuery_group);
$cbo_group="";
if(!$rs_group->EOF)
{
	$cbo_group="0@Parent";
	while(!$rs_group->EOF)
	{
		if($cbo_group=="")
			$cbo_group=$rs_group->fields[0]."@".$rs_group->fields[1];
		else
			$cbo_group.="@".$rs_group->fields[0]."@".$rs_group->fields[1];
		$rs_group->MoveNext();
	}
}
echo $cbo_group;		
?>