<?php
session_start();

require_once('../../includes/functions.php');
$path = $_SERVER['DOCUMENT_ROOT']."/path.php";
require_once($path);
include($vpndb);

include '../functions.php';

include 'class.php';
?><style type="text/css">
          
          

        </style>
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
        <script type="text/javascript">
            $(document).ready(function(){
                function loading_show(){
                    $('#loading').html("<img src='images/loading.gif'/>").fadeIn('fast');
                }
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }                
                function loadData(page){
                    loading_show();                    
                    $.ajax
                    ({
                        type: "POST",
                        url: "function-pagination.php",
                        data: "page="+page,
                        success: function(msg)
                        {
                            $("#container").ajaxComplete(function(event, request, settings)
                            {
                                loading_hide();
                                $("#container").html(msg);
                            });
                        }
                    });
                }
                loadData(1);  // For first time page load default results
                $('#container .pagination li.active').live('click',function(){
                    var page = $(this).attr('p');
                    loadData(page);
                    
                });           
                $('#go_btn').live('click',function(){
                    var page = parseInt($('.goto').val());
                    var no_of_pages = parseInt($('.total').attr('a'));
                    if(page != 0 && page <= no_of_pages){
                        loadData(page);
                    }else{
                        alert('Enter a PAGE between 1 and '+no_of_pages);
                        $('.goto').val("").focus();
                        return false;
                    }
                    
                });
            });
        </script>
<?php
if(loggedin())
{
	include('header.php');
?>
<link rel="stylesheet" href="admin-style.css" type="text/css">
<section class="row">
	<div class="container">
		<div class="form_section_content">
			<h1 class="add_user">Logs</h1>
			<div class="view_log_details">
				<div class="log_heading">
					<div class="serial_nol">S.No.</div>
					<div class="user_namel">User Name</div>
					<div class="user_namel">Action</div>
					<div class="user_namel">IP</div>
					<div class="user_namel">Virtual IP</div>
					<div class="user_namel">Protocole</div>
					<div class="user_namel">Duration</div>
					<div class="user_namel">Date</div>
					<div class="user_namel">Recr</div>
					<div class="user_namel">Sent</div>
				</div>
			</div>
		</div>
		
         <div id="loading"></div>
        <div id="container">
            <div class="data"></div>
            <div class="pagination"></div>
        </div>
	</div>
</section>

</div>
	</div>
</section>

<?php
include($get_footer);
}
else
{
header('Location:../login.php');
}
?>
