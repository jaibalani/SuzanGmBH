<?php echo $this->element('admin/OuterboxStart');?>

	<table cellpadding="5" cellspacing="5" border="0" width="100%"> 
  	  <tr>      
         <td width="130" valign="top" class="bold_font">Id</td>
         <td width="20" valign="top" class="bold_font">:</td>
         <td><?php echo $language['Language']['id']; ?></td>
       </tr>  
       
       <tr>      
         <td valign="top" class="bold_font">Title</td>
         <td valign="top" class="bold_font">:</td>
         <td valign="top"><?php echo $language['Language']['title'];?></td>
       </tr> 
       
       <tr>      
         <td valign="top" class="bold_font">Native</td>
         <td valign="top" class="bold_font">:</td>
         <td valign="top"><?php echo $language['Language']['native']; ?></td>
       </tr> 
       
       <tr>      
         <td valign="top" class="bold_font">Alias</td>
         <td valign="top" class="bold_font">:</td>
         <td valign="top"><?php echo $language['Language']['alias'];?></td>
       </tr> 
       
       <tr>      
         <td valign="top" class="bold_font">Status</td>
         <td valign="top" class="bold_font">:</td>
         <td valign="top">
					 <?php if($language['Language']['status']==1){
             
                    echo "Enable";
                    
                 }else{
                   
                    echo "Disable";
                    
                 } 
					?>
         </td>
       </tr> 
   </table>

 <?php echo $this->element('admin/OuterboxEnd');?>     
 
 