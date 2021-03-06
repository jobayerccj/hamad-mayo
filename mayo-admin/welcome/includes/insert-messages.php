<?php
define('SCRIPT_DEBUG', true);
	ini_set('display_errors',true);  
	error_reporting(E_ALL);
	error_reporting(E_ALL & ~E_NOTICE);
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	error_reporting(-1);
	ini_set('error_reporting', E_ALL);
	if(!isset($_SESSION))
	{
		session_start();
	}
	
	$path = $_SERVER['DOCUMENT_ROOT']."/path.php";
	require_once($path);
	
	include($config);
	
	$functions  = $_SERVER['DOCUMENT_ROOT']."/classes/functions.php";
	include($functions);
	
	$meshedfile = $_SERVER['DOCUMENT_ROOT']."/attorney/classes/meshed.php";
	require_once($meshedfile);
	
	$getdata    = new Meshed();
	
	$admin      = $_SESSION['username'];
	$admin_id   = $getdata->GetDetailsByUsername($admin,"id");
	
	if(isset($_REQUEST['message']) && $_REQUEST['message']!='')
	{
		$insert_mes = mysql_query("INSERT INTO `message_sent` (`form_id`,`user_id`,`sent_by`,`main_user_id`,`message`,`date_message`) VALUES ('$_REQUEST[fid]','$_REQUEST[uid]','$admin_id','$_REQUEST[sent_ids]','$_REQUEST[message]',now())") or die(mysql_error());
	}
	if(isset($_REQUEST['message_reply_r']) && $_REQUEST['message_reply_r']!='')
	{
		$sendmessage = mysql_query("INSERT INTO `message_reply`(`message_sent_id`,`form_id`,`user_id`,`main_user_id`,`reply_by`,`sent_by`,`message_reply`,`date_reply`) VALUES ('$_REQUEST[message_id_r]','$_REQUEST[fid]','$_REQUEST[uid]','$_REQUEST[main_user_r]','$admin_id','$_REQUEST[sent_by_r]','$_REQUEST[message_reply_r]',now())") or die(mysql_error());
	}
	

				$temp_message = mysql_query("SELECT * FROM `message_sent` WHERE `user_id`='$_REQUEST[uid]' &&`form_id`='$_REQUEST[fid]' order by message_id desc") or die(mysql_error());
				$message = mysql_fetch_object($temp_message);
	?>
				<div>
				  <div id="news_list">
					  <div class="rep_middle">
					  <div class="rep_tit"><a>
					  <?php 
							echo "<b>".$fname   = $getdata->GetObjectById($message->sent_by,"first_name")."</b>";
							echo "<b>".$lname   = $getdata->GetObjectById($message->sent_by,"last_name")."</b><br/>";
									   $desg    = $getdata->GetObjectById($message->sent_by,"designation");
							echo "<b>".$desgna  = $getdata->GetDesgBydesId($desg)."</b><br/>";
						?>
						</a></div>
                       <div class="rep_rep_tit"><?php  $a_mess = $message->date_message;
							echo date('Y-M-d',strtotime($a_mess))."<br/>";
							echo date('h:i:s a',strtotime($a_mess));
							?>
                            </div>
					  <div class="rep">
						<?php 
							echo $tempmessages  = $message->message;
						?><br />	
					<a onclick="" href="javascript:animatedcollapse.show('jasoncomment<?php echo $message->message_id; ?>')">Reply Me</a>
				
						   </div>
					  </div>
					  <div class = "clr" ></div>
				  </div>
				 </div>
				<script type="text/javascript">
					animatedcollapse.addDiv('jason22', 'fade=1,height=150px,speed=200');animatedcollapse.addDiv('jasoncomment<?php echo $message->message_id; ?>', 'fade=1,height=150px,speed=200');
					animatedcollapse.ontoggle=function($, divobj, state){
					}
					animatedcollapse.init()
				</script>
				<div id="jasoncomment<?php echo $message->message_id; ?>"  style="width:460px; background:#f0f0f0; margin:5px; display:none; border:1px solid #cccccc;">
					<a href="javascript:animatedcollapse.hide('jasoncomment<?php echo $message->message_id; ?>')">Close</a>
					<div style="font-size:11px; margin:10px 0 0 10px; font-family:latha;font-weight:bold;">Your Message 
						<form method="post" action="" >
							<div style=" padding-top:5px;">
								<textarea rows="3" cols="50" id="message_reply_r<?php echo $message->message_id; ?>" id ="comment<?php echo $message->message_id; ?>" name="reply-comment"></textarea>
							</div>
							<input type="text" name ="message_id" id="message_id_r<?php echo $message->message_id; ?>" value ="<?php echo $message->message_id; ?>"/>
							<input type="text" name="sent_by" id="sent_by_r<?php echo $message->message_id; ?>" value="<?php echo $message->sent_by; ?>">
							<input type="text" name="main_user_id" id="main_user_r<?php echo $message->message_id; ?>" value="<?php echo $message->main_user_id; ?>">
							<input onclick="" type="submit" class="reply_of_message" name="replyb" value="Enter Your Message" style="margin-top:5px; height:20px; border:1px solid #246d00; font-size:10px; font-weight:bold;" />
						</form>
					</div>
				</div>
				<?php	
					$test = "SELECT a .* , b .* FROM  `message_reply` AS a, message_sent AS b WHERE a.message_sent_id = b.message_id && b.message_id='".$message->message_id."' && a.message_sent_id='".$message->message_id."' ";
					$data1 = mysql_query("SELECT a .* , b .* FROM  `message_reply` AS a, message_sent AS b WHERE a.message_sent_id = b.message_id && b.message_id='".$message->message_id."' && a.message_sent_id='".$message->message_id."' ") or die(mysql_error());
					if(mysql_num_rows($data1)>0)
					{
						$data = mysql_fetch_object($data1);
				?>
					<div style="">
					  <div id="news_list">
						  <div class="rep_middle" style="padding:<?php echo $i+10; ?>px; margin-left:<?php echo 5*$i; ?>px;">
							  <?php $count = count($data); ?>
								<div class="rep_tit">
									<a>
										<?php 
											echo "<b>".$fname = $getdata->GetObjectById($data->reply_by,"first_name")."</b>";
											echo "<b>".$lname = $getdata->GetObjectById($data->reply_by,"last_name")."</b><br/>";
											$desg    = $getdata->GetObjectById($data->reply_by,"designation");
											echo "<b>".$desgna  = $getdata->GetDesgBydesId($desg)."</b><br/>";
										?>
									</a>
								</div>
								<div class="rep_rep_tit">
									<?php  
										$a_mess = $data->date_reply;
										echo date('Y-M-d',strtotime($a_mess))."<br/>";
										echo date('h:i:s a',strtotime($a_mess));
									?>
								</div>
								<div class="rep">
									<?php 
										echo $tempmessages  = $data->message_reply;
									?><br />	
									<a onclick="" id="<?php echo $message->message_id; ?>" href="javascript:animatedcollapse.show('jasoncomment<?php echo $data->reply_id; ?>')">Reply Me</a>
								</div>
						  </div>
						  <div class = "clr" ></div>
					  </div>
					 </div>
					<script type="text/javascript">
						animatedcollapse.addDiv('jason22', 'fade=1,height=150px,speed=200');animatedcollapse.addDiv('jasoncomment<?php echo $data->reply_id; ?>', 'fade=1,height=150px,speed=200');
						animatedcollapse.ontoggle=function($, divobj, state){
							}
						animatedcollapse.init()
					</script>
					<div id="jasoncomment<?php echo $data->reply_id; ?>"  style="width:460px; background:#f0f0f0; margin:5px; display:none; border:1px solid #cccccc;">
						<a id="close<?php echo $message->message_id; ?>" href="javascript:animatedcollapse.hide('jasoncomment<?php echo $data->reply_id; ?>')">Close</a>
						<div style="font-size:11px; margin:10px 0 0 10px; font-family:latha;font-weight:bold;">Your Message 
							<form name="<?php echo $message->message_id; ?>" method="post" action="">
								<div style=" padding-top:5px;">
									<textarea rows="3" cols="50" id="message_reply_r<?php echo $message->message_id; ?>" name="reply-comment"></textarea>
								</div>
								<input type="text" name ="message_id" id="message_id_r<?php echo $message->message_id; ?>"  value ="<?php echo $message->message_id; ?>" />
								<input type="text" name="sent_by" id="sent_by_r<?php echo $message->message_id; ?>"  value="<?php echo $data->sent_by; ?>" />
								<input type="text" name="main_user_id" id="main_user_r<?php echo $message->message_id; ?>" value="<?php echo $data->main_user_id; ?>" />
								<input type="submit" name="replyb" id="<?php echo $message->message_id; ?>"  value="Enter Your Message" style="margin-top:5px; height:20px; border:1px solid #246d00; font-size:10px; font-weight:bold;" />
							</form>
						</div>
					</div>
				<?php } ?>
				<?php

				if(isset($_POST['message_sent_t']))
				{
					$message = $_POST['send_message_r'];
					$msgid   = $_POST['msgid'];
					foreach($msgid as $sent_to_id)
					{
						$temp_id[] = $sent_to_id;
					}
					$temp_ids  = implode(',',$temp_id);							
					$message_reply = mysql_query("INSERT INTO `message_sent` (`form_id`,`user_id`,`sent_by`,`main_user_id`,`message`,`date_message`) VALUES ('$_REQUEST[fid]','$_REQUEST[uid]','$admin_id','$temp_ids','$message',now())") or die(mysql_error());
					if($message_reply)
					{
						echo "Message Sent Successfully";
					}
				}
				if(isset($_POST['replyb']))
				{
					$smessage      = mysql_real_escape_string($_POST['reply-comment']);
					$message_id    = $_POST['message_id'];
					$sent_by       = $_POST['sent_by']; 
					$main_user_id  = $_POST['main_user_id'];
					$sendmessage = mysql_query("INSERT INTO `message_reply`(`message_sent_id`,`form_id`,`user_id`,`main_user_id`,`reply_by`,`sent_by`,`message_reply`,`date_reply`) VALUES ('$message_id','$_REQUEST[fid]','$_REQUEST[uid]','$main_user_id','$admin_id','$sent_by','$smessage',now())") or die(mysql_error());
				}
				
			?>