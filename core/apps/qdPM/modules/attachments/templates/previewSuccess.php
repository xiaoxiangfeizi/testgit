<?php foreach($attachments as $a): 

$file_path = sfConfig::get('sf_upload_dir') . '/attachments/' . $a->getFile();

?>
  <div id="attachedFile<?php echo $a->getId() ?>">
    <table>
      <tr>
        <td colspan="2"><?php echo Attachments::getFileIcon($a->getFile())  . ' ' . (is_file($file_path) ? (getimagesize($file_path) ? link_to( substr($a->getFile(),7), 'attachments/view?id=' . $a->getId(), array('target'=>'_blank', 'absolute'=>true)) :Attachments::getLink($a)):substr($a->getFile(),7)) ?></td>
      </tr>                                                                                     
      </tr>
        <?php if($a->getBindType()!='wiki'):?>
        <td><?php echo __('Description') . ':</td><td> ' . textarea_tag('attachments_info[' . $a->getId() . ']',$a->getInfo(),array('style'=>'height: 50px;','class'=>'attachments_textarea'))?></td>
        <?php endif ?>
        
        <td><a href="#" onClick="return deleteAttachments(<?php echo $a->getId() ?>,'<?php echo url_for('attachments/delete?id=' . $a->getId()) ?>')"><?php echo __('Delete') ?></a></td>
      </tr> 
      <?php if($a->getBindType()=='wiki') echo '<tr><td colspan="3">[' . url_for('attachments/view?id='.$a->getId(),true). ' ' . substr($a->getFile(),7) . ']</td></tr>' ?>       
    </table>
  </div>
<?php endforeach ?>