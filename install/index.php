<?php
  error_reporting(E_ALL & ~E_NOTICE);
  
  include('lib/databaseHelper.php');
       
  if($_GET['step']=='qdpm_config') include('actions/check_db_settings.php');
  if($_GET['action']=='install_qdpm') include('actions/install_qdpm.php');
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />      
    <meta name="language" content="en" /> 
    <title>qdPM 8.2 Installation</title> 
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" /> 
    </head> 
  <body>      
  <div id="page"> 
  
    <div id="header-wrap"> 
      <div id="header"> 
        <div id="logo-section">qdPM 8.2 Installation</div>                  
      </div> 
    </div> 
 
    <div class="shadow-top"><div class="shadow-top-bar"></div></div>  
    <div id="center-wrap">  
      <div id="center"> 
        <div id="content"> 
                                                    
          <?php if(!$_GET['step']) include('modules/checking_environment.php')?>
          <?php if($_GET['step']=='database_config') include('modules/database_config.php')?>
          <?php if($_GET['step']=='qdpm_config') include('modules/qdpm_config.php')?>
          <?php if($_GET['step']=='success') include('modules/success.php')?>

 
        </div> 
                        
      </div> 
    </div> 
 
    <div class="patch_minheight"></div> 
    <div id="footer_guarantor"></div> 
  </div> 
  
    
 
  <div id="footer-wrap"> 
    <div class="shadow-bottom"></div> 
    <div id="footer"> 
      <div class="footer-text">       
        <br>
        qdPM 8.2 | <a class="footer-text" target="_blank" href="http://qdpm.net/free-project-management-forum/index.php">Forum</a> | <a class="footer-text" target="_blank" href="http://qdpm.net/contact_us.php">Contacts</a>      <br>      
       Copyright @ 2010 <a title="Project Management, Time Tracking, Support Tickets" class="footer-text" target="_blank" href="http://qdpm.net/">qdpm.net</a>
 
      </div> 
 
    </div> 
  </div> 
   
  </body> 
</html> 