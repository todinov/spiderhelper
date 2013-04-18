<div id="main">
	<div class="content">
		<ul>
			<li><?php echo $no_color;?></li>
		<?php foreach ($results as $colour):?>
			<li>
				<ol>
					<li class="cell" style="width:300px;"><?php echo $colour['from'];?></li>
					<li class="cell" style="width:300px;"><?php echo $colour['to'];?></li>
				</ol>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>