<div id="main">
	<div class="content">
		<ul>
			<li>
					<ol>
						<li class="header taskname">Name</li>
						<li class="header taskdesc">Desc</li>
						<li class="header taskactn">Actions</li>
					</ol>
			</li>
			<li>
				<form type="POST" action="todo.php">
					<ol>
						<li class="cell taskname"><input type="text" name="taskname"/></li>
						<li class="cell taskdesc"><input type="text" name="taskdesc"/></li>
						<li class="cell taskactn"><input type="submit" value="add"/></li>
					</ol>
				</form>
			</li>
			<?php foreach ($tasks as $task):?>
			<li>
				<ol>
					<li class="cell taskname"><?php echo $task['name'];?></li>
					<li class="cell taskdesc"><?php echo $task['description'];?></li>
					<li class="cell taskactn">
						<input type="checkbox" class="state" name="done[<?php echo $task['id'];?>]" title="<?php echo $task['id'];?>" <?php echo ($task['state']?'checked="checked"':'');?>/>
					</li>
				</ol>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>