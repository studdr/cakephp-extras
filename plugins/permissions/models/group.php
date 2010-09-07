<?php  

class Group extends PermissionAppModel { 

   var $name = 'Group'; 
   var $displayField = 'name';
   var $hasMany = array( 
      'User' => array( 
         'className' => 'User', 
         'foreignKey' => 'group_id'
      ),
      'Privilege' => array( 
         'className' => 'Privilege', 
         'foreignKey' => 'group_id'
      ),
   ); 

   function getList() {
      return $this->find('list', array(
            'fields' => array('Group.id', 'Group.name'),
            'recursive' => -1
         ));
   }
}
?>