
<br>
<div class="container" align="left">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default panel-primary">
			  	<div class="panel-heading">
			    	<h3 class="panel-title"><?php echo $pageTitle; ?></h3>
			 	</div>
			  	<div class="panel-body">
			    	<?php echo $this->Form->create('User',array('onsubmit'=>'return validateForm()'));?>
                    <fieldset>
			    	  	<div class="form-group input-group">
			    	  		<span class="input-group-addon">@</span>
                  <?php echo $this->Form->input('email',array('type' => 'email', 'label' => false, 'required' => 'required', 'class' => 'form-control', 'placeholder'=>'E-mail','autofocus'=>true,'value'=>@$email)); ?>
			    		</div>
			    	<div class="row">
							<div class="col-md-3">
							<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-md btn-primary','label'=>false)); ?>
							</div>
							<div class="col-md-9" align="right" style="margin-top:5px;">
								<?php echo $this->Html->link('Admin Login', array('controller'=>'users', 'action'=>'login','admin'=>true))?>
							</div>
						</div>
						
			    	</fieldset>
			      	<?php echo $this->Form->end(); ?>			      	
			    </div>
			</div>
		</div>
	</div>
</div>
<script>
function validateForm(){
	if($.trim($('#UserEmail').val()) == ''){
            alert('Please enter email address');
            return false;
	}
	if(!$('#UserEmail').val().toLowerCase().match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/)){
			alert('Please enter valid email address');
			return false;
	}
}
</script>