<div class="row">
  <div class="col-md-12">
    <h1><?php echo $title_for_layout; ?></h1>
  </div>
</div>
<?php echo $this->Form->create('Language', array('name'=>'frm_language', 'type'=>'file')); ?>
	<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3"><?php echo __('Title')?>:</div>
	  <div class="col-md-7">
    		<?php echo $this->Form->input('title',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
	  </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
  
  <div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3"><?php echo __('Language')?>:</div>
	  <div class="col-md-7">
    		<?php echo $this->Form->input('locale', array('type' => 'select','class' => 'form-control', 'options' => $language_list, 'empty' => 'Select Language', 'label' => false, 'div' => false));?>
	  </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
  
  <div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3"><?php echo __('Native')?>:</div>
	  <div class="col-md-7">
    		<?php echo $this->Form->input('native',array('class' => 'form-control','label' => false,'class' => 'form-control', 'div' => false));?>
	  </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
  
  <div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3"><?php echo __('Flag')?>:</div>
	  <div class="col-md-7">
    		<?php echo $this->Form->input('language_flag', array('label' => false, 'type' => 'file')); ?>
	  </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
  
  <div class="clear10"></div>
 	<div class="row">
	  <div class="col-md-3">&nbsp;</div>
	  <div class="col-md-3"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
   <?php 
						   echo $this->Html->link($this->Html->div(null,'Cancel',array('class'=>'cancel btn btn-warning')),array('action' => 'index'), array('escape'=>false)); ?>
    </div> 
	</div>	
		
	<div class="clear10"></div>
<?php echo $this->Form->end();?> 