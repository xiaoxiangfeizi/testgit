<?php

/**
 * ProjectsPhases form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectsPhasesForm extends BaseProjectsPhasesForm
{
  public function configure()
  {
    $this->widgetSchema['phases_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('PhasesStatus')));
    $this->setDefault('phases_status_id', app::getDefaultValueByTable('PhasesStatus'));
    
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['due_date'] = new sfWidgetFormInput(array(),array('class'=>'datepicker'));    
    $this->widgetSchema['name']->setAttributes(array('class'=>'required'));
    
    $this->widgetSchema->setLabels(array('phases_status_id'=>'Status','due_date'=>'Due Date'));
  }
}
