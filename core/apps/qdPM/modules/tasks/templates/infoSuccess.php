<h1><?php echo ($tasks->getTasksLabelId()>0 ? $tasks->getTasksLabels()->getName(). ': ':'') . $tasks->getName() . ($tasks->getTasksStatusId()>0 ? ' [' . $tasks->getTasksStatus()->getName(). '] ':'') ?></h1>

<table class="contentTable" style="width: 100%">
<tr>
  <td>
    <div><?php echo  replaceTextToLinks($tasks->getDescription()) ?></div>
    <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'comments','bind_id'=>$tasks->getId())) ?></div>
  </td>
  <td style="width:45%">
    <div id="itemDetailsContainer">
      <div id="itemDetailsBox">        
        <?php include_component('tasks','details',array('tasks'=>$tasks)) ?>
      </div>
    </div>
  </td>
</tr>
</table>
