<?php
ob_start();
ini_set('display_errors',1);  
error_reporting(E_ALL);
require_once('../../includes/functions.php');
$path = $_SERVER['DOCUMENT_ROOT']."/path.php";
require_once($path);
include($config);

include '../functions.php';

include 'class.php';

if(loggedin())
{
	include('header.php');
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo $sitepath; ?>/calend/jquery.datepick.css" rel="stylesheet">
<script src="<?php echo $sitepath; ?>/calend/jquery.plugin.js"></script>
<script src="<?php echo $sitepath; ?>/calend/jquery.datepick.js"></script>
<script>
$(function() {
	$('#popupDatepicker').datepick({yearRange: '1920:2020',minDate: new Date(12-1, 25),dateFormat: 'mm-dd-yyyy'});
});
</script>
<script type="text/javascript">
	 function update(id)
	 {
		 
		 $.ajax({
			 
				type:"POST",
				url:"activate-user.php",
				data:"id="+id,
			 });
			 alert("User Activated Successfully");
		 
	 }
</script>
<section class="row">
	<div class="form_section_content">
		
	</div>
	<div class="container">
		<form name="form1" method="post" action="">
			<input type="text" name="firstname" placeholder="First Name" class="inx_input" value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname']; }?>"/>
			<input type="text" name="lastname" placeholder="Last Name" class="inx_input" value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname']; };?>"/>
			<input type="text" name="d_o_b" placeholder="Date of Birth" class="inx_input" id="popupDatepicker" value="<?php if(isset($_POST['d_o_b'])){echo $_POST['d_o_b'];}?>"/>
			<select name="case_type" class="inx_drop" >
				<option value="">All Cases</option>
				<?php 
					$tempgetstates = mysql_query("SELECT * FROM `type_of_cases` order by `case_id` asc") or die(mysql_error());
					while($getstates = mysql_fetch_object($tempgetstates))
					{
				?>
					<option value=<?=$getstates->case_id?> <?php if(isset($_POST['case_type']) && $_POST['case_type']==$getstates->case_id){ echo "selected='selected'";} ?>><?=$getstates->name_of_case?></option>;
				<?php
					}
				?>
			</select>
			<select name="case_situation" class="inx_drop" >
				<option value="0" <?php if(isset($_POST['case_situation']) && $_POST['case_situation']==0){ echo "selected='selected'";} ?>>In Process</option>
				<option value="1" <?php if(isset($_POST['case_situation']) && $_POST['case_situation']==1){ echo "selected='selected'";} ?>>Pending</option>
				<option value="2" <?php if(isset($_POST['case_situation']) && $_POST['case_situation']==2){ echo "selected='selected'";} ?>>Closed</option>
			</select>
			<input type="submit" value="Reset" class="inx_org">
			<input type="submit" name="find_data" value="Search" class="inx_blue">
		</form>
		<?php
			if(isset($_POST['find_data']))
			{
				$firstname  = $_POST['firstname'];
				$lastname   = $_POST['lastname'];
				$dob        = $_POST['d_o_b'];
				$case_type  = $_POST['case_type'];
				$case_situa = $_POST['case_situation'];
				
				$search     = "SELECT a.*,b.* from `plantiff_information` as a, `plantiff_case_type_info` as b WHERE a.form_id=b.form_id";
				if($firstname!="" && $lastname=="")
				{
					$search .= " && a.plantiff_name LIKE '%".$firstname."%'";
				}
				if($lastname!="" && $firstname=="" )
				{
					$search .= " && a.plantiff_name LIKE '%".$lastname."%'";
				}
				if($firstname!="" && $lastname!="")
				{
					$fullname = $firstname." ".$lastname;
					$search .= " && a.plantiff_name LIKE '%".$fullname."%'";
				}
				if($dob!="")
				{
					$search .= " && a.p_d_o_b LIKE '%".$dob."%'";
				}
				if($case_type!="")
				{
					$search .= " && b.case_type=$case_type";
				}
				if($case_situa==0)
				{
					$search .= " && b.admin_approved=1 && b.case_closed=0";
				}
				elseif($case_situa==1)
				{
					$search .= " && b.admin_approved=0";
				}
				elseif($case_situa==2)
				{
					$search .= " && b.case_closed=1";
				}
				//echo $search;
				$qData = mysql_query($search) or die(mysql_error());
				if(mysql_num_rows($qData)>0)
				{	
?>
<div class="form_section_content">
			<h1 class="add_user">Search Results</h1>
			<div class="view_log_details">
				<div class="log_heading">
					<div class="serial_no_16">S.No.</div>
					<div class="user_name_16">Name</div>
					<div class="first_name_16">D.O.B</div>
					<div class="workflow_16">Work Flow</div>
					<div class="status_16">Status</div>
					<div class="action_16">Action</div>
				</div>
			</div>
		</div>
<?php
					$i=1;
					while($searchData = mysql_fetch_object($qData))
					{
?>
						<div class="log_row">
							<div class="serial_no_16"><?php  echo $i; ?>&nbsp;</div>
							<div class="user_name_16"><?php  echo ucwords($fname = $searchData->plantiff_name); ?>&nbsp;
							<input type="hidden" name="user_id" id="user_id" value="<?php echo $searchData->id; ?>">&nbsp;</div>
							<div class="first_name_16"><?php echo $dob_c = $searchData->p_d_o_b;  //echo date('m-d-Y',strtotime($dob_c)); ?></div>
							<div class="workflow_16">
								<?php
									$comment_data = mysql_query("SELECT * FROM `work_comments` where form_id='".$searchData->form_id."' and id=(select max(id) from work_comments where form_id='".$searchData->form_id."')") or die(mysql_error());
									$dattas = mysql_fetch_object($comment_data);
									if(mysql_num_rows($comment_data)>0)
									{
										echo $dattas->work_comments;
										if(!empty($dattas->work_comments_area))
										{
											echo '<a class="work_comment_title tooltip animate" data-tool="'.$dattas->work_comments_area.'" href="">See Notes</a>';
										}
									}
									else
									{
										echo "Work Comment is not Updated Yet";
									}
								?>
							</div>
							<div class="status_16">
								<?php 
									$sqll = mysql_query("SELECT * FROM `status_update` where form_id='".$searchData->form_id."' and id=(select max(id) from status_update WHERE form_id='".$searchData->form_id."')") or die(mysql_error());
									$datta = mysql_fetch_object($sqll);
									if(mysql_num_rows($sqll)>0)
									{
										echo $datta->status_messages;
										if(!empty($datta->status_notes))
										{
											echo '<a class="work_comment_title tooltip animate" data-tool="'.$datta->status_notes.'" href="">See Notes</a>';
										}
									}
									else
									{
										echo "Status is not Updated Yet";
									}
								?>
							</div>
							<div class="action_16">
								<div class="verified">
									<?php
										if($case_situa==0)
										{
											$casetypeDetails = $searchData->case_type;
											switch($casetypeDetails)
											{
												case 1:
												echo '<a href="ortho-case/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
												
												case 2:
												echo '<a href="mesh-case/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
												
												case 3:
												echo '<a href="pain-management-case/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
												
												case 4:
												echo '<a href="general-surgery-case/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
												
												case 5:
												echo '<a href="neurology-case/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
												
												case 6:
												echo '<a href="medical-release/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
												break;
											}
										}
										elseif($case_situa==1)
										{
											echo '<a href="new-cases/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
										}
										elseif($case_situa==2)
										{
											echo '<a href="closed-cases/index.php?fid='.$searchData->form_id.'&uid='.$searchData->id.'&cid='.$searchData->case_type.'" onclick="">View</a>';
										}
									?>
									
								</div>
							&nbsp;
							</div>

						</div>
<?php				
$i++;
					}
				}
				else
				{
					echo "No Matches Found with the Records. Please Change the Search Keywords.";
				}
			}
		?>
			<script>
				 $(document).ready(function() 
				 {
					 $(".view").load("includes/lastest-messages-home-page.php");
				   var refreshId = setInterval(function() {
					  $(".view").load("includes/lastest-messages-home-page.php");
				   }, 80000);
				   $.ajaxSetup({ cache: false });
				});
			</script>
			<?php //include('includes/lastest-messages-home-page.php'); ?>
			<div class="view"></div>
	</div>
</section>
<?php 
}
else 
{ 
header('Location:../login.php');
}
?>

</div>
	</div>
</section>
<?php

require($get_footer);
?>
