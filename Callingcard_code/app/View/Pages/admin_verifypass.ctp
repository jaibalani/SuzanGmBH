
<br>
<div class="container" align="left">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default panel-primary">
			  	<div class="panel-heading">
			    	<h3 class="panel-title"><?php echo $pageTitle; ?></h3>
			 	</div>
			  	<div class="panel-body">
			    	<?php echo $this->Form->create('User');?>
                    <fieldset>
			    	  <div class="form-group input-group">
			    	  		<span class="input-group-addon">P&nbsp;</span>
			    			<?php 
								echo $this->Form->input('password',array('label' => false, 'required' => 'required', 'class' => 'form-control','placeholder'=>'Password')); 
								echo $this->Form->input('id',array('label' => false, 'required' => 'required', 'type' => 'hidden','value'=>$id)); 
								?>
			    		</div>
              
              <div class="form-group input-group">
			    	  		<span class="input-group-addon">P&nbsp;</span>
			    			<?php echo $this->Form->input('confirm_password',array('label' => false, 'required' => 'required', 'class' => 'form-control','placeholder'=>'Confirm Password','type'=>'password')); ?>
			    		</div>
              
			    	<div class="row">
							<div class="col-md-3">
							<?php echo $this->Form->input('Update', array('type'=>'submit', 'class'=>'btn btn-md btn-primary','label'=>false)); ?>
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
