<?php
include ("ta_session.php");
include("../ta_connect.php");
//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may differ in your case
echo '<?xml version="1.0" encoding="iso-8859-1"?>'; 
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
			$menu_name=$_POST[$rowId."_c1"];	
			$menu_path=$_POST[$rowId."_c2"];		//item_code
			$is_parent=$_POST[$rowId."_c3"];
			$parent_node=$_POST[$rowId."_c4"];
			$status=$_POST[$rowId."_c5"];
			$menu_level=$_POST[$rowId."_c6"];
			$gen_flag=$_POST[$rowId."_c7"];
			$menu_order=$_POST[$rowId."_c8"];

			
			$myQuery_insert="insert into $dbname.TBLA_EPF_MENU(menu_name,menu_path,is_parent,parent_node,status,menu_level,menu_order,updated_by,updated_on,gen_flag) values('$menu_name','$menu_path',$is_parent,$parent_node,$status,'$menu_level','$menu_order','$sess_emp_code',sysdate,$gen_flag)";
			$bool=$conn->Execute($myQuery_insert);
			$myQuery_newId="select max(menu_id) from $dbname.TBLA_EPF_MENU where menu_name='$menu_name' and menu_path='$menu_path' and is_parent=$is_parent and parent_node=$parent_node ";
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
			$myQuery_delete="delete from $dbname.TBLA_EPF_MENU where menu_id=".$rowId;
			$bool=$conn->Execute($myQuery_delete);
			if($bool==true)
				$action="delete";
			else
				$action="delete fail";
		break;
		default:
			//row updating request
			$menu_name=$_POST[$rowId."_c1"];	
			$menu_path=$_POST[$rowId."_c2"];		//item_code
			$is_parent=$_POST[$rowId."_c3"];
			$parent_node=$_POST[$rowId."_c4"];
			$status=$_POST[$rowId."_c5"];
			$menu_level=$_POST[$rowId."_c6"];
			$gen_flag=$_POST[$rowId."_c7"];
			$menu_order=$_POST[$rowId."_c8"];
			
			
			$myQuery_update="update $dbname.TBLA_EPF_MENU set menu_name='$menu_name',menu_path='$menu_path',is_parent='$is_parent',parent_node='$parent_node',status=$status,menu_level='$menu_level',menu_order='$menu_order',updated_by='$sess_emp_code',updated_on=sysdate,gen_flag=$gen_flag where menu_id=".$rowId;					
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