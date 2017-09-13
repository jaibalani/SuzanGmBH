 <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
      		     <a class="navbar-brand" href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'dashboard'));?>">
	            		<?php echo $this->Html->image("logo.png"); ?>
               </a>
				    </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a>
                        <?php echo __("Language: "); ?>
                        </a>
                    </li>
                    <li>
                        <a onclick = "change_language('en')" href="javascript:void(0)" title="<?php echo __('English Language');?>"><?php echo $this->Html->image("english.png") ?></a>
                    </li>
                    <li>
                        <a onclick = "change_language('deu')" href="javascript:void(0)"  title="<?php echo __('German Language');?>"><?php echo $this->Html->image("other.png") ?></a>
                    </li>
                    <?php if($login_user) {?> 
                     <li>
                  	    <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout'));?>">
                        <?php echo __("Logout"); ?>
                        </a>
                    </li>
                   <?php } ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>


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