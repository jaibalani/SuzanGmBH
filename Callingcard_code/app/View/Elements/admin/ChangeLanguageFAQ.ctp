<?php echo $this->Form->create('Faq', array('Controller'=>'Faq','action'=>'checklanguage','admin'=>true,'id' => 'frm_faq', 'name'=>'frm_faq')); ?>
<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-7">&nbsp;</div>
	  <div class="col-md-3">
    		<?php echo $this->Form->input('fl_alias',array('required'=>true,'type'=>'select','options'=>$lang_list,'label'=>false,'class'=>'form-control','selected'=>$language_alias));?>
        <?php echo $this->Form->input('fl_f_id',array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>$fl_f_id)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
	
	<div class="clear10"></div>
<?php echo $this->Form->end();?>  

<script  type="text/javascript">
	$('#FaqFlAlias').change(
		function(){
     $('#frm_faq').submit();
	});
</script>