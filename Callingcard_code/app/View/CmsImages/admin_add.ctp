<?php echo $this->Form->create('CmsImage', array('id' => 'frm_cmsimage', 'name'=>'frm_cmsimage', 'enctype' => 'multipart/form-data')); ?>
<table cellpadding="5" cellspacing="5" border="0" width="100%">     

	<tr>
    <td width="1" style="padding-left:1%;" class="star_color" valign="top">*</td>
    <td width="200" valign="top" class="bold_font">Image</td>
    <td width="20" valign="top" class="bold_font">:</td>
    <td>
	 	 <?php echo $this->Form->input('image',array('type'=>'file','class' => 'input_textbox','label' => false));?>
    	<?php echo $this->Form->input('cmspages_id',array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>$cmspages_id)); ?>
    </td>
  </tr>

  <tr>
    <td valign="top" colspan="3"></td>
    <td valign="top">
    		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
          	<td width="80"><?php echo $this->Form->input('Submit',array('class'=>'new_savebtn','type'=>'submit','label' => false));?></td>
            <td>
            
            <div class="before_cancel">
						<?php 
						  echo $this->Html->link($this->Html->div(null,'Cancel',array('class'=>'cancel')),array('controller'=>'CmsPages','action' => 'edit',$cmspages_id), array('escape'=>false)); ?>
            </div>
            </td>
          </tr>
        </table>
    </td>
  </tr>
       
</table>      
<?php echo $this->Form->end();?>  
