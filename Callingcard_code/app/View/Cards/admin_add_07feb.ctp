<div class="row">
    <div class="col-md-12">
        <div class="page_title"><?php echo $title; ?></div>
        <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Card Management</span><i class="icon-angle-right home_icon"></i> <span>Add Card</span></div>
    </div>
</div>

<div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
    </div>    

    <div class="clear10"></div>

    <div align="left" class="grid_table_box">
        <?php echo $this->Form->create('Card', array("enctype" => "multipart/form-data", 'onSubmit' => 'return ValidateWebPage()')); ?>
        <div class="clear10"></div>
        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Main Category') ?><sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('Card.cat_id', array(
                    'label' => false,
                    'required' => true,
                    'id' => 'cat_id',
                    'default' => '',
                    'empty' => '--- Select Category---',
                    'options' => $cat_names,
                    'selected' => (isset($parent_details['Parent']['cat_id']) ? $parent_details['Parent']['cat_id'] : 0),
                    'class' => 'form-control'
                ))
                ?>
<?php //echo $parent_details['Parent']['cat_title'];  ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>
        <div class="row">
            <div class="col-md-3 sb_left_pad">
<?php echo __('Sub Category') ?>

                <sup class="MandatoryFields">*</sup>
            </div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('Card.sub_cat_id', array(
                    'label' => false,
                    'required' => true,
                    'id' => 'sub_cat_id',
                    'default' => '',
                    'empty' => '--- Select Sub Category---',
                    'options' => $sub_cat_names,
                    'selected' => (isset($parent_details['Category']['cat_id']) ? $parent_details['Category']['cat_id'] : 0),
                    'class' => ' form-control',
                ))
                ?>
            </div>

<?php //echo $parent_details['Category']['cat_title'];  ?>
            <div class="col-md-3">&nbsp;</div>
        </div>

        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Card Name') ?><sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
<?php echo $this->Form->input('c_title', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'required' => true)); ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Buying Price') ?> &euro;<sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
<?php echo $this->Form->input('c_buying_price', array('type' => 'text', 'class' => 'form-control decimal', 'label' => false, 'div' => false, 'required' => true)); ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Selling Price') ?> &euro;<sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
<?php echo $this->Form->input('c_selling_price', array('type' => 'text', 'class' => 'form-control decimal', 'label' => false, 'div' => false, 'required' => true)); ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('German Contact Number') ?></div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('c_contact_number_1', array('class' => 'form-control contact_number',
                    'type' => 'text',
                    'required' => false,
                    'label' => false,
                    'div' => false));
                ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('English Contact Number') ?></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_contact_number_2', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => false,
    'label' => false,
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 1') ?></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_local_number_1', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => false,
    'label' => false,
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 2') ?></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_local_number_2', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => false,
    'label' => false,
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 3') ?></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_local_number_3', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => false,
    'label' => false,
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>


        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 4') ?></div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('c_local_number_4', array('class' => 'form-control contact_number',
                    'type' => 'text',
                    'required' => false,
                    'label' => false,
                    'div' => false));
                ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>


        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 5') ?></div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('c_local_number_5', array('class' => 'form-control contact_number',
                    'type' => 'text',
                    'required' => false,
                    'label' => false,
                    'div' => false));
                ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>


        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Local Contact Number - 6') ?></div>
            <div class="col-md-6 sb_left_mar">
        <?php
        echo $this->Form->input('c_local_number_6', array('class' => 'form-control contact_number',
            'type' => 'text',
            'required' => false,
            'label' => false,
            'div' => false));
        ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>
                <?php if (isset($lang_list) && !empty($lang_list)) {
                    foreach ($lang_list as $key => $val) {
                        ?>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6 sb_left_mar">Freetexts for <?php echo $val['Language']['title']; ?></div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="clear10"></div>		
                <?php
                echo $this->Form->input('CardsFreeText.cf_alias.', array('type' => 'hidden', 'value' => isset($this->request->data['CardsFreeText']['cf_alias'][$key]) ? $this->request->data['CardsFreeText']['cf_alias'][$key] : $val['Language']['alias']));

                echo $this->Form->input('CardsFreeText.cf_id.', array('type' => 'hidden', 'value' => isset($this->request->data['CardsFreeText']['cf_id'][$key]) ? $this->request->data['CardsFreeText']['cf_id'][$key] : ''));
                for ($i = 1; $i <= 6; $i++) { //create 6 free text for each language
                    ?> 
                    <div class="row">
                        <div class="col-md-3 sb_left_pad"><?php echo __('Free Text ' . $i) ?></div>
                        <div class="col-md-6 sb_left_mar">
                            <?php
                            echo $this->Form->input('CardsFreeText.cf_freetext' . $i . '.', array('class' => 'form-control',
                                'type' => 'textarea',
                                'label' => false,
                                'div' => false,
								'style'=>'height:70px;',
                                'value' => isset($this->request->data['CardsFreeText']['cf_freetext' . $i][$key]) ? $this->request->data['CardsFreeText']['cf_freetext' . $i][$key] : '',
                                'required' => false));
                            ?>
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                    </div>
                    <div class="clear10"></div>
                            <?php }
                        ?>

    <?php }
}
?>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Webpage') ?></div>
            <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Form->input('c_webpage', array('class' => 'form-control url',
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'required' => false));
                ?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Card Inventory Threshold') ?><sup class="MandatoryFields"></sup></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_inventory_threshold', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => false,
	'default'=>0,
    'label' => false,
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('PINs per card') ?><sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
<?php
echo $this->Form->input('c_pin_per_card', array('class' => 'form-control contact_number',
    'type' => 'text',
    'required' => true,
    'label' => false,
	'value'=>1,
	'readonly'=>'readonly',
    'div' => false));
