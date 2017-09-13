<!--header which shows language select box and will post the form on change of language-->
<?php echo $this->Form->create($form_name, array('url' => array('controller'=>$controller_name,'action'=>'checklanguage','admin'=>true),'id' => 'frm_'.$form_name, 'name'=>'frm_'.$form_name)); ?>
<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-7">&nbsp;</div>
	  <div class="col-md-3">
    		<?php echo $this->Form->input($lan_name,array('required'=>true,'type'=>'select','options'=>$lang_list,'label'=>false,'class'=>'form-control select_lang','selected'=>$language_alias));?>
        <?php echo $this->Form->input($ids_name,array('type'=>'hidden','class' => 'form-control','label' => false,'value'=>$ids_val)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
	
	<div class="clear10"></div>
  
 
<?php echo $this->Form->end();?>  

<script  type="text/javascript">
	$('.select_lang').change(
		function(){
     $('#frm_<?php echo $form_name;?>').submit();
	});
</script>