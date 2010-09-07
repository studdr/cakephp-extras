<?php

App::import('Core', 'Sanitize');
class PrivilegesController extends PermissionAppController {
   
   var $name = 'Privileges';
   
   function delete($id = null, $group_id = null) {
      if (!$id) {
         $this->Flash->invalid();
         $this->redirect($this->referer(), null, false);
      }
      if (!$group_id) {
         $this->Flash->invalid();
         $this->redirect($this->referer(), null, false);
      }
      if($this->Privilege->delete(Sanitize::clean($id))) {
         $this->Flash->delete();
         $this->redirect(array('controller' => 'groups', 'action' => 'privileges', $group_id));
      }
   }
   
   function group_delete($id = null) {
      if (!$id) {
         $this->Flash->invalid();
         $this->redirect($this->referer(), null, false);
      }
      if (!empty($this->data)) {
         $data = Sanitize::clean($this->data);
         foreach ($data['Privilege'] as $key => $value) {
            $this->Privilege->delete($value['id']);
         }
         $this->Flash->success();
         $this->redirect(array('controller' => 'groups', 'action' => 'privileges', $id));
      }
      else {
         $this->Flash->invalid();
         $this->redirect($this->referer(), null, false);
      }
   }
}
?>