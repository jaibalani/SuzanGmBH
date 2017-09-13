<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
    -->
    <div class="sub_title"><i class="icon-book home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Main Category</span></div>
    <div class="main_subdiv">
        <div class="gird_button">
            <div class="main_sub_title cat_w" style="width:30%;"><?php echo $title; ?></div>
                <!-- <button class="new_button mar_right" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;&nbsp;Delete</button> -->
            <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
            <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
            </button>

            <button class="new_button new_button_right" id="add_button" type="button" style="float:right; margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp;Add New</button>
            <button class="new_button back_new " type="button" style="cursor:pointer;float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>

        </div>    

        <div class="clear10"></div>
        <div align="left" class="grid_table_box">
            <table  width="100%" id="list"></table> 
            <div id="gridpager"></div>
        </div>
        <div class="clear10"></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var editOptions = {
            aftersavefunc: function (rowid, response, options) {
                console.log("Строка " + rowid + " сохранена.");
                //$("#grid").trigger("reloadGrid");
            }
        };

        var lastSel;
        jQuery("#list").jqGrid({
            url: "<?php echo Router::url(array('controller' => 'Categories', 'action' => 'admin_json')); ?>",
            datatype: "json",
            colNames: ['Title', 'Action', 'Status'],
            colModel: [
                {
                    name: 'Category.cat_title',
                    index: 'Category.cat_title',
                    width: 240,
                    search: true,
                    sortable: true,
                    align: "left",
                    jsonmap: "Category.cat_title",
                    editable: true
                },
                {
                    name: 'edit',
                    index: 'edit',
                    width: 60,
                    search: false,
                    sortable: false,
                    align: "center"
                },
                {
                    name: 'Category.cat_status',
                    index: 'Category.cat_status',
                    width: 50,
                    search: true,
                    stype: 'select',
                    searchoptions: {
                        value: {'3': 'All', 1: 'Enable', 0: 'Disable'},
                        defaultValue: '3'
                    },
                    sortable: true,
                    align: "center",
                }
            ],
            /*
             onSelectRow: function(id){
             //alert(id);
             if(id && id!==lastSel){ 
             jQuery('#list').restoreRow(lastSel); 
             lastSel=id; 
             }
             jQuery('#list').editRow(id, true,null,function(xhr){
             var data = eval(xhr.responseText);
             if(data == '0'){
             alert("Selected record not saved please try again");
             }else{
             alert("Selected record upldated successfully.");
             return true;
             }
             }); 
             },
             */
            editurl: "<?php echo $this->Html->url(array("controller" => "categories", "action" => "inline_category", 'admin' => true)); ?>",
            rowNum: 50,
            rowList: [10, 20, 30, 50],
            pager: '#gridpager',
            sortname: 'Category.cat_title',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption: false,
            multiselect: false,
            height: "100%",
            autowidth: true,
            shrinkToFit: true,
            gridview: true,
            loadonce: false,
            hidegrid: false,
            beforeRequest: function () {
                $("#loading-image").fadeIn();
            },
            gridComplete: function () {
                $('#list tr:nth-child(odd)').addClass("evenTableRow");
                $('#list tr:nth-child(even)').addClass("oddTableRow");
                $("#loading-image").fadeOut();
            }
        });
        jQuery("#list").jqGrid('navGrid', '#gridpager', {edit: false, search: false, add: false, del: false, refresh: true});
        jQuery("#list").jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});

        //delete records
        $("#delete_button").click(function () {
            var ids = getSelectedRows();

            if (ids != '') {
                if (confirm("Are you sure? You want to remove this record.")) {
                    $.ajax({
                        url: "<?php echo Router::url(array('controller' => 'Categories', 'action' => 'admin_delete')); ?>",
                        type: "POST",
                        data: ({ids: ids}),
                        success: function (data) {
                            if (data == '1') {
                                reloadGrid();
                                alert('Selected record(s) deleted successfully.');
                            } else {
                                alert('Error in deleting.Please try again later.');
                            }
                        }
                    });
                }
            } else {
                alert('Please select rows to perform this specific action.');
            }
        });

        $("#add_button").click(function () {
            window.location = "<?php echo Router::url(array('controller' => 'CategoriesLanguages', 'action' => 'admin_add')); ?>";
        });
        $('.back').click(function () {
            history.go(-1);
            //window.location = "<?php //echo Router::url(array('controller'=>'Categories','action'=>'admin_index')); ?>";
        });

    });

    $('.back_new').click(function () {
        var url = "<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'dashboard', 'admin' => 'true')); ?>";
        history.go(-1);
        // window.location.href = url;
    });

    function reloadGrid() {
        jQuery("#list").trigger("reloadGrid");
    }

    function getSelectedRows() {
        var s = jQuery("#list").jqGrid('getGridParam', 'selarrrow');
        return s;
    }
    function changeStatus(id, status, child_count)
    {
        var msg = "";

        st = status.value;
        if (st == 1) {
            msg = "Are you sure? You want to active the status.";
        } else {
            msg = "Are you sure? You want to inactive the status.";
        }
        if (confirm(msg)) {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Categories', 'action' => 'admin_changestatus')); ?>",
                type: "POST",
                data: ({id: id, st: st, child_count: child_count}),
                success: function (data) {
                    if (data == '1') {
                        //alert('Status has been changed successfully');
                        reloadGrid();
                    }
                }
            });
        }
        else {

            var st = st == 1 ? 0 : 1;
            $(status).val(st);

        }
    }

    $(document).ready(function () {
        $('#product').addClass('sb_active_opt');
        $('#product').removeClass('has_submenu');
        $('#cat_active').addClass('sb_active_subopt_active');
    });

    var rowid;
    function inplaceEdit(id) {
        jQuery('#list').editRow(id);
        changeActionState('edit', id);
    }
    function inplaceCancel(id) {
        jQuery('#list').restoreRow(id);
        changeActionState('cancel', id);
    }
    function inplaceSave(id) {
        rowid = id;
        jQuery('#list').saveRow(id, checkSave);

        //jQuery('#list').saveRow(id);
        //changeActionState('save', id);
    }

    function inplaceDel(id, child_count)
    {
        var msg = "";

        st = '3';
        
        msg = "Are you sure? You want to delete selected Category.";
        
        if (confirm(msg)) {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Categories', 'action' => 'admin_changestatus')); ?>",
                type: "POST",
                data: ({id: id, st: st, child_count: child_count}),
                success: function (data) {
                    if (data == '1') {
                        //alert('Status has been changed successfully');
                        reloadGrid();
                    }
                }
            });
        }
        else {

            var st = st == 1 ? 0 : 1;
            $(status).val(st);

        }
    }

    function checkSave(result) {

        if (result.responseText.toLowerCase() == '1') {
            changeActionState('save', rowid);
            reloadGrid();
        }
        else
        {
            alert(result.responseText); 
            changeActionState('save', rowid);
            reloadGrid();
        }
    }

    function changeActionState(action, id) {
        if (action == 'edit') {
            jQuery('#action_edit_' + id).css('display', 'none');
            jQuery('#action_save_' + id).css('display', 'inline-block');
            jQuery('#action_cancel_' + id).css('display', 'inline-block');

            /* EXTRA WORK */
            $("#action_status_" + id).attr("disabled", false);
            jQuery('#action_view_' + id).css('display', 'none');
             jQuery('#action_del_' + id).css('display', 'none');
        }
        else {
            jQuery('#action_edit_' + id).css('display', 'inline-block');
            jQuery('#action_save_' + id).css('display', 'none');
            jQuery('#action_cancel_' + id).css('display', 'none');

            $("#action_status_" + id).attr("disabled", true);
            jQuery('#action_view_' + id).css('display', 'inline-block');
             jQuery('#action_del_' + id).css('display', 'inline-block');
        }
    }

	$('.clear_filer_class').click(function(){
			var new_url = "<?php echo $this->Html->url(array('controller'=>'Categories','action'=>'admin_json'));?>";
			$('#list').setGridParam({url:new_url});
			$("#list")[0].clearToolbar();
			reloadGrid();
	});

    function delete_main_category(id)
    {
        var ans = confirm("Are you sure? You want to delete the selected category.");
        if(ans)
        {
            window.location = "<?php echo Router::url(array('controller'=>'Categories','action'=>'delete_main_category'));?>/"+id;
        }
    }
</script>