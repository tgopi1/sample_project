<?php
include ("ta_session.php");
include("../ta_connect.php");
//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may differ in your case
echo '<?xml version="1.0" encoding="iso-8859-1"?>'; 
$fac_id=$_GET['fac_id'];
$newId="";
$response="";
echo "<data>";      
//output update results
$ids=explode(",",$HTTP_POST_VARS["ids"]);
//for each row
for ($i=0; $i < sizeof($ids); $i++) { 
	$rowId = $ids[$i]; //id or row which was updated 
	$newId = $rowId; //will be used for insert operation	
	$mode = $HTTP_POST_VARS[$rowId."_!nativeeditor_status"]; //get request mode
	switch($mode){
		case "inserted":
			//row adding request
			$material=$_POST[$rowId."_c1"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_code=str_replace('"'," ",$material);
			
			$material=$_POST[$rowId."_c2"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_name=str_replace('"'," ",$material);
			
			$material=$_POST[$rowId."_c3"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_branch=str_replace('"'," ",$material);
			$status=$_POST[$rowId."_c4"];	
			$gl_code=$_POST[$rowId."_c5"];	
			
			$myQuery_insert="insert into $dbname.tbla_epf_bank (bk_code,bk_name,bk_branch,status,fac_id,updated_by,updated_on,gl_code) values('$bk_code','$bk_name','$bk_branch',$status,$fac_id,$sess_emp_code,sysdate,'$gl_code')";
			$bool=$conn->Execute($myQuery_insert);
			$myQuery_newId="select max(sno) from $dbname.tbla_epf_bank where fac_id=$fac_id and bk_code='$bk_code' and bk_name='$bk_name'";
			$rs_newId=$conn->Execute($myQuery_newId);
		
			if(!$rs_newId->EOF)
				$newId=$rs_newId->fields[0];
			if($bool==true)
				$action="insert";
			else
				$action="insert fail";
		break;
		case "deleted":
			//row deleting request
			$myQuery_delete="delete from $dbname.tbla_epf_bank where sno=".$rowId;
			$bool=$conn->Execute($myQuery_delete);
			if($bool==true)
				$action="delete";
			else
				$action="delete fail";
		break;
		default:
			//row updating request
			$material=$_POST[$rowId."_c1"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_code=str_replace('"'," ",$material);
			
			$material=$_POST[$rowId."_c2"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_name=str_replace('"'," ",$material);
			
			$material=$_POST[$rowId."_c3"];		
			$material=preg_replace('/(&amp;)/',' and ',$material);
			$material=preg_replace('/[&]/',' and ',$material);
			$material=str_replace("'"," ",$material);
			$bk_branch=str_replace('"'," ",$material);
			$status=$_POST[$rowId."_c4"];	
			$gl_code=$_POST[$rowId."_c5"];	
			
			$myQuery_update="update $dbname.tbla_epf_bank set bk_code='$bk_code',status='$status',bk_name='$bk_name',bk_branch='$bk_branch',updated_by='$sess_emp_code',updated_on=sysdate,gl_code='$gl_code' where sno=$rowId";				
			$bool=$conn->Execute($myQuery_update);
			if($bool==true)
				$action="update";
			else
				$action="update fail";
	}	
	if($action=='insert')
	{
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Inserting successfully...~".$rowId."~0</action>";
		echo "<action type='insert' sid='".$rowId."' tid='".$newId."'/>";
	}
	else if($action=='insert fail')
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Inserting failed...~".$rowId."~1</action>";
	else if($action=='update')
	{
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Updating successfully...~".$newId."~0</action>";
		echo "<action type='update' sid='".$rowId."' tid='".$newId."'/>";
	}
	else if($action=='update fail')
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Updating failed...~".$newId."~1</action>";
	else if($action=='delete')
	{
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Deleting successfully...~".$newId."~0</action>";
		echo "<action type='delete' sid='".$rowId."' tid='".$newId."'/>";
	}
	else if($action=='delete fail')
		echo "<action type='action' sid='".$rowId."' tid='".$newId."'>Deleting failed...~".$newId."~1</action>";
}
echo "</data>";
?>