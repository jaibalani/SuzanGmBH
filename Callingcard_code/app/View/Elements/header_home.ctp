<?php ?>
<!--Header Part Start-->		
	<div id="home-header-section">
        <div id="home-header-content" align="center">
		
			<div id="home-header">
				
				<!--Float_cleaner used to clean floating effect on div's-->
				<!--<div class="float_cleaner"></div>-->
				
				<!--Website Logo start-->
				<div id="home-logo">
                  <a href="<?php echo Router::url(array('controller'=>'Pages', 'action'=>'dashboard')); ?>">
				 	<?php echo $this->Html->image("logo.png",array('style'=>'cursor:pointer;')); ?> 
                 </a>
                  </div>
				<!--Website Logo end-->
				
				<!--Language Converter Start-->
				<div id="home-languages"> 
					
					<p>  <?php 

					        echo __('Language:')?> <?php echo $this->Html->image(IMAGE_PATH.'img/admin_uploads/flags/'.$languages_flag_default['en'],array('onclick'=>'change_language("en")','style'=>'cursor:pointer;','width'=>'30','height'=>'20','title'=>__('English'))); ?><?php echo $this->Html->image(IMAGE_PATH.'img/admin_uploads/flags/'.$languages_flag_default['deu'],array('onclick'=>'change_language("deu")','style'=>'cursor:pointer;','width'=>'30','height'=>'20','title'=>__('German'))); ?></p>
	
				</div>
				<!--Language Converter End-->

				<!--<div class="float_cleaner"></div>-->	
		
			</div>					
		
		</div>
    </div>
	<!--Header Part End-->

<script type="text/javascript">

function change_language(language_code)
{
			  $.ajax({
			    beforeSend: function (XMLHttpRequest) {
					 $("#loading-image").fadeIn();
				},
				complete: function (XMLHttpRequest, textStatus) {
					$("#loading-image").fadeOut();
				},
				dataType: "html",
				type: "POST",
				evalScripts: true,
				url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'change_language','admin'=>false));?>",
				data: ({language_code:language_code}),
				success: function (data)
				{
					 location.reload();
 				}
				});
}
</script>