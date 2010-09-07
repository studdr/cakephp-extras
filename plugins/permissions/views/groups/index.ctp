<div id="body">
   <h2>Groups</h2>
   <?php echo $this->Html->div('add', $this->Html->link('Add Group', array('action' => 'add'))); ?>
   
   <?php echo $this->element('paginate'); ?>
   <table>
   	<tr> 
   		<th><?php echo $paginator->sort('ID', 'id'); ?></th> 
   		<th><?php echo $paginator->sort('Name', 'name'); ?></th>
   		<th>Permissions</th>
   		<th class="center">Actions</th> 
   	</tr>
   	<?php foreach ($data as $group): ?>
   	<tr> 
   		<td><?php echo $group['Group']['id']; ?></td> 
   		<td><?php echo $group['Group']['name']; ?></td> 
   		<td class="center"><?php echo $html->link('Permissions', array('action' => 'privileges', $group['Group']['id'])); ?></td> 
   		<td class="center">
   		   <?php echo $this->Html->link($this->Html->image('icons/actions/edit.png', array('alt' => 'Edit')), array('action' => 'edit', $group['Group']['id']), array('escape' => false), null, false); ?>
   		   <?php echo $this->Html->link($this->Html->image('icons/actions/delete.png', array('alt' => 'Edit')), array('action' => 'delete', $group['Group']['id']), array('escape' => false), null, false); ?>
   		</td> 
   	</tr>
      <?php endforeach; ?>
   </table>
</div>