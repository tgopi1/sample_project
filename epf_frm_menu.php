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
<link rel="stylesheet" type="text/css" href="scripts/development-bundle/themes/redmond/jquery-ui-1.8.18.custom.css"/>
<script type="text/javascript" src="scripts/development-bundle/ui/jquery-ui-1.8.18.custom.js"></script>
<link href="style.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
var myGrid;
var myDataProcessor;
function doInitGrid()
{
	myGrid=new dhtmlXGridObject('Qa_grid');
	myGrid.setImagePath("dhtmlxgrid/codebase/imgs/");
	myGrid.setHeader("Sno,File Name,File Path,Is-Parent,Parent Node,Status,Level,Gen Flag,Order");
	myGrid.setInitWidths("50,150,150,100,100,80,80,80,80");
	myGrid.setColAlign("center,left,left,center,center,center,left,center,center");
	myGrid.setColTypes("ro,ed,ed,ch,coro,ch,coro,ch,ed");
	myGrid.setSkin("dhx_blue");
	myGrid.enableColSpan(true);
	myGrid.setColSorting("na,str,str,str,str,str,str,str,na");
	myGrid.getCombo(4).put(0,"Parent");
	myGrid.getCombo(6).put(1,"Mapping");
	myGrid.getCombo(6).put(2,"Forms");
	myGrid.getCombo(6).put(3,"Reports");
	$.ajax({
		async: false,
		type: "POST",
		url: "includes/epf_frm_menu_dhx_cbo_parents.php",
		success: function(msg){
			if(msg!='')
			{
				var can_type=msg.split("@");
				myGrid.getCombo(4).put('','');
				for(var i=0;i<can_type.length;i+=2)
				{
					myGrid.getCombo(4).put(can_type[i],can_type[i+1]);
				}
			}
		}
	});
	myGrid.init();
	myGrid.loadXML("includes/epf_frm_menu_load_data.php");
	myDataProcessor = new dataProcessor("includes/epf_frm_menu_update.php");
	myDataProcessor.setTransactionMode("POST",true); //set mode as send-all-by-post
	// grid column validation code
	myDataProcessor.attachEvent("onRowMark",function(id)
	{
		if (this.is_invalid(id)=="invalid") return false;
		return true;
	});
	// end of the column validation code
	myDataProcessor.setUpdateMode(false); //disable auto-update
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
	myGrid.addRow(newId," , , ,0,0, , , , ",myGrid.getRowIndex(myGrid.getSelectedId()));
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
<body onload="doInitGrid()">
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
                        <td bgcolor="#F0F0F0" class="H2" height="25" style="color:#D04528;font-weight:bold;">EPF MENU ITEMS</td>
                      </tr>
                    </table>
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
						<!--START CODING-->
							<form name="myForm">
								<table align="center">
									<tr id="grid_tr"><td colspan="2" align="center"><BR><div id="Qa_grid" style="width:887px;height:267px"></div></td></tr>
									<tr><td><br></td></tr>
									<tr id="button_tr">
										<td colspan="2" align="center">
											<div id="buttons">
												<input type="button"  value='ADD' onclick="addRow()" class="commonButton"></input>
												<input type="button"  value='REMOVE' onclick="removeRow()" class="commonButton"></input>
												<input type="button"  value='SAVE' onclick="save();" class="commonButtonGRN "></input>
												<input type="button"  value='REFRESH' onclick="refresh();" class="commonButton"></input>
											</div>
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

