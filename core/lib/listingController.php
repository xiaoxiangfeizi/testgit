<?php

class listingController
{
  public $module;
  
  public function __construct($module,$url_params = '',$sf_request,$sf_user)
	{
	 $this->module = $module;
	 $this->url_params = $url_params;
	 $this->sf_request = $sf_request;
	 $this->sf_user = $sf_user;
	 $this->access = Users::getAccessSchema($module,$sf_user,$sf_request->getParameter('projects_id'));	 	 
	}
	
	public function count_columns($filters,$count)
	{
    if($this->access['edit'] or $this->access['delete']) $count++;
    
    foreach($filters as $v)
    {
      if($v>0) $count++;
    }
    
    return $count;
  }
		
	public function add_url_params($d)
	{
    if(strlen($this->url_params)>0)
    {
      return $d . $this->url_params; 
    }
    else
    {
      return '';
    }    
  }
	
	public function insert_button($title)
	{
	  
	  if($this->access['insert'])
	  {
	    $f = new sfWidgetFormInput();
	    $attributes = array('type'=>'button', 'class'=>'btn','onClick'=>'openModalBox(\'' . url_for($this->module . '/new' . $this->add_url_params('?'),true) . '\')');
      return $f->render('',$title,$attributes);
    }
  }
  
  public function add_projects_id($id = 0)
  {
    if($id>0)
    {
      return '&projects_id=' . $id;
    }
    else
    {
      return '';
    }
  }
    
  public function edit_button($id,$projects_id=0)
	{
	  if($this->access['edit'])
	  {
	    $f = new sfWidgetFormInput();
	    $attributes = array('type'=>'image','title'=>__('Edit'),'class'=>'actionIcon iconEdit','src'=>public_path('images/icons/edit.png',true),'onClick'=>'openModalBox(\'' . url_for($this->module . '/edit?id=' . $id  . $this->add_projects_id($projects_id) . $this->add_url_params('&'),true) . '\')');
      return $f->render('',__('Edit'),$attributes);
    }
  }
        
  public function delete_button($id,$projects_id=0,$confirm = false)
	{
	  if($this->access['delete'])
	  {
      if(!$confirm)
      {
        $confirm = __('Are you sure?');
      }
            
      return link_to(image_tag(public_path('images/icons/delete.png',true),array('title'=>__('Delete'),'class'=>'iconDelete')), $this->module  . '/delete?id='  . $id  . $this->add_projects_id($projects_id) . $this->add_url_params('&'), array('method' => 'delete','class'=>'actionIcon', 'confirm' => $confirm));
    }
  }
  
  public function add_child_button($id,$projects_id=0)
	{
	  if($this->access['insert'])
	  {
	    $f = new sfWidgetFormInput();
	    $attributes = array('type'=>'image','title'=>__('Add Child Task'),'class'=>'actionIcon','src'=>public_path('images/icons/add_child_task.png',true),'onClick'=>'openModalBox(\'' . url_for($this->module . '/new?parent_id=' . $id  . $this->add_projects_id($projects_id) . $this->add_url_params('&'),true) . '\')');
      return $f->render('',__('Edit'),$attributes);
    }
  }
  
  public function sort_tasks_tree($title)
	{
	  
	  if($this->access['edit'])
	  {
	    $f = new sfWidgetFormInput();
	    $attributes = array('type'=>'button', 'class'=>'btn','onClick'=>'location.href=\'' . url_for($this->module . '/sortTree' . $this->add_url_params('?'),true) . '\'');
      return $f->render('',$title,$attributes);
    }
  }
  
  public function renderActionTd($id,$projects_id=0,$is_tree_view=false, $confirm = false)
  {
    if($this->access['edit'] or $this->access['delete'])
    {
      return '<td>' . $this->delete_button($id,$projects_id,$confirm)  .  $this->edit_button($id,$projects_id) .  ($is_tree_view ? $this->add_child_button($id,$projects_id) : '') . '</td>';
    }
  }
  
  public function rednerActionTh()
  {
    if($this->access['edit'] or $this->access['delete'])
    {
      return '<th class="{sorter: false}"  style="width: 45px;">' . __('Action') . '</th>';
    }
  }
  
  public function rednerMultipleSelectTh()
  {
    return '<th class="{sorter: false}" style="width: 20px;"><input name="multiple_selected_all" id="multiple_selected_all" type="checkbox"></th>';
  }
  
  public function rednerMultipleSelectTd($id)
  {
    return '<td><input name="multiple_selected[]" id="multiple_selected_' . $id . '" type="checkbox" value="' . $id . '" class="multiple_selected"></td>';
  }
  
  public function rednerWithSelectedAction($tlId)
  {  
    $s = array();
    
    if($this->access['edit'])
    {
      $s[] = array('title'=>__('Update'),'url'=>$this->module . '/multipleEdit' . $this->add_url_params('?'),'mamodalbox'=>true);
      
      if($this->module!='projects')
      {
        $s[] = array('title'=>__('Move To'),'url'=>$this->module . '/moveTo' . $this->add_url_params('?'),'mamodalbox'=>true);
      }      
    }    
    
    
    $s[] = array('title'=>__('Export'),'url'=>$this->module . '/export' . $this->add_url_params('?'),'mamodalbox'=>true);
    
    if($this->access['delete'])
    {
      $s[] = array('title'=>__('Delete'),'url'=>'app/multipleDelete?table=' . $this->module . $this->add_url_params('&'),'mamodalbox'=>true,'is_hr'=>true);
    }
    
    $m = array();
    $m[] = array('title'=>__('With Selected'),'submenu'=>$s);
    
    return renderYuiMenu('with_selected_menu'.$tlId, $m) . '
    <script type="text/javascript">
        YAHOO.util.Event.onContentReady("with_selected_menu' . $tlId . '", function () 
        {
            var oMenuBar = new YAHOO.widget.MenuBar("with_selected_menu' . $tlId . '", {
                                                    autosubmenudisplay: true,
                                                    hidedelay: 750,
                                                    submenuhidedelay: 0,
                                                    showdelay: 150,
                                                    lazyload: true });
            oMenuBar.render();
        });
    </script>
    ';
  }
} 