<?php
ob_start();
ini_set('display_errors',1);  
error_reporting(E_ALL);
$path = $_SERVER['DOCUMENT_ROOT']."/rao/path.php";
require_once($path);
include($config);
include '../../functions.php';
$classfile = $_SERVER['DOCUMENT_ROOT']."/rao/classes/functions.php";
include($classfile);
include('../header.php');

if(loggedin())
{
	$desg  = new Allfunctions();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<section class="row">		
	<div class="container">
		<div class="form_section_content">
			<?php
			if(isset($_POST['add-role']))
			{
				$data      = array('cpt_code'=>$_POST['cpt'],'description'=>$_POST['description'],'loc'=>$_POST['loc'],'ortho_group'=>$_POST['ortho_group'],'phoenix'=>$_POST['phoenix'],'matthews'=>$_POST['mathews']);
				$tablename = "cpt_codes";
				$desg->INSERT($data,$tablename);
				if($desg)
				{
					echo "CPT Code Added Successfully";
				}
			}
			?>
		</div>
		<div class="form_section_content">
		<?php 
			if(isset($_REQUEST['cptid']) && $_REQUEST['action'] == "edit")
			{
				$temp_getcpt = mysql_query("SELECT * FROM `cpt_codes` WHERE `id`='$_REQUEST[cptid]'") or die(mysql_error());
				$getcpt      = mysql_fetch_object($temp_getcpt);
		?>
				<h1 class="add_user">Update CPT Codes</h1>
				<form name="updatecpt" method="post" action="" id="regform">
						<ul>
							<li>
								<span class="form_label">	
									<label>CPT Code</label>
								</span>
								<span class="form_input">
									<input type="text" name="cpt" value="<?php echo $getcpt->cpt_code; ?>" required />
									<span class="error"></span>
								</span>
							</li>
						   <li>
							   <span class="form_label">
									<label>Description</label>
								</span>
								<span class="form_input">
									<textarea name="description" rows="10" cols="50"><?php  echo $getcpt->description;?></textarea>
									<span class="error"></span>
								</span>
						   </li>
						   <li>
								<span class="form_label">	
									<label>Loc</label>
								</span>
								<span class="form_input">
									<input type="text" name="loc_u" value="<?php echo $getcpt->loc; ?>" required />
									<span class="error"></span>
								</span>
							</li>
							<li>
								<span class="form_label">	
									<label>Ortho Group</label>
								</span>
								<span class="form_input">
									<input type="text" name="orthogroup_u" value="<?php echo $getcpt->ortho_group; ?>" required />
									<span class="error"></span>
								</span>
							</li>
							<li>
								<span class="form_label">	
									<label>Phoenix</label>
								</span>
								<span class="form_input">
									<input type="text" name="phoenix_u" value="<?php echo $getcpt->phoenix; ?>" required />
									<span class="error"></span>
								</span>
							</li>
							<li>
								<span class="form_label">	
									<label>Matthews</label>
								</span>
								<span class="form_input">
									<input type="text" name="matthews_u" value="<?php echo $getcpt->matthews; ?>" required />
									<span class="error"></span>
								</span>
							</li>
							<li>	
								<span class="form_label">&nbsp;</span>
								<input type="submit" name="update_cpt" value="Update CPT" required />
							</li>
						</ul>
				</form>	
				<?php
					if(isset($_POST['update_cpt']))
					{
						$cpt = $_POST['cpt'];
						$desc = $_POST['description'];
						$loc  = $_POST['loc_u'];
						$ortho_u = $_POST['orthogroup_u'];
						$phoenix = $_POST['phoenix_u'];
						$matthews_u = $_POST['matthews_u'];
						
						$update_temp = mysql_query("UPDATE `cpt_codes` SET `cpt_code`='$cpt',`description`='$desc',`loc`='$loc',`ortho_group`='$ortho_u',`phoenix`='$phoenix',`matthews`='$matthews_u' WHERE `id`='$_REQUEST[cptid]'") or die(mysql_error());
						if($update_temp)
						{
							echo "CPT Updated Successfully";
							header("refresh:2; url=insert-cpt-code.php");
						}
						else
						{
							echo "There is Error. Some thing going Wrong. Please try again Later";
						}
						
					}
				?>
				<?php
					}
					else
					{
				?>
					<h1 class="add_user">Add CPT Codes</h1>
					<form name="userinfo" method="post" action="" id="regform">
						<ul>
							<li>
								<span class="form_label">	
									<label>Name</label>
								</span>
								<span class="form_input">
									<input type="text" name="cpt" required />
									<span class="error"></span>
								</span>
							</li>
						  
						   <li>
							   <span class="form_label">
									<label>Email Format</label>
								</span>
								<span class="form_input">
									<textarea name="description" rows="10" cols="50" required ></textarea>
									<span class="error"></span>
								</span>
						   </li>
							<span class="form_label">&nbsp;</span>
								<input type="submit" name="add-role" value="Add CPT Code" required />
							</li>
							</ul>
						
					</form>	
			
			<div class="view_log_details">
				<div class="log_heading">
					<div class="serial_no">CPT Code</div>
					<div class="user_nameee">Description</div>
					<div class="user_name">Edit</div>
					<div class="user_name">Delete</div>
				</div>
				<?php 
					$query =mysql_query("SELECT * FROM `cpt_codes`") or die(mysql_error());
					$ij=1;
					while ($row = mysql_fetch_array($query)) 
					{
				?>
					<div class="log_row">
						<div class="serial_no"><?php  echo $row['cpt_code']; ?></div>
						<div class="user_nameee"><?php  echo $row['description'];?></div>
						<div class="user_name"><a href="insert-cpt-code.php?cptid=<?php echo $row['id']; ?>&action=edit">Edit</a></div>
						<div class="user_name">
							<a class="confirmation" href="insert-cpt-code.php?cptid=<?php echo $row['id']; ?>&action=delete">Delete</a>
						</div>
					</div>
					<script type="text/javascript">
						$('.confirmation').on('click', function () {
							return confirm('Are you sure?');
						});
					</script>
				<?php 
					} 
				}
				?>
				<?php
					if(isset($_REQUEST['cptid']) && $_REQUEST['action']=="delete")
					{
						$delete = mysql_query("DELETE FROM `cpt_codes` WHERE `id`='$_REQUEST[cptid]'") or die(mysql_error());
						if($delete)
						{
							echo "Record Deleted Successfully";
							header("refresh:1;url=insert-cpt-code.php");
						}
						else
						{
							echo "There is error. Please try it later";
						}
					}
				?>
				
			</div>
</div>

<?php 
}
else 
{ 
header('Location:../login.php');
}
?>
<script src="http://<?php echo $jqueryminjs; ?>"></script>
<script src="http://<?php echo $validateminjs; ?>"></script>
</div>
</section>
<?php
require($get_footer);
?>