?>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        <div class="clear10"></div>

        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Image') ?><sup class="MandatoryFields">*</sup></div>
            <div class="col-md-6 sb_left_mar">
        <?php echo $this->Form->input('c_image', array('type' => 'file', 'label' => false)); ?>
            </div>
            <div class="col-md-3">
                &nbsp;
            </div>
        </div>
        <div class="clear10"></div>
                <?php if (isset($this->request->data['Card']['c_image']) && !is_array($this->request->data['Card']['c_image'])) {
                    if (file_exists(WWW_ROOT . 'img/card_icons/' . $this->request->data['Card']['c_image'])) {
                        ?>
                <div class="row">
                    <div class="col-md-3 sb_left_pad">&nbsp;</div>
                    <div class="col-md-6 sb_left_mar">
                <?php
                echo $this->Html->image('card_icons/' . $this->request->data['Card']['c_image'], array('alt' => 'Card', 'class' => '', 'border' => '0', 'div' => true, 'width' => '180', 'height' => '100'));
                ?>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="clear10"></div>
    <?php }
}
?>
        <div class="row">
            <div class="col-md-3 sb_left_pad"><?php echo __('Image Back') ?><sup class="MandatoryFields"></sup></div>
            <div class="col-md-6 sb_left_mar">
<?php echo $this->Form->input('c_image_back', array('type' => 'file', 'label' => false)); ?>
            </div>
            <div class="col-md-3">
                &nbsp;
            </div>
        </div>


        <div class="clear10"></div>
<?php if (isset($this->request->data['Card']['c_image_back']) && !is_array($this->request->data['Card']['c_image_back'])) {
    if (file_exists(WWW_ROOT . 'img/card_icons/' . $this->request->data['Card']['c_image_back'])) {
        ?>
                <div class="row">
                    <div class="col-md-3 sb_left_pad">&nbsp;</div>
                    <div class="col-md-6 sb_left_mar">
        <?php
        echo $this->Html->image('card_icons/' . $this->request->data['Card']['c_image_back'], array('alt' => 'Card', 'class' => '', 'border' => '0', 'div' => true, 'width' => '180', 'height' => '100'));
        ?>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="clear10"></div>
    <?php }
}
?>
        <div class="clear10"></div>
        <div class="row">
            <div class="col-md-3 sb_left_pad"></div>
            <div class="col-md-6 sb_left_mar">
                NOTE : <b>Image size should be within minimum 110px X 70px and maximum 1760px X 2480px in 'jpg','jpeg','png' and 'gif' formats.</b>
            </div>
            <div class="col-md-3">
                &nbsp;
            </div>
        </div>


        <div class="row">
            <div class="col-md-3 sb_left_pad">&nbsp;</div>
            <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type' => 'submit', 'class' => 'btn btn-primary', 'label' => false, 'id' => 'submit_btn', 'div' => false)); ?>
<?php
echo $this->Form->button(__('Cancel'), array('type' => 'button',
    'class' => 'btn btn-warning cancel',
    'label' => false,
    'style' => 'cursor:pointer;'));
?>
            </div> 
        </div>	

        <div class="clear10"></div>

<?php echo $this->Form->end(); ?>

    </div>

</div>		

<script type="text/javascript">

	$(document).ready(function () {
		$('#cat_id').change(function () {
			$.ajax({
				beforeSend: function (XMLHttpRequest) {
					$("#loading-image").fadeIn();
				},
				complete: function (XMLHttpRequest, textStatus) {
					$("#loading-image").fadeOut();
				},
				url: "<?php echo Router::url(array('controller' => 'cards', 'action' => 'get_subcat')); ?>",
				type: "POST",
				data: ({id: $(this).val()}),
				dataType: 'json',
				success: function (json) {
					$('#sub_cat_id').html('');
					$('#sub_cat_id').html('<option>--- Select Sub Category---</option>');
					$.each(json, function (i, value) {
						$('#sub_cat_id').append($('<option>').text(value).attr('value', i));
					});
				}
			});
		});
		$('.decimal').on('keypress', function (e) {
			//Only allow 0-9, '.' and backspace (charCode 0 in Firefox)
			if (e.charCode == 46 || e.charCode == 44) {
				var currentContents = $(this).val();
				return !(currentContents.indexOf('.') != -1);
			}
			/*if ((event.which != 46 || $(this).val().indexOf('.') != -1)) {
			 event.preventDefault();
			 }*/
			return (e.charCode >= 48 && e.charCode <= 57) || e.charCode === 0;
		});

		$(".contact_number").keypress(function (e) {
			//if the letter is not digit then display error and don't type anything
			var contact_num_length = $(this).val().length;
			var max_length = 15;
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			} else if (e.which != 8 && e.which != 0 && (contact_num_length == max_length)) {
				alert('Max length of entering contact number is ' + max_length);
				return false;
			}
		});
		$(".contact_number, .decimal").on("copy paste", function () {
			return false;
		});

	});

	$('.cancel').click(function () {
		//var url = "<?php echo $this->request->referer(); ?>"
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'index','admin'=>'true')); ?>";
		window.location.href = url;
        //window.location.href = '<?php echo $url; ?>';
		//history.go(-1);
	});

	function ValidateWebPage() {
		var sub_cat = $('#sub_cat_id').val();
		if (sub_cat.length == 0 || sub_cat == '--- Select Sub Category---')
		{
			alert("Please select proper category to proceed.");
			$('#sub_cat_id').focus();
			return false;
		}

		var txt = $.trim($('#CardCWebpage').val());
		if (txt != '') {
			var re = /(http(s)?:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/;
			if (!re.test(txt)) {
				alert('Please enter valid webpage url');
				$('#CardCWebpage').focus();
				return false;
			}
		}
	}
	$(document).ready(function () {
		$('#product').addClass('sb_active_opt');
		$('#product').removeClass('has_submenu');
		$('#addcard_active').addClass('sb_active_subopt_active');

	});

</script>