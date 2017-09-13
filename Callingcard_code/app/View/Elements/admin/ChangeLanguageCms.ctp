<?php echo $this->Form->create('CmsPages', array('Controller'=>'CmsPages','action'=>'checklanguage','admin'=>true,'id' => 'frm_cmspage', 'name'=>'frm_cmspage')); ?>
<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-7">&nbsp;</div>
	  <div class="col-md-3">
    		<?php echo $this->Form->input('language_alias',array('required'=>true,'type'=>'select','options'=>$lang_list,'label'=>false,'class'=>'form-control','selected'=>$language_alias));?>
        <?php echo $this->Form->input('cmspage_id',array('type'=>'hidden','class' => 'form-control','label' => false,'value'=>$cmspage_id)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
	</div>
	
	<div class="clear10"></div>
  
 
<?php echo $this->Form->end();?>  

<script  type="text/javascript">
	$('#CmsPagesLanguageAlias').change(
		function(){
     $('#frm_cmspage').submit();
	});
</script>