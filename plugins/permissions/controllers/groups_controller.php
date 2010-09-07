<?php

App::import('Core', 'Sanitize');
class GroupsController extends PermissionAppController {
   
   var $name = 'Groups';
   var $paginate = array(
      'Group' => array(
         'fields' => array(
            'Group.id', 'Group.name',
         ),
         'limit' => 15,
         'recursive' => 1,
         'order' => array('Group.id' => 'DESC'),
      ));

   function index() {
      $this->paginate = array(
            'conditions' => array('Group.id !=' => '')
         );
      $data = $this->paginate('Group');
      $this->set('data', $data);
   }
   
   function privileges($id = null) {
      $this->Group->unBindModel(array('hasMany' => array('User')));
      $data = $this->Group->find('first', array(
            'conditions' => array(
               'Group.id' => $id
               ),
            'recursive' => 1
         ));
      $this->set('data', $data);
   }
   
   function add() {
      if(!empty($this->data)) {
         $data = Sanitize::clean($this->data);
         if($this->Group->save($data)) {    
            $this->Flash->success();
            $this->redirect(array('action' => 'index'));
         }
         else {
            $this->Flash->error();
         }
      }
   }
   
   function edit($id = null) {
      if (!$id) {
         $this->Flash->error();
            $this->redirect(array('action' => 'index'));
      }
      if(!empty($this->data)) {
         $data = Sanitize::clean($this->data);
         if($this->Group->save($data['Group'])) {
            $this->Flash->success();
            $this->redirect(array('action' => 'index'));
         }
         else {
            $this->Flash->error();
         }
      }
      $this->data = $this->Group->read(null, Sanitize::clean($id));
   }
   
   function delete($id = null) {
      if (!$id) {
         $this->Flash->error();
         $this->redirect(array('action' => 'index'));
      }
      if ($this->Group->delete(Sanitize::clean($id), true)) {
         $this->Flash->delete();
         $this->redirect(array('action' => 'index'));
      }
      else {
         $this->Flash->error();
         $this->redirect(array('action' => 'index'));
      }
   }
}
?>