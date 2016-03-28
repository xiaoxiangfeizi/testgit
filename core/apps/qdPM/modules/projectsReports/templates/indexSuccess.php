<h1><?php echo __('Projects Reports') ?></h1>

<?php
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button(__('Add Report')) . ' ' .  $lc->sort_button();
?>

<table class="tableListing">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>            
      <th width="100%"><?php echo __('Name') ?></th>
      <th><?php echo __('Display on dashboard') ?></th>      
      <th><?php echo __('Display in menu') ?></th>                  
    </tr>
  </thead>
  <tbody>
    <?php foreach ($projects_reportss as $projects_reports): ?>
    <tr>      
      <td><?php echo $lc->action_buttons($projects_reports->getId()) ?></td>      
      <td><?php echo link_to($projects_reports->getName(),'projectsReports/view?id=' . $projects_reports->getId()) ?></td>
      <td><?php echo renderBooleanValue($projects_reports->getDisplayOnHome()) ?></td>      
      <td><?php echo renderBooleanValue($projects_reports->getDisplayInMenu()) ?></td>      
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($projects_reportss)==0) echo '<tr><td colspan="4">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
<?php echo $lc->insert_button(__('Add Report')); ?>

<script type="text/javascript">
  $(document).ready(function(){ $('table.tableListing tbody tr:odd').addClass('odd') });
</script>
