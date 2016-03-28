<?php

class ticketsComponents extends sfComponents
{
  public function executeListing(sfWebRequest $request)
  {
    if(!isset($this->reports_id)) $this->reports_id = false;
        
    $q = Doctrine_Core::getTable('Tickets')->createQuery('t')          
          ->leftJoin('t.TicketsStatus ts')          
          ->leftJoin('t.TicketsTypes tt')                              
          ->leftJoin('t.Departments td')
          ->leftJoin('t.Projects p')
          ->leftJoin('t.Users');
          
    if($request->hasParameter('projects_id'))
    {
      $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
      
      if(Users::hasAccess('view_own','tickets',$this->getUser(),$request->getParameter('projects_id')))
      {                 
        $q->addWhere("t.departments_id in (" . implode(',',Departments::getDepartmentIdByUserId($this->getUser()->getAttribute('id'))). ") or t.users_id='" . $this->getUser()->getAttribute('id') . "'");
      }
    }
    else
    {
      if(Users::hasAccess('view_own','projects',$this->getUser()))
      {       
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
      
      if(Users::hasAccess('view_own','tickets',$this->getUser()))
      {                 
        $q->addWhere("t.departments_id in (" . implode(',',Departments::getDepartmentIdByUserId($this->getUser()->getAttribute('id'))). ") or t.users_id='" . $this->getUser()->getAttribute('id') . "'");
      }
      
    }      
                              
    if($this->reports_id>0)
    {
      $q = TicketsReports::addFiltersToQuery($q,$this->reports_id,$this->getUser());      
    }                
    elseif($request->hasParameter('search'))
    {    
      $q = app::addSearchQuery($q, $request->getParameter('search'),'TicketsComments','t',$request->getParameter('search_by_extrafields'));
      $q = app::addListingOrder($q,'tickets',$this->getUser());
    }
    else
    {
      $q = Tickets::addFiltersToQuery($q,$this->getUser()->getAttribute('tickets_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '')));
      $q = app::addListingOrder($q,'tickets',$this->getUser(), (int)$request->getParameter('projects_id'));            
    }
    
    if(sfConfig::get('app_rows_limit')>0)
    {
      $this->pager = new sfDoctrinePager('Tickets', sfConfig::get('app_rows_limit'));
      $this->pager->setQuery($q);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
    }
                                  
    $this->tickets_list = $q->fetchArray();
            
    if(isset($this->is_dashboard))
    {
      $this->url_params = 'redirect_to=dashboard';
      $this->display_insert_button = true;
    }
    elseif($this->reports_id>0)
    {
      $this->url_params = 'redirect_to=ticketsReports' . $this->reports_id;
      $this->display_insert_button = true;
    }
    else
    {
      $this->url_params = 'redirect_to=ticketsList';
      if($request->hasParameter('projects_id')) $this->url_params = 'projects_id=' . $request->getParameter('projects_id');
      $this->display_insert_button = true;
    }
    
    $this->tlId = rand(1111111,9999999);
  }
  
  public function executeFilters(sfWebRequest $request)
  {  
    $m = array();
    
    $params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
            
    
    $m = app::getFilterMenuItemsByTable($m,'TicketsStatus','Status','tickets/index',$params);    
    $m = app::getFilterMenuItemsByTable($m,'TicketsTypes','Type','tickets/index',$params);  
    $m = app::getFilterMenuItemsByTable($m,'Departments', 'Department','tickets/index',$params);
    $m = app::getFilterMenuUsers($m,'TicketsCreatedBy','Created By','tickets/index',$params);    
    
    if(!$params)
    {
      $m = app::getFilterProjects($m,'tickets/index',$params,array(),$this->getUser());      
      $m = app::getFilterMenuItemsByTable($m,'ProjectsStatus','Projects Status','tickets/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'ProjectsTypes','Projects Types','tickets/index',$params);      
    }
              
                    
    $this->m = array(array('title'=>__('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview(sfWebRequest $request)
  {
    $this->filter_by = $this->getUser()->getAttribute('tickets_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : ''));
    $this->params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
    $this->filter_tables = array('TicketsPriority'=>'Priority', 'TicketsStatus'=>'Status','TicketsTypes'=>'Type','TicketsGroups'=>'Group','Departments'=>'Department','TicketsCreatedBy'=>'Created By');
    
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
  
  
  public function executeRelatedTicketsToTasks()
  {          
    $task = Doctrine_Core::getTable('Tasks')->find($this->tasks_id); 
    
    $this->tickets_list = array();
    
    if($task)
    {                
      $this->tickets_list = $q = Doctrine_Core::getTable('Tickets')->createQuery('t')          
            ->leftJoin('t.TicketsStatus ts')
            ->leftJoin('t.TicketsTypes tt')          
            ->leftJoin('t.Projects p')    
            ->addWhere('id=?',$task->getTicketsId())                            
            ->fetchArray();
    }
  }
}