<?php

class tasksComponents extends sfComponents
{  
  public function executeListing(sfWebRequest $request)
  {
    if(!isset($this->reports_id)) $this->reports_id = false;
    
    
    $q = Doctrine_Core::getTable('Tasks')->createQuery('t')
          ->leftJoin('t.TasksPriority tp')
          ->leftJoin('t.TasksStatus ts')
          ->leftJoin('t.TasksLabels tl')
          ->leftJoin('t.TasksTypes tt')
          ->leftJoin('t.TasksGroups tg')
          ->leftJoin('t.ProjectsPhases pp')
          ->leftJoin('t.Versions v')
          ->leftJoin('t.Projects p')
          ->leftJoin('t.Users');
          
    if($request->hasParameter('projects_id'))
    {
      $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
      
      if(Users::hasAccess('view_own','tasks',$this->getUser(),$request->getParameter('projects_id')))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
    }
    else
    {
      if(Users::hasAccess('view_own','projects',$this->getUser()))
      {       
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
      
      if(Users::hasAccess('view_own','tasks',$this->getUser()))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }      
    }      
                              
    if($this->reports_id>0)
    {
      $q = UserReports::addFiltersToQuery($q,$this->reports_id,$this->getUser()->getAttribute('id'));
    }                
    elseif($request->hasParameter('search'))
    {    
      $q = app::addSearchQuery($q, $request->getParameter('search'),'TasksComments','t',$request->getParameter('search_by_extrafields'));
      $q = app::addListingOrder($q,'tasks',$this->getUser());
    }
    else
    {
      $q = Tasks::addFiltersToQuery($q,$this->getUser()->getAttribute('tasks_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '')));
      $q = app::addListingOrder($q,'tasks',$this->getUser(), (int)$request->getParameter('projects_id'));            
    }
    
    $this->pager = false;
    if(sfConfig::get('app_rows_limit')>0)
    {
      $this->pager = new sfDoctrinePager('Tasks', sfConfig::get('app_rows_limit'));
      $this->pager->setQuery($q);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
    }
                                      
    $this->tasks_list = $q->fetchArray();
    
    
            
    if(isset($this->is_dashboard))
    {
      $this->url_params = 'redirect_to=dashboard';
      $this->display_insert_button = true;
    }
    elseif($this->reports_id>0)
    {
      $this->url_params = 'redirect_to=userReports' . $this->reports_id;
      $this->display_insert_button = true;
    }
    else
    {
      $this->url_params = 'redirect_to=tasksList';
      if($request->hasParameter('projects_id')) $this->url_params = 'projects_id=' . $request->getParameter('projects_id');
      $this->display_insert_button = true;
    }
    
    $this->tlId = rand(1111111,9999999);
    
    $this->users_schema = Users::getSchema();
  }
  
  public function executeFilters(sfWebRequest $request)
  {  
    $m = array();
    
    $params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
            
    $m = app::getFilterMenuItemsByTable($m,'TasksPriority','Priority','tasks/index',$params);
    $m = app::getFilterMenuStatusItemsByTable($m,'TasksStatus','Status','tasks/index',$params);
    $m = app::getFilterMenuItemsByTable($m,'TasksLabels','Label','tasks/index',$params);
    $m = app::getFilterMenuItemsByTable($m,'TasksTypes','Type','tasks/index',$params);  
    $m = app::getFilterMenuUsers($m,'TasksAssignedTo', 'Assigned To','tasks/index',$params);
    $m = app::getFilterMenuUsers($m,'TasksCreatedBy','Created By','tasks/index',$params);    
    
    if(!$params)
    {
      $m = app::getFilterProjects($m,'tasks/index',$params,array(),$this->getUser());      
      $m = app::getFilterMenuItemsByTable($m,'ProjectsStatus','Projects Status','tasks/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'ProjectsTypes','Projects Types','tasks/index',$params);
      
    }
    else
    {
      $m = app::getFilterMenuItemsByTable($m,'TasksGroups','Group','tasks/index',$params);  
      $m = app::getFilterMenuItemsByTable($m,'ProjectsPhases','Phase','tasks/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'Versions','Version','tasks/index',$params);
    }
                          
    $this->m = array(array('title'=>__('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview(sfWebRequest $request)
  {
    $this->filter_by = $this->getUser()->getAttribute('tasks_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : ''));
    $this->params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
    $this->filter_tables = array('TasksPriority'=>'Priority', 'TasksStatus'=>'Status','TasksTypes'=>'Type','TasksLabels'=>'Label','TasksAssignedTo'=>'Assigned To','TasksCreatedBy'=>'Created By','TasksGroups'=>'Group','ProjectsPhases'=>'Phase','Versions'=>'Version');
    
    $this->filter_tables['Projects']='Projects';    
    $this->filter_tables['ProjectsStatus']='Project Status';
    $this->filter_tables['ProjectsTypes']='Project Type';    
    

  }
      
  public function executeDetails()
  {
       
  }
  
  public function executeEmailBody()
  {
       
  }
  
  public function executeRelatedTasksToDiscussions()
  {                 
    $this->tasks_list = $q = Doctrine_Core::getTable('Tasks')->createQuery('t')          
          ->leftJoin('t.TasksStatus ts')
          ->leftJoin('t.TasksLabels tl')          
          ->leftJoin('t.Projects p')    
          ->whereIn('discussion_id',$this->discussions_id)                            
          ->fetchArray();
  }
  
  public function executeRelatedTasksToTickets()
  {                 
    $this->tasks_list = $q = Doctrine_Core::getTable('Tasks')->createQuery('t')          
          ->leftJoin('t.TasksStatus ts')
          ->leftJoin('t.TasksLabels tl')          
          ->leftJoin('t.Projects p')    
          ->whereIn('tickets_id',$this->tickets_id)                            
          ->fetchArray();
  }
  
  public function executeViewType()
  {
    $s = array();
    $s[] = array('title'=>__('List'),'url'=>'tasks/index?projects_id=' . $this->projects->getId() . '&setViewType=list');
    $s[] = array('title'=>__('Tree'),'url'=>'tasks/index?projects_id=' . $this->projects->getId() . '&setViewType=tree');
    $this->m = array(array('title'=>__('View Type'),'submenu'=>$s));
  }
}