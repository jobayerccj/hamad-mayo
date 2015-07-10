<?php 
session_start();
require('header.php');
$path = $_SERVER['DOCUMENT_ROOT']."/path.php";
require_once($path);
require_once($config);
include('classes/login.php');
$checklogin = new Login();
?>
<script src="https://<?php echo $jqueryminjs; ?>"></script>
<script src="https://<?php echo $validateminjs; ?>"></script>
<script>
$(document).ready(function(){
	$('#logind').click(function(){
			setTimeout(function(){ $('.messages').fadeOut('slow'); }, 5000);
		});
	jQuery.validator.addMethod("noSpace", function(value, element)
    	{ return value.indexOf(" ") < 0; }, "No space in Password");
    	$.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z ]*$/);
 });
    $("#regform").validate({
    
        // Specify the validation rules
        rules: {
            uname: {
				required:true
				},
            upassword: {
                required: true
            }
	
        },
        
        // Specify the validation error messages
        errorElement: "span",
        messages: {
            uname: {
				required: "Please enter username",
			},
            password: {
                required: "Please Enter password"
            }
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
</script>
<?php					
	if(isset($_POST['logins']))
	{
		$username = mysql_real_escape_string($_POST['username']);
		$password = mysql_real_escape_string($_POST['password']);
		$remember = $_POST['remember'];
		$getuser  = $checklogin->CheckLogin($username,$password,$remember);
	}
?>
<section class="row">
	<div class="container">
		<div class="login_section_content">
			<div class="mayo_login">
				<div class="login_right_inner">
					<h1>Login to Mayo Surgical</h1>
					<form  action="" method="post" name="form-login" id="regform">
						<input required placeholder="Username" type="text" name="username"/>
						<span class="error"></span>
						<input required placeholder="Password" type="password" name="password"/>
						<span class="error"></span>
						<?php
							if(isset($getuser))
							{
								echo "<span class='login_error'>".$getuser."</span>";
							}
						?>
						<input type="submit" name="logins" id="logind" value="LOG IN"/>
						<div class="login_span_row">
							<label class="login_span_row_left" for="remember"><input type="checkbox" name="remember" value="yes"> Remember Me</label>
							<label class="forgot_password login_span_row_right"><a href="<?php echo $sitepath; ?>/forgot-password.php">Forgot Password</a></label>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</section><?php //echo $_SESSION['username'];
//echo $_COOKIE['username']; ?>
<?php
require($get_footer);
?>
