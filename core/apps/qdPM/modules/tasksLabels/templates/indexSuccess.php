<h1><?php echo __('Tasks Labels') ?></h1>
<?php                                                  
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button() . ' ' .  $lc->sort_button();
?>
<table class="tableListing">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      <th width="100%"><?php echo __('Name') ?></th>
      <th><?php echo __('Default?') ?></th>
      <th><?php echo __('Sort Order') ?></th>
      <th><?php echo __('Active?') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tasks_labelss as $tasks_labels): ?>
    <tr>
      <td><?php echo $lc->action_buttons($tasks_labels->getId()) ?></td>
      <td><?php echo $tasks_labels->getName() ?></td>
      <td><?php echo renderBooleanValue($tasks_labels->getDefaultValue()) ?></td>      
      <td><?php echo $tasks_labels->getSortOrder() ?></td>      
      <td><?php echo renderBooleanValue($tasks_labels->getActive()) ?></td>            
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($tasks_labelss)==0) echo '<tr><td colspan="6">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
<?php echo $lc->insert_button(); ?>

<script type="text/javascript">
  $(document).ready(function(){ $('table.tableListing tbody tr:odd').addClass('odd') });
</script>
