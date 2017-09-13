<style>
.flipClass{cursor:pointer}

.frontSuccMessage{
	padding: 8px 35px 8px 14px;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	color: #4F8A10;
 	background-color: #DFF2BF;
	border:solid 1px #B5D87B;
	font-size:13px;
}
.frontErrorMessage{
	padding: 8px 35px 8px 14px;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	color: #CE4040;
  	background-color: #FFBABA;
  	border:solid 1px #E29191;
	font-size:13px;
}
.frontWarningMessage{
	padding: 8px 35px 8px 14px;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	color: #CE4040;
  	background-color: #FFBABA;
  	border:solid 1px #E29191;
	font-size:13px;
}
.flashclose {
  float: right;
  font-size: 20px;
  font-weight: bold;
  line-height: 20px;
  color: #000000;
  text-shadow: 0 1px 0 #ffffff;
  opacity: 0.2;
  filter: alpha(opacity=20);
  
  position: relative;
  top: -2px;
  right: -21px;
  line-height: 20px;
  
}

.flashclose:hover {
  color: #000000;
  text-decoration: none;
  cursor: pointer;
  opacity: 0.4;
  filter: alpha(opacity=40);
}

button.flashclose {
  padding: 0;
  cursor: pointer;
  background: transparent;
  border: 0;
  -webkit-appearance: none;
}

button::-moz-focus-inner,
input::-moz-focus-inner {
  padding: 0;
  border: 0;
}
</style>
<?php /*?><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><?php */?>
<div style="clear:both;"></div>
<div class="demp">
<?php if($this->Session->read('Message.success')){ ?>
  
    <div style="clear:both;"></div>
    <div align="center" class="frontSuccMessage">
    	<button onclick="closesuccmsg();" class="flashclose" type="button">&#215;</button>
    	<?php echo $this->Session->flash('success', array('params' => array('class' => 'flashconent')));?>
    </div>
<?php } ?>

<?php if($this->Session->read('Message.error')){ ?>

<div style="clear:both;"></div>
	<div align="center"  class="frontErrorMessage">
   		<button onclick="closesuccmsg();" class="flashclose" type="button">&#215;</button>
    	<?php echo $this->Session->flash('error', array('params' => array('class' => 'flashconent')));?>
    </div>

<?php } ?>
<?php if($this->Session->read('Message.warning')){ ?>


<div style="clear:both;"></div>
	<div align="center"  class="frontWarningMessage">
   		<button onclick="closesuccmsg();" class="flashclose" type="button">&#215;</button>
    	<?php echo $this->Session->flash('error', array('params' => array('class' => 'flashconent')));?>
    </div>

<?php } ?>
</div>

<script>
function closesuccmsg(){
	$(".demp").slideUp('slow');
	$(".demp").remove();
}
</script>