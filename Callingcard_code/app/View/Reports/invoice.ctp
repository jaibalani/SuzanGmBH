<?php ?>
<div class="right-part right-panel">
    <!--Invoice Start-->
    <div class="sb-page-title">
        <strong>Invoice</strong>
    </div>

    <div id="date-range">
        <div id="date-panel">
            <form action="#" method="post">
            <label><?php echo __('From Date:')?></label>
            <div class="input-group">
                <div class="input-group-addon">
			 		<?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                 </div>
                 <?php echo $this->Form->input('datepicker1',array(
                                            'label'    => false, 
                                            'class'=>'form-control',
											'id'=>'datepicker1',
                                            'value'=>@$date_set_start,
											'style'=>'background-color:#FFF',
                                            'readonly' => 'readonly',
											'type'=>'text', 
											'placeholder'=>__('From Date')
											));
				?>
            </div>
            <label><?php echo __('To Date:')?></label>
            <div class="input-group">
                <div class="input-group-addon">
                    <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                </div>
			   <?php echo $this->Form->input('datepicker2',array(
                                    'label'    => false, 
                                    'class'=>'form-control',
                                    'id'=>'datepicker2',
                                    'value'=>@$date_set_end,
                                    'style'=>'background-color:#FFF',
                                    'readonly' => 'readonly', 
                                    'type'=>'text',
                                    'placeholder'=>__('To Date')
                                    ));
                  ?>
            </div>
            <?php echo $this->Form->input('GO', array('type'=>'submit', 'class'=>'button-gradient','label'=>false,'id'=>'submit_btn','div'=>false ,'onclick'=>'return check_fields();')); ?>
            </form>
        </div>

    </div>
                        <div id="invoice">

                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center">Invoice No.</th>
                                    <th>Invoice Description</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td><img src="images/download.png" /></td>
                                  </tr>
                                  <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td><img src="images/download.png" /></td>
                                  </tr>
                                  <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td><img src="images/download.png" /></td>
                                  </tr>
                                  <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td><img src="images/download.png" /></td>
                                  </tr> 
                                </tbody>
                              </table>

                        </div>
                        <!--Invoice End-->
                    </div>


<script type="text/javascript">

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-invoice').addClass('opt-selected');
});

$(function() {
	var date = new Date();
	var currentMonth = date.getMonth();
	var currentDate = date.getDate();
	var currentYear = date.getFullYear();

	
	$( "#datepicker1" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: new Date(currentYear, currentMonth, currentDate),
	});
	$( "#datepicker2" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: new Date(currentYear, currentMonth, currentDate),
	});
});

</script>