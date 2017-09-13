
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
width:100% !important;
}
.faq_content{ width: 1000px !important; }
</style>

  <div class="right-part right-panel full-width">
    <!--Compose Start-->
    <div class="sb-page-title full-width">
        <strong>FAQ'<small>s</small></strong>
    </div>
    <div id="faqs-content" class="full-width">
        <div class="left-part faq-left-panel">
			<?php foreach($faqData as $key=>$val){
                    $class = "faqs-icon";
                    $style = "";
                    $sign = '&#43;';
                    if($key==0)
					          {
                        $class= "faqs-icon faqs-icon-active";
                        $sign = '&ndash;';
                    }
                ?> 
            <div class="faq-items">
                <div class="<?php echo $class;?>"><?php echo $sign;?></div>
                <div class="faq-title">
         			<?php echo ucwords($val['FaqsLanguage'][0]['fl_title']) ;?>
                </div>
                <div class="faq-details">
                    <?php echo ucfirst($val['FaqsLanguage'][0]['fl_desc']);?>
                </div>
            </div>
		<?php } ?>
      </div>
        
        <!--<div class="right-part faq-right-panel">
            
            <div id="panel-subject">
                Subject
            </div> 
            <div class="multi-form-opt">
                <ul>
                    <li>Account Settings  <div class="right-part">1</div></li> 
                    <li class="active-opt1">Billing & Payment <div class="right-part">2</div></li> 
                    <li>Copyrights & Legal <div class="right-part">3</div></li> 
                    <li>What technology to be used <div class="right-part">44</div></li> 
                    <li>How to started <div class="right-part">5</div></li> 
                    <li>What technology to e used <div class="right-part">6</div></li> 
                </ul>
            </div>
        </div>-->
    </div>
    <!--Compose End-->
  </div>
<?php } else { ?>
<style type="text/css">
  #footer-section{
    margin-top: 0px !important;
  }
  .mainContent p{
    font-size: 12px;
  }
  .faq_text3{
    font-size: 12px;
  }
</style>
<div id="right_panel" style="width:100%;margin-bottom:0px;">
 <div class="head_bg">
	<div class="head_center">
        <div class='container'>
          <div class='head-Text' style="float:left;">
              <?php echo $title;?>
          </div>
        </div>
	</div>
 </div>
  <?php if(isset($faqData) && !empty($faqData)){?> 
	    <div class='mainContent'>
        <div class="container">
          <div class="row" style="width:100% !important;">
	          <div class="col-lg-12" style="width:100% !important;">
				<div class="faq_contenter" style="width:100% !important;">
					<?php foreach($faqData as $key=>$val){
										$class = "faq_text3";
										$style = "width:100% !important;";
										if($key==0){
											$class.= " faq_text2";
											$style = 'style="display:block;width:100% !important;"';
										}
									?> 
          			<div class="<?php echo $class;?>" style="width:100% !important;">
                     <div class="faq_arrow_img2"></div>
				         			<?php echo ucwords($val['FaqsLanguage'][0]['fl_title']);?>
                    	<div <?php echo $style;?> class="faq_content"><?php echo ucfirst($val['FaqsLanguage'][0]['fl_desc']);?></div>
                  </div>
               <?php }?>
              </div><!--faq_contenter-->            
            </div><!--col-lg-12-->
          </div><!--row-->
        </div><!--container-->
      </div><!--mainContent-->
    <?php }?>
  </div>    
<?php }?>

<script >
  $(window).load(function(){
    $('#main_container').css('display','block');
  });
	$(document).ready(function(){
    $('.container').css('width','1000px');
		setTimeout(function(){$('.active-marker2 li:first').addClass('active-marker')}),300;
		$('.faq_text3').click(function(){
		 	if($(this).children(':last').css('display')=='none'){	
				$('.faq_text3').removeClass('faq_text2');
				$('.faq_arrow_img').removeClass('faq_arrow_img2');
				$('.faq_content').slideUp('slow');
				$(this).addClass('faq_text2');
				$(this).children(':last').slideDown('slow');
				$(this).children(':first').addClass('faq_arrow_img2');
			}		
		})
	})
</script>