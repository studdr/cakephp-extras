<div id="body">
   <h2>group: <?php echo $data['Group']['name']; ?></h2>
   <?php echo $this->Html->div('add', $this->Html->link('Add Privilege', array('controller' => 'privileges', 'action' => 'add', $data['Group']['id']))); ?>
   <?php
   echo $form->create('Privilege', array('url' => '/privileges/group_delete/'. $this->params['pass'][0]));
   ?>
   <table>
   	<tr> 
   		<th> &nbsp; </th> 
   		<th>id</th> 
   		<th>controller</th>
   		<th>action</th>
   		<th class="center">Delete</th> 
   	</tr>
    	<?php 
    	$i = 0;
    	foreach ($data['Privilege'] as $key => $value): 
    	?>
   	<tr> 
   		<td><input type="checkbox" name="data[Privilege][<?php echo $i; ?>][id]" value="<?php echo $value['id']; ?>" /></td> 
   		<td><?php echo $value['id']; ?></td> 
   		<td><?php echo $value['controller']; ?></td> 
   		<td><?php echo $value['action']; ?></td> 
   		<td class="center">
   		   <?php 
   		   echo $this->Html->link(
   		         $this->Html->image('icons/actions/delete.png', array('alt' => 'Delete')), 
   		         array('controller' => 'privileges', 'action' => 'delete', $value['id'], $value['group_id']), 
   		         array('escape' => false), null, false
		         ); 
   		   ?>
   		</td> 
   	</tr>
      <?php 
         $i++;
      endforeach; 
      ?>
   </table>
   <?php
   echo $form->submit('Delete Selected');
   echo $form->end();
   ?>
</div>