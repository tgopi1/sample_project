<?php
include ("ta_session.php");
include("../ta_connect.php");
$fac_id=$_POST['fac_id'];
$myQuery="select GL_CODE,GL_DESC from $dbname.TBLA_EPF_GL_CODES where fac_id=$fac_id and nvl(status,0)=1 order by GL_DESC";
$rs_group=$conn->Execute($myQuery);
$cbo_group="";
while(!$rs_group->EOF)
{
	if($cbo_group=="")
		$cbo_group=$rs_group->fields[0]."@".$rs_group->fields[1];
	else
		$cbo_group.="@".$rs_group->fields[0]."@".$rs_group->fields[1];
	$rs_group->MoveNext();
}
echo $cbo_group;		
?>