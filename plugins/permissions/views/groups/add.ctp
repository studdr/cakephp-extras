<div id="body">
      <h2>Add Group</h2>
      <?php
      echo $this->Form->create('Group');
      echo $this->Form->input('id');
      echo $this->Form->input('name');
      echo $this->Form->submit('Edit Group');
      echo $this->Form->end();
      ?>
   
</div>