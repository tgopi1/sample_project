<?php
	include ("includes/ta_session.php");
	include("ta_connect.php");
	if(@$HTTP_POST_VARS["cbo_facility_name"]=="") $HTTP_POST_VARS["cbo_facility_name"]=$sess_home_facility_id;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="version="1.0"  encoding="iso-8859-1"">
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
<title>Employee Provident Fund</title>
<link type="text/css" rel="stylesheet" href="styles/epf_common_style.css">
<link type="text/css" rel="stylesheet" href="dhtmlxgrid/codebase/dhtmlxgrid.css">
<link type="text/css" rel="stylesheet" href="dhtmlxgrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<script type="text/javascript" src="dhtmlxgrid/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="dhtmlxgrid/codebase/dhtmlxgrid.js"></script>
<script type="text/javascript" src="dhtmlxgrid/codebase/dhtmlxgridcell.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src='dhtmlxgrid/dhtmlxdataprocessor.js'></script>
<script type="text/javascript" src="scripts/epf_frm_common_js.js"></script>
<script type="text/javascript" src="scripts/epf_frm_gl_codes_js.js"></script>
<link rel="stylesheet" type="text/css" href="scripts/development-bundle/themes/redmond/jquery-ui-1.8.18.custom.css"/>
<script type="text/javascript" src="scripts/development-bundle/ui/jquery-ui-1.8.18.custom.js"></script>
<link href="style.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
var myGrid;
var myDataProcessor;
var err_first_cell="";
var err_second_cell="";
var err_third_cell="";
function validate_data(value,id,ind)
{
	$("#result").html("&nbsp;").show();
	if(ind==1)
	{
		if(value=="")
		{
			myGrid.setCellTextStyle(id,ind,"background-color:yellow;");
			err_first_cell=" Bank Code Should Not Empty...";
			return false;
		}
		else
		{
			myGrid.setCellTextStyle(id,ind,"background-color:white;");
			return true;
		}
	}
	if(ind==2)
	{
		if(value=="")
		{
			myGrid.setCellTextStyle(id,ind,"background-color:yellow;");
			err_second_cell=" Bank Name Should Not Empty...";
			return false;
		}
		else
		{
			myGrid.setCellTextStyle(id,ind,"background-color:white;");
			return true;
		}
	}
	if(ind==3)
	{
		if(value=="")
		{
			myGrid.setCellTextStyle(id,ind,"background-color:yellow;");
			err_third_cell=" Bank Branch Should Not Empty...";
			return false;
		}
		else
		{
			myGrid.setCellTextStyle(id,ind,"background-color:white;");
			return true;
		}
	}
}
function doInitGrid()
{
	myGrid=new dhtmlXGridObject('Qa_grid');
	myGrid.setImagePath("dhtmlxgrid/codebase/imgs/");
	myGrid.setHeader("Sno,Bank Code,Bank Name,Branch,Status,GL Code");
	myGrid.setInitWidths("50,80,300,150,50,150");
	myGrid.setColAlign("center,left,left,left,center,left");
	myGrid.setColTypes("ro,ed,ed,ed,ch,coro");
	myGrid.setSkin("dhx_blue");
	myGrid.enableColSpan(true);
	myGrid.setColSorting("na,str,str,str,str,str");
	var fac_id=$("#cbo_fac_id").val();
	$.ajax({
		async: false,
		type: "POST",
		data: "fac_id="+fac_id,
		url: "includes/epf_frm_dhx_cbo_gl_codes.php",
		success: function(msg){
			if(msg!='')
			{
				var types_arr=new Array();
				types_arr=msg.split("@");
				myGrid.getCombo(5).put('','');
				for(var i=0;i<types_arr.length;i+=2)
				{
					myGrid.getCombo(5).put(types_arr[i],types_arr[i+1]);
				}
			}
		}
	});
	myGrid.init();
	myGrid.loadXML("includes/epf_frm_bank_load_data.php?fac_id="+fac_id);
	myDataProcessor = new dataProcessor("includes/epf_frm_bank_update.php?fac_id="+fac_id);
	myDataProcessor.setTransactionMode("POST",true); //set mode as send-all-by-post
	// grid column validation code
	myDataProcessor.setVerificator(1,validate_data);
	myDataProcessor.setVerificator(2,validate_data);
	//myDataProcessor.setVerificator(3,validate_data);
	myDataProcessor.attachEvent("onRowMark",function(id)
	{
		if (this.is_invalid(id)=="invalid") return false;
		return true;
	});
	// end of the column validation code
	myDataProcessor.setUpdateMode("off"); //disable auto-update
	
	myDataProcessor.init(myGrid); //link dataprocessor to the grid
	myDataProcessor.defineAction("action", function(tag) {
		var str=tag.firstChild.nodeValue;
		var arr_str=str.split("~");
		var xid=arr_str[1];
		var response=arr_str[0];
		var all_ids=myGrid.getAllRowIds(',');
		var arr_all_ids=all_ids.split(",");
		for(i=0;i<arr_all_ids.length;i++)
		{
			if(arr_all_ids[i]==xid)
			{
				var line_name=myGrid.cells(arr_all_ids[i],0).getValue();
				if(arr_str[2]==0)
					$("#result").append("<br><b>row "+line_name+ " is "+response+"</b>");
				else
					$("#result").append("<br><font color='blue'><b>row "+line_name+" is "+response+"</b></font>");
				return true;
			}
		}
	});
}

function addRow()
{
	$("#result").html("&nbsp;");
	if(myGrid.getRowId(0)==-1)
		myGrid.clearAll();
	myGrid.clearSelection();
	// serial number code
	var all_ids=myGrid.getAllRowIds(',');
	var str_sno=new Array();
	var arr_all_ids=new Array();
	if(all_ids!="")
	{
		arr_all_ids=all_ids.split(",");
		for(var i=0;i<arr_all_ids.length;i++)
		{
			str_sno[i]=myGrid.cells(arr_all_ids[i],0).getValue();				
		}
	}
	else
		str_sno[0]=0;
	var max_sno=Math.max.apply(0,str_sno);
	//end of serial number code
	var newId=(new Date()).valueOf();
	myGrid.addRow(newId," , , , ,0, , ",myGrid.getRowIndex(myGrid.getSelectedId()));
	myGrid.cells(newId,0).setValue(parseInt(max_sno,10)+1);
	myGrid.showRow(newId);
}
function removeRow()
{
	$("#result").html("&nbsp;");
	myGrid.deleteSelectedItem();
}

function save()
{
	$("#result").html("&nbsp;");
	myDataProcessor.sendData();
	var str_msg="";
	if(err_first_cell!="")
		str_msg+="<tr align='left'><td align='right'><b>BANK CODE:</b></td><td>"+err_first_cell+"</td></tr>";
	if(err_second_cell!="")
		str_msg+="<tr align='left'><td align='right'><b>BANK NAME:</b></td><td>"+err_second_cell+"</td></tr>";
	if(err_third_cell!="")
		str_msg+="<tr align='left'><td align='right'><b>BANK BRANCH:</b></td><td>"+err_third_cell+"</td></tr>";
	if(str_msg!="")
	{
		str_msg="<table border=0 align='center'>"+str_msg+"</table>";
		$("#diag_div").html(str_msg);
		$("#diag_div").dialog( "option", "height", 180 );
		$("#diag_div").dialog( "option", "width", 470 );
		$("#diag_div" ).dialog( "option", "buttons", { "Ok": function() { $(this).dialog("close"); } } );
		$("#diag_div").dialog('open');
	}
	str_msg="";
	err_first_cell="";
	err_second_cell="";
	err_third_cell="";
}
function refresh()
{
	$("#result").html("&nbsp;");
	myGrid.clearSelection();
	doInitGrid();
}
</script>
<style type="text/css">
/* for grid header values,cell values aligment*/
table{
border-collapse: collapse;
border-spacing: 0;
/*margin-top: 0.75em;*/
border: 0 none;
}
</style>
</head>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php include('includes/header.php'); ?>
      <tr>
        <td align="center"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center">
              <table width="97%" height="182" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#FF9999">
                <tr>
                  <td width="100%" align="center" valign="top">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr align="center">
                        <td bgcolor="#F0F0F0" class="H2" height="25" style="color:#D04528;font-weight:bold;">BANK DETAILS</td>
                      </tr>
                    </table>
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><br>
						<!--START CODING-->
							<form name="myForm">
								<table align="center">
								  <tr>
								  	<td align="right" id="fac_hide">Facility</td>
									<td align="left" id="cbo_fac_hide" ><select name="cbo_fac_id" id="cbo_fac_id" class="combo">
											<?php
												$myQuery="select facility_id,facility_name from $dbname.tbla_facility  where nvl(active_fac,0)=1 and nvl(pf_fac,0)=1";
												if($sess_epf_flag!=1)
													$myQuery.="and facility_id=$sess_home_facility_id";
												$myQuery.=" order by 2";
												$rs=$conn->Execute($myQuery);
												while(!$rs->EOF)
												{
													$sel=($sess_home_facility_id==$rs->fields[0])? "selected=selected":"";
													echo "<option value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]."</option>";
													$rs->MoveNext();
												}	
												$rs->Close();
												if($sess_epf_flag!=1)
													echo "<script>
															$('#fac_hide').hide();
															$('#cbo_fac_hide').hide();					
														 </script>";
											 ?>
										  </select>
									 </td>											
									</tr>
									<tr><td>&nbsp;</td></tr>
								</table>
								<table align="center">
									<tr id="grid_tr" class="display_none"><td colspan="2" align="center"><div id="Qa_grid" style="width:797px;height:267px"></div></td></tr>
									<tr><td><br></td></tr>
									<tr id="button_tr" class="display_none">
										<td colspan="2" align="center">
										<?php 
											if($sess_epf_flag=="1" || $sess_epf_flag=="2")
											{
										?>
											<div id="buttons">
												<input type="button"  value='ADD' onclick="addRow()" class="commonButton"></input>
												<input type="button"  value='REMOVE' onclick="removeRow()" class="commonButton"></input>
												<input type="button"  value='SAVE' onclick="save();" class="commonButtonGRN "></input>
												<input type="button"  value='REFRESH' onclick="refresh();" class="commonButton"></input>
											</div>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
								<table align="center">
									<tr id="tr_focus" class="display_none"><td><input type="text" id="txt_focus"></input></td></tr>
									<tr><td align="center"><div id="result"></div></td></tr>
									<tr><td><div id="diag_div" align="center" style="display:none;"></div></td></tr>
								</table>
								</form>
						<!-- END-->	
						</td>
                      </tr>
                  </table>
				  </td>
                </tr>
              </table>
           </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td background="images/red_dots.gif"><img src="images/red_dots.gif" width="8" height="7"></td>
      </tr>
	  <tr>
		<td bgcolor="C0B2A3"><? include("includes/epf_footer.php"); ?></td>
	  </tr>
    </table></td>
  </tr>
</table>
</body>
</html>

