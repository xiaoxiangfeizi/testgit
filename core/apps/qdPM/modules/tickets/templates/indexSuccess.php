<?php if($sf_request->hasParameter('projects_id')) include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<div class="pageHeading">
<table>
  <tr>
    <td><span class="pageHeading"><?php echo __('Tickets') ?></span></td>
    <td style="padding-left: 15px;"><?php include_component('tickets','filters') ?></td>
    <td style="padding-left: 15px;"><?php include_component('app','orderByMenu',array('module'=>'tickets')) ?></td>
    <td style="padding-left: 15px;"><?php include_component('app','searchMenu') ?></td>
  </tr>  
</table>
</div>

<div><?php if(!$sf_request->hasParameter('search')) include_component('tickets','filtersPreview') ?></div>

<?php include_component('tickets','listing') ?>