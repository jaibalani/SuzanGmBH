<style>
#loading-image
{   
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  position: fixed;
  display: block;
  opacity: 0.7;
  background-color: #EEEEEE;
  z-index: 99;
  text-align: center;
}
  
#image
{
  position: fixed;
  top: 300px;
  left: 630px;
  z-index: 100;
}
</style>
    <div id="loading-image" style="display:none;"><?php echo $this->Html->image('loading.gif',array('id'=>'image')); ?></div> 
