<?php

App::import('Core', 'Inflector');
class PermissionComponent extends Object {
   
   var $components = array('Auth', 'Session', 'Flash');
   var $Privilege;
   var $Controller;
      
	function startup(&$controller) {
      $this->Privilege = ClassRegistry::init('Privilege');
      $this->Controller = $controller;
      $this->verifyPrivilege();
   }
   
   function verifyPrivilege() {
      switch ($this->Controller->action) {
         case 'login':
         case 'logout':
            break;
         default:
            // get minimum level
            $valid = $this->Privilege->find('first', array(
                  'conditions' => array(
                     'and' => array(
                        'controller' => $this->Controller->name,
                        'action' => $this->Controller->action,
                        'group_id' => $this->Controller->Auth->user('group_id'),
                     )
                  ),
                  'fields' => array('Privilege.id'),
                  'limit' => 1,
                  'recursive' => -1
               ));
            
            // check if they have access
            if (!empty($valid['Privilege'])) return true;
            else {
               $this->Flash->denied();
               $this->Controller->redirect($this->Controller->referer(), null, false);
            }
      }
   }

	function setPrivileges() {
	   // get controllers
	   $controllers   = Configure::listObjects('controller'); 
	   
	   $this->filter['methods'] = get_class_methods('Controller'); 
      $this->filter['controller'] = array('App', 'Pages');             
       
      $list = array(); 
      foreach($controllers AS $c) { 
         if(in_array($c,$this->filter['controller'])) continue; 

         if(!App::import('Controller',$c)) { 
            foreach($plugins AS $p) { 
               if(strpos($p,$c) === 0 && App::import('Controller',$p . '.' . $c)) break; 
            }             
         } 
          
         // get controller methods
         $list[$c] = $this->_getMethods($c . 'Controller','methods'); 
      } 
      
      $i = '';
      foreach ($list as $key => $value) {
         foreach ($value as $k => $v) {
            $methods[$i] = array('controller' => $key, 'action' => $v);
            $i++;
         }
      }
      $groups = ClassRegistry::init('Group')->find('list', array(
            'fields' => array('Group.id', 'Group.name'),
            'recursive' => -1
         ));
      
      $i = 0;
      foreach ($groups as $k => $v) {
         foreach ($methods as $key => $value) {
            $method[$i] = array(
                  'id' => $i,
                  'group_id' => $k,
                  'controller' => $value['controller'],
                  'action' => $value['action'],
               );
            $i++;
         }
      }
      $this->Privilege = ClassRegistry::init('Privilege');
      foreach ($method as $key => $value) {
         $this->Privilege->create();
         $this->Privilege->save($value);
      }
	}
	
   function _getMethods($className,$filter = 'methods') { 
      $c_methods = get_class_methods($className); 
      $c_methods = array_diff($c_methods,$this->filter[$filter]); 
      $c_methods = array_filter($c_methods,array($this,"_removePrivate")); 
       
      return $c_methods; 
   } 
    
   function _removePrivate($var) { 
      if(substr($var,0,1) == '_') return false; 
      else return true; 
   } 
}
?>