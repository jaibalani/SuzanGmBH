<?php echo $this->element('admin/OuterboxStart');?>			
<?php echo $this->Form->create('Language', array('id' => 'frm_language', 'name'=>'frm_language')); ?>
<table cellpadding="5" cellspacing="5" border="0" width="100%">     
	
  
  <tr>
    <td style="padding-left:1%;" valign="top" class="star_color">*</td>
    <td valign="top" class="bold_font" >Content</td>
    
  </tr>
  
  <tr>
    <td width="1" style="padding-left:1%;" class="star_color" valign="top"></td>
    <td valign="top"  class="bold_font">
    <?php
			echo $this->Form->input('Locale.content', array(
				'label' => false,
				'value' => $content,
				'type' => 'textarea',
				'class' => 'textarea',
				'style'	=>	'width:875px; height:400px;'
			));
		?>
        
    </td>
    
  </tr> 
  
 
  
  <tr>
    <td valign="top" colspan="1"></td>
    <td valign="top" align="left" >
    		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
          	<td width="80"><?php echo $this->Form->input('Submit',array('class'=>'new_savebtn','type'=>'submit','label' => false));?></td>
            <td><div class="before_cancel">
						<?php 
						  echo $this->Html->link($this->Html->div(null,'Cancel',array('class'=>'cancel')),array('action' => 'index'), array('escape'=>false)); ?>
            </div></td>
          </tr>
        </table>
    </td>
  </tr>
  
</table>      

<?php echo $this->Form->end();?> 
 
<?php	echo $this->element('admin/OuterboxEnd');?>