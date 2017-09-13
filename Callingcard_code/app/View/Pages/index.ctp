<?php ?>
<?php if($this->Session->read('Auth.User.id')) {?>
	<style type="text/css">
 .mainContent{
	width:100% !important; 
	padding-top:0px !important;
	}
	.front_main{
		 width:1170px !important;
		 padding-top:30px !important;
	 }
  .container{
    	 width:1000px !important;
  	}
    </style>

    <div class="right-part right-panel">
    	<!--Page Title Start-->
    	<div class="sb-page-title">
        	<strong><?php echo $content['CmsLanguage']['title'];?></strong>
        </div>
        <!--Page Title End-->
        
        <div class="title_dashboard">
        </div>
       
        <!--Welcome Start-->
        <div id="welcome_msg" class=" spacer-12">
            <?php echo $content['CmsLanguage']['content'];?>
        </div>
        <!--Welcome End-->
    </div>
<?php } else { ?>
<style type="text/css">
  #footer-section{
    margin-top: 0px !important;
  }
  .mainContent p{
    font-size: 12px;
  }

</style>
<div id="right_panel" style="width:100% !important; margin-bottom:0px;">
    <div class="head_bg">
        <div class="head_center">
        <div class='container'>
           <div class='head-Text' style="float:left;">
                <?php echo $content['CmsLanguage']['title'];?>
           </div>
        </div>
        </div>
    </div>
	<div class='mainContent'>

	<div class="container">
	   <div class="row" style="width:100% !important;">
    	  <div class="col-lg-12" style="width:100% !important;">
          <?php foreach($cms_image as $rec){?>
              <?php 
                   echo $this->Html->image("admin_uploads/cms_uploads/".$rec['CmsImage']['image'], 
											array('border' =>'0','width'=>'1000')); 
                        }
                ?>
 			<?php echo $content['CmsLanguage']['content'];?>
          </div>
        </div>
      </div>
   </div>
 </div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
      $('.container').css('width','1000px');
});
$(window).load(function(){
    $('#main_container').css('display','block');
  });
</script>