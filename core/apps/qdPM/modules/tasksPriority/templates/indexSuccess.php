<h1><?php echo __('Tasks Priorities') ?></h1>
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
      <th><?php echo __('Icon') ?></th>
      <th><?php echo __('Sort Order') ?></th>
      <th><?php echo __('Active?') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tasks_prioritys as $tasks_priority): ?>
    <tr>
      <td><?php echo $lc->action_buttons($tasks_priority->getId()) ?></td>
      <td><?php echo $tasks_priority->getName() ?></td>
      <td><?php echo renderBooleanValue($tasks_priority->getDefaultValue()) ?></td>      
      <td><?php if(strlen($tasks_priority->getIcon())>0) echo image_tag('icons/p/'.$tasks_priority->getIcon()) ?></td>
      <td><?php echo $tasks_priority->getSortOrder() ?></td>      
      <td><?php echo renderBooleanValue($tasks_priority->getActive()) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($tasks_prioritys)==0) echo '<tr><td colspan="6">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
<?php echo $lc->insert_button(); ?>

<script type="text/javascript">
  $(document).ready(function(){ $('table.tableListing tbody tr:odd').addClass('odd') });
</script>
