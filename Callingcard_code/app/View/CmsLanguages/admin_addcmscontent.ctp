<?php echo $this->Form->create('CmsLanguage', array('id' => 'frm_cmslanguage', 'name'=>'frm_cmslanguage')); ?>


<table cellpadding="5" cellspacing="5" border="0" width="100%">     
	<tr>
    <td width="140" valign="top" class="bold_font"></td>
    <td width="20" valign="top" class="bold_font"></td>
    <td>
    	<?php echo $this->Form->input('cmspage_id',array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>@$cmspage_id)); ?>
        <?php echo $this->Form->input('language_alias',array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>@$language_alias)); ?>
    </td>
  </tr>
	
    <tr>
    <td valign="top" class="bold_font"><span class="star_color">*</span>&nbsp;Title</td>
    <td valign="top" class="bold_font">:</td>
    <td><?php echo $this->Form->input('title',array('type'=>'text','class' => 'input_textbox','label' => false,'required' => true));?></td>
    </tr>
  
  <tr>
  <td valign="top" class="bold_font"><span class="star_color">*</span>&nbsp;Content</td>
  <td valign="top" class="bold_font">:</td>
    <td><?php echo $this->Form->input('content',array('class' => 'input_textbox','label' => false,'required' => false));?></td>
  </tr>

  <tr>
  	<td colspan="2"></td>
    <td valign="top">
    		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
          	<td width="80"><?php echo $this->Form->input('Submit',array('class'=>'new_savebtn','type'=>'submit','label' => false));?></td>
            <td>
	       <div class="before_cancel">
		      <button class="cancel btn btn-warning" type="button" >Cancel</button>
            </div>
            </td>
          </tr>
        </table>
    </td>
  </tr>       
</table>      
 
<?php echo $this->Form->end();?>  
<script type="text/javascript">
	var editor =CKEDITOR.replace('CmsLanguageContent', {height:200,width:700,toolbar:'MyToolbar'});
	
	    $('.cancel').click(function(){
	      history.go(-1);
   });

</script>

