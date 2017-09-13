<style>
.input_textbox{ width:350px;}
</style>
<div class="main_admin_dashboard">
<?php echo $this->Form->create('Cmspage', array('id' => 'frm_cmspage', 'name'=>'frm_cmspage', 'enctype' => 'multipart/form-data')); ?>
<table cellpadding="5" cellspacing="5" border="0" width="100%">     
	<?php if($id==1){ ?>
	<tr>
  		<td colspan="3"></td>
      <td>
			<?php echo $this->Html->link('Upload Images', array('controller'=>'CmsImages','action' => 'add',$id), array('style' => 'padding-left:80%'));?>
      </td>
  </tr>
<?php } ?>
  <tr>
    <td width="1" style="padding-left:1%;" class="star_color" valign="top">*</td>
    <td width="200" valign="top" class="bold_font">Title</td>
    <td width="20" valign="top" class="bold_font">:</td>
    <td>
	 	<?php echo $this->Form->input('title',array('class' => 'input_textbox','label' => false)); ?>
    	<?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
    </td>
  </tr>
  <tr>
    <td width="1" style="padding-left:1%;" class="star_color" valign="top"></td>
    <td width="200" valign="top" class="bold_font">Image</td>
    <td width="20" valign="top" class="bold_font">:</td>
    <td>
	 	 <?php echo $this->Form->input('image',array('type'=>'file','class' => 'input_textbox','label' => false));?>
    </td>
  </tr>
    <?php if($this->request->data['Cmspage']['image']!=''){?>
  <tr>
  <td colspan="3"></td>
    <td><div style="float:left;"><?php echo $this->Html->image("admin_uploads/cms_uploads/".$this->request->data['Cmspage']['image'], array('border' =>'0','width'=>360,'height'=>210))?></div>
    </td>
  </tr>  
  <? }?>	  

    <tr>
    <td valign="top" colspan="3"></td>
    <td valign="top">
    		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
          	<td width="80"><?php echo $this->Form->input('Submit',array('class'=>'new_savebtn','type'=>'submit','label' => false));?></td>
            <td>
            <div class="before_cancel">
						<?php 
						  echo $this->Html->link($this->Html->div(null,'Cancel',array('class'=>'cancel')),array('action' => 'index'), array('escape'=>false)); ?>
            </div>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <?php if($id==1){?>
   <?php foreach($cms_image as $cmsimages){ ?>
    <tr>
    <td colspan="3"></td>
    <td><div style="float:left;"><?php echo $this->Html->image("admin_uploads/cms_uploads/".$cmsimages['CmsImage']['image'], array('border' =>'0','width'=>310,'height'=>130))?></div>
    	  <div style="float:left;padding-left:3%">
		  <?php echo $this->Html->link(
     				$this->Html->image("delete.png", array('border' =>'0','style'=>'padding-left:3%')),
						array('controller'=>'CmsImages','action'=>'delete',$id,$cmsimages['CmsImage']['id'],),
    				array('escape' => false),
						"Are you sure you wish to delete this image?"
  			 );	
		     ?>
        </div>
    </td>
  </tr>  	
   <?php } ?>   
 <?php } ?>
</table>      
<?php echo $this->Form->end();?>
</div>