<?php
  $lc = new listingController('projects',$url_params,$sf_request,$sf_user);
  $extra_fields = ExtraFieldsList::getFieldsByType('projects',$sf_user);
  $totals = array();
  
  $is_filter = array();
  
  $is_filter['status'] = app::countItemsByTable('ProjectsStatus');
  $is_filter['type'] = app::countItemsByTable('ProjectsTypes');
  
  $has_comments_access = Users::hasAccess('view','projectsComments',$sf_user);      
?>   

<table width="100%">
  <tr>
    <td>
      <table>
        <tr>
          <?php if($display_insert_button): ?>
          <td style="padding-right: 15px;"><?php  echo $lc->insert_button(__('Add Project')) ?></td>
          <?php endif ?>
          <td style="padding-right: 15px;"><div id="tableListingMultipleActionsMenu"><?php echo $lc->rednerWithSelectedAction($tlId) ?></div></td>
          <td><?php echo  ((count($projects_list)>sfConfig::get('app_rows_per_page'))? get_partial('global/jsPager', array('id'=>'pager'. $tlId)): '') ?></td>
        </tr>
      </table>
    </td>
    <td align="right">
      <form onSubmit="return false">
      <table>
        <tr>
          <td><?php echo __('Quick Search') ?>&nbsp;</td>
          <td><input name="filter" id="filter-box<?php echo $tlId ?>" value="" maxlength="20" size="20" type="text">&nbsp;</td>
          <td><?php echo image_tag('icons/reset.png',array('id'=>'filter-clear-button'. $tlId,'title'=>__('Reset'),'class'=>'pointer'))?>&nbsp;</td>                              
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>

<div class="itemsListing projectsListing">
  <div class="itemsListingContainer">
      
<table class="tableListing" id="tableListing<?php echo $tlId ?>" style="display:none;">
  <thead>
    <tr>
      <?php echo $lc->rednerMultipleSelectTh() ?>
      <?php echo $lc->rednerActionTh() ?>      
      <th><div><?php echo __('Id') ?></div></th>

      
      <?php if($is_filter['status']): ?>
      <th><div><?php echo __('Status') ?></div></th>  
      <?php endif; ?>
                            
      <th width="100%"><div><?php echo __('Name') ?></div></th>
      
      <?php if($is_filter['type']): ?>
      <th><div><?php echo __('Type') ?></div></th>
      <?php endif; ?>
                  
      <th><div><?php echo __('Created By') ?></div></th>
      <th><div><?php echo __('Created At') ?></div></th>
      
      <?php echo ExtraFieldsList::renderListingThead($extra_fields) ?>                  
    </tr>
  </thead>
  
  <tbody>  
    <?php foreach ($projects_list as $projects): ?>
    <tr>     
      <?php echo $lc->rednerMultipleSelectTd($projects['id']) ?>
      <?php echo $lc->renderActionTd($projects['id'],0,false,__('Are you sure you want to delete project') . '\n\n' . $projects['name'] . '?\n\n' . __('Note: all Projects Tasks, Tickets and Discussions will be deleted as well.')) ?>      
      <td><?php echo $projects['id'] ?></td>

      
      <?php if($is_filter['status']): ?>
      <td><?php echo app::getArrayName($projects,'ProjectsStatus') ?></td>
      <?php endif; ?>
                        
      <td>
        <?php echo link_to($projects['name'],'projects/open?projects_id=' . $projects['id'])  ?>
        <?php if($has_comments_access) echo  ' '. app::getLastCommentByTable('ProjectsComments','projects_id',$projects['id']) ?>
      </td>
      
      <?php if($is_filter['type']): ?>
      <td><?php echo app::getArrayName($projects,'ProjectsTypes') ?></td>
      <?php endif; ?>
            
      <td><?php echo app::getArrayName($projects,'Users') ?></td>
      <td><?php echo app::dateTimeFormat($projects['created_at'],0,true) ?></td>
      
      <?php
        $v = ExtraFieldsList::getValuesList($extra_fields,$projects['id']); 
        echo ExtraFieldsList::renderListingTbody($extra_fields,$v);
        $totals = ExtraFieldsList::getListingTotals($totals, $extra_fields,$v)  
      ?>
    </tr>
    <?php endforeach; ?>
    </tbody>
            
    <?php if(count($projects_list)>0 and count($totals)>0): ?>
    <tfoot>
    <tr>
      <td colspan="<?php echo $lc->count_columns($is_filter,5) ?>"></td>
      <?php echo ExtraFieldsList::renderListingTotals($totals,$extra_fields) ?>
    </tr>  
    </tfoot>
    <?php endif ?>
    
    <?php if(sizeof($projects_list)==0) echo '<tr><td colspan="20">' . __('No Records Found') . '</td></tr>';?>
  
</table>

  </div>
</div>

<?php if($display_insert_button) echo $lc->insert_button(__('Add Project')) ?>

<?php include_partial('global/pager', array('total'=>count($projects_list), 'pager'=>$pager,'url_params'=>($reports_id>0 ? 'id=' . $reports_id:''),'page'=>$sf_request->getParameter('page',1),'reports_action'=>'projectsReports','reports_id'=>$reports_id)) ?>

<?php include_partial('global/jsTips'); ?>

<?php if(count($projects_list)>0){ ?>
<script type="text/javascript">
  $(document).ready(function(){   
    $("#tableListing<?php echo $tlId ?>").css('display','table');    
    $("#tableListing<?php echo $tlId ?>").tablesorter({widgets: ['zebra']}).tablesorterPager({ container: $("#pager<?php echo $tlId ?>"),size:<?php echo sfConfig::get('app_rows_per_page')?>, positionFixed: false}).tablesorterFilter({filterContainer: "#filter-box<?php echo $tlId ?>",filterClearContainer: "#filter-clear-button<?php echo $tlId ?>"});                                      
  });     
</script>
<?php }else{ ?>
<script type="text/javascript">
  $(document).ready(function(){
    $("#tableListing<?php echo $tlId ?>").css('display','table');
    $('table.tableListing<?php echo $tlId ?> tbody tr:odd').addClass('odd')
  });
</script>
<?php } ?>  