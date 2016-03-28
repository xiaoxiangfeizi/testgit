<?php

/**
 * ProjectsReports
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ProjectsReports extends BaseProjectsReports
{

    
  public static function addFiltersToQuery($q,$reports_id,$sf_user=false)
  {
    if($r = Doctrine_Core::getTable('ProjectsReports')->find($reports_id))
    {     
      if(strlen($r->getProjectsStatusId())>0)
      {
        $q->whereIn('p.projects_status_id',explode(',',$r->getProjectsStatusId()));
      }
       
      if(strlen($r->getProjectsTypeId())>0)
      {
        $q->whereIn('p.projects_types_id',explode(',',$r->getProjectsTypeId()));
      }
        
      
      if(strlen($r->getProjectsId())>0)
      {
        $q->whereIn('p.id',explode(',',$r->getProjectsId()));
      }
      
      if($r->getInTeam()>0)
      {        
        $q->addWhere('find_in_set(' . $r->getInTeam() . ',p.team)');
      }   
      
      $q->orderBy('ps.sort_order,p.projects_status_id, p.name');
                            
    }
                  
    return $q;  
  }
}