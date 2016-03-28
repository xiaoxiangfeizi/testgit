<?php

/**
 * dashboard actions.
 *
 * @package    sf_sandbox
 * @subpackage dashboard
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dashboardActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if($this->getUser()->getAttribute('users_group_id')==0)
    {
      $this->redirect('users/index');
    }
        
    $this->reports = array();
    
    //users reports
    $projects_reports = Doctrine_Core::getTable('ProjectsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))
      ->addWhere('display_on_home=1')
      
      ->orderBy('sort_order, name')
      ->execute();
      
    foreach($projects_reports as $v)
    {
      $this->reports[] = 'projectsReports' . $v->getId();
    }
      
    $user_reports = Doctrine_Core::getTable('UserReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))
      ->addWhere('display_on_home=1')
      
      ->orderBy('sort_order, name')
      ->execute();
      
    foreach($user_reports as $v)
    {
      $this->reports[] = 'userReports' . $v->getId();
    }
      
    $tickets_reports = Doctrine_Core::getTable('TicketsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))
      ->addWhere('display_on_home=1')
      
      ->orderBy('sort_order, name')
      ->execute();
   
    foreach($tickets_reports as $v)
    {
      $this->reports[] = 'ticketsReports' . $v->getId();
    }
      
    $discussions_reports = Doctrine_Core::getTable('DiscussionsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))
      ->addWhere('display_on_home=1')
      
      ->orderBy('sort_order, name')
      ->execute();
    
    foreach($discussions_reports as $v)
    {
      $this->reports[] = 'discussionsReports' . $v->getId();
    }  
    
    if(!$this->getUser()->hasAttribute('hidden_dashboard_reports'))
    {
      $this->getUser()->setAttribute('hidden_dashboard_reports', array());
    }
    
    $this->hidden_dashboard_reports = $this->getUser()->getAttribute('hidden_dashboard_reports');
          
    app::setPageTitle('Dashboard',$this->getResponse());
  }
  
  public function executeExpandReport(sfWebRequest $request)
  {  

        
    $hidden_reports = $this->getUser()->getAttribute('hidden_dashboard_reports');
    
    if($request->getParameter('type')=='hide')
    {
      $hidden_reports[] = $request->getParameter('report');
      $this->getUser()->setAttribute('hidden_dashboard_reports', $hidden_reports);
    }
    else
    {
      if($key = array_search($request->getParameter('report'),$hidden_reports))
      {
        unset($hidden_reports[$key]);
        
        $this->getUser()->setAttribute('hidden_dashboard_reports', $hidden_reports);
      }
    }
    exit();
  } 
  
  public function executeConfigure(sfWebRequest $request)
  {     
    $this->projects_reports = Doctrine_Core::getTable('ProjectsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
      
      ->orderBy('sort_order, name')
      ->execute();
            
    $this->user_reports = Doctrine_Core::getTable('UserReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
      
      ->orderBy('sort_order, name')
      ->execute();
      
    $this->tickets_reports = Doctrine_Core::getTable('TicketsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
      
      ->orderBy('sort_order, name')
      ->execute();
       
    $this->discussions_reports = Doctrine_Core::getTable('DiscussionsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
      
      ->orderBy('sort_order, name')
      ->execute();
      
    if($request->isMethod(sfRequest::PUT))
    {
      Doctrine_Query::create()->update('ProjectsReports')->set('display_on_home', 0)->where('users_id =?', $this->getUser()->getAttribute('id'))->execute();
      Doctrine_Query::create()->update('UserReports')->set('display_on_home', 0)->where('users_id =?', $this->getUser()->getAttribute('id'))->execute();
      Doctrine_Query::create()->update('TicketsReports')->set('display_on_home', 0)->where('users_id =?', $this->getUser()->getAttribute('id'))->execute();
      Doctrine_Query::create()->update('DiscussionsReports')->set('display_on_home', 0)->where('users_id =?', $this->getUser()->getAttribute('id'))->execute();
      
      if($request->hasParameter('projects_reports'))
      {
        Doctrine_Query::create()->update('ProjectsReports')->set('display_on_home', 1)->whereIn('id',$request->getParameter('projects_reports'))->addWhere('users_id =?', $this->getUser()->getAttribute('id'))->execute();                
      }
      
      if($request->hasParameter('user_reports'))
      {
        Doctrine_Query::create()->update('UserReports')->set('display_on_home', 1)->whereIn('id',$request->getParameter('user_reports'))->addWhere('users_id =?', $this->getUser()->getAttribute('id'))->execute();                
      }
      
      if($request->hasParameter('tickets_reports'))
      {
        Doctrine_Query::create()->update('TicketsReports')->set('display_on_home', 1)->whereIn('id',$request->getParameter('tickets_reports'))->addWhere('users_id =?', $this->getUser()->getAttribute('id'))->execute();                
      }
      
      if($request->hasParameter('discussions_reports'))
      {
        Doctrine_Query::create()->update('DiscussionsReports')->set('display_on_home', 1)->whereIn('id',$request->getParameter('discussions_reports'))->addWhere('users_id =?', $this->getUser()->getAttribute('id'))->execute();                
      }
      
      $this->redirect('dashboard/index');
    }
  }
  
}
