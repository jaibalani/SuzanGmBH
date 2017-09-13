<?php ?>
    <!--<title>CALLING CARD - Welcome</title>-->
    <div class="right-part right-panel">
    	<!--Page Title Start-->
    	<div class="sb-page-title">
        	<strong>Dashboard</strong>
        </div>
        <!--Page Title End-->
        
        <!-- Welcome Page _title -->
        <div class="title_dashboard">
              <?php echo $content['CmsLanguage']['title'];?>
        </div>
       
        <!--Welcome Start-->
        <div id="welcome_msg" class=" spacer-12">
            <?php echo $content['CmsLanguage']['content'];?>
        </div>
        <!--Welcome End-->
    </div>

<script type="text/javascript">
$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-dashboard').addClass('opt-selected');
});


</script>