<?php
$parameters = $this->request->params;
//prd($parameters);
?>
<style>
.user_header_info{
	padding-top: 12px;
	color: #fff;
	font-size: 12px;
}
.navbar-fixed-top{ text-align: center;  text-align: -webkit-center; text-align: -moz-center; text-align: -ms-center; display:inline-block}

#sb-nav-header{ width: 1350px; display:inline-block}

.navigation li li a {
    
    padding-left: 30px;
}
.navigation a {
    color: #5A5A5A;
    display: block;
    font-family: "Droid Sans",Arial,Verdana;
    font-size: 14px;
    height: 45px;
    line-height: 45px;
    padding-left: 14px;
    position: relative;
    text-decoration: none;
    margin-left: -40px;
	padding-left: 40px;
}
ol, ul {
    list-style: none outside none;
}
.navigation{
 display:none;
}

.sb_active_subopt_active{

/*background-color: #EFEFEF !important;*/
background-color: #E0E0E0 !important;
}

.navigation a:hover{

background-color: #E0E0E0 !important;
}

.sb_active_single_opt{ 
    background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important; 
    background-image: url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat;
    background-position: 200px 16px;
}

.left-panel li a.sb_active_single_opt:hover, .left-panel li a.sb_active_single_opt:focus{
background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important;    
}

.sb_active_opt{
    background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important; 
    background-image: url(<?php echo $this->webroot?>img/down_arrow.png), url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat,no-repeat;
    background-position: 175px 23px, 200px 15px;
}


.left-panel li a.sb_active_opt + ul{ 
    display: block; // uncomment for test purpose
}

.has_submenu{ background-image: url(<?php echo $this->webroot?>img/right_arrow.png);
    background-repeat: no-repeat;
    background-position: 175px 23px; }
    
.left-panel li a.has_submenu + ul{ 
    display: none; 
}

    .left-panel li a.sb_active_opt:hover, .left-panel li a.sb_active_opt:focus{ background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important;
    background-image: url(<?php echo $this->webroot?>img/down_arrow.png), url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat,no-repeat;
    background-position: 175px 23px, 200px 15px; 
}

.left-panel li li{ 
    border-bottom:0px !important;
}

.left-panel li li:first-child{ 
    border-top:0px !important;
}
.show{
    display: block;
}

.left-panel li a{ width: 210px; }

.left-panel{
padding-top: 12px;

background: rgba(255,255,255,1);
background: -moz-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -webkit-gradient(left top, right top, color-stop(0px, rgba(255,255,255,1)), color-stop(210px, rgba(255,255,255,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(15px;, rgba(242,242,242,1)));
background: -webkit-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -o-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -ms-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: linear-gradient(to right, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f2f2f2', GradientType=1 );

}
</style>   


   <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" align="center">
   	  <div id="sb-nav-header">
	     <!-- Brand and toggle get grouped for better mobile display -->
	     <div class="navbar-header">
	          <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>-->
	          <a class="navbar-brand" href="<?php echo APPLICATION_PATH?>admin"><?php echo APPLICATION_NAME?></a>
	        </div>
	
	
	        <?php if($this->Session->read('Auth.User.id')){ ?>
	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse navbar-ex1-collapse sb-user-pf-pic">
	
	          <ul class="nav navbar-nav navbar-right navbar-user">
	            
	            <li class="dropdown user-dropdown user_header_info" >
	             <?php echo __('Welcome').": <b>".ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'))."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</b>";?>
	            </li>

	            <li class="dropdown user-dropdown user_header_info" >
	              <?php echo __('Account Number').": <b>".$this->Session->read('Auth.User.username')."</b>";?>
	            </li>
	            

	            <li class="dropdown user-dropdown" >
	            <?php  
				  
				  $image = $this->Session->read('Auth.User.image');
				  if(file_exists(WWW_ROOT.'img/users/'.$image) && !empty($image))
				  {
				  	$admin_image= $this->Html->image('users/'.$this->Session->read('Auth.User.image'),
																				array('class'=>'','border'=>'0','div'=>true,'width'=>45,'style'=>'border-radius:7px;'));
				  
				  }
				  else
				  {
					 $admin_image = '<i class="icon-user"></i>';  
				  }
				 
				  ?>
				  <a href="#" class="dropdown-toggle user_icon" data-toggle="dropdown" style="padding-top:10px;height:55px;">
	                <div style="float:left;"><?php echo $admin_image?></div><?php echo $this->Session->read('Auth.User.u_name')?><b class="caret"></b></a>
	              <ul class="dropdown-menu" style="margin-left:0px;padding-left:0px;width:200px;text-align:left;">
	               <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_edit'))?>">
	                <div style="float:left;width:45px;margin-right:5px;" align="center"><?php echo $admin_image; ?></div> <?php echo __('Profile Manage');?></a></li>
                    <?php if($this->Session->read('Auth.User.distributor_login_mediator') == 1) 
							{
									$new_admin_image = $this->Session->read('Auth.User.admin_image');
									if(file_exists(WWW_ROOT.'img/users/'.$new_admin_image) && !empty($new_admin_image))
									{
									  $new_admin_image= $this->Html->image('users/'.$this->Session->read('Auth.User.admin_image'),
															array('class'=>'','border'=>'0','div'=>true,'width'=>45,'style'=>'border-radius:7px;'));
									
									}
									else
									{
										$new_admin_image = '<i class="icon-user"></i>';  
									}
					?>

                    <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'manage_mediator_redirect',$this->Session->read('Auth.User.admin_id')))?>">
                    	<div style="float:left;width:auto;width:45px;margin-right:5px;" align="center"><?php echo $new_admin_image; ?></div> <?php echo __('Distributor Account');?></a>
                    </li>
                    <?php  } ?>

	                <li class="divider"></li>
	                <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout'))?>"><i class="icon-power-off"></i> <span style="margin-left:20px;"><?php echo __('Log Out');?></span></a></li>
	              </ul>
	            </li>
	          </ul>
	        </div><!-- /.navbar-collapse -->
	        <?php } ?>
	      </div>
      </nav>

 

<script>
$(window).load(function(){

	$('.sb_active_opt').next().css('display','block');
	
	/* container height */
	window_size = parseInt($(window).height());
	container_size = parseInt($('#page-wrapper').height());
	document_size = parseInt($(document).height());
	
	if(window_size > container_size){
		$('#sb-left-panel11').css('height',(window_size - 65));	
	}else{
		$('#sb-left-panel11').css('height',(window_size - 65));
	}
	
});


$('.hidden_sub').click(function(){

	$(this).next().toggle('sliderup');

	if(!$(this).hasClass('sb_active_opt')){

		$(this).addClass('sb_active_opt').removeClass('has_submenu');
		$('.navigation li:first-child a').addClass('sb_active_subopt');
		

    }else{

         $(this).removeClass('sb_active_opt');
         $(this).addClass('has_submenu');

    }
    
});

</script>      
