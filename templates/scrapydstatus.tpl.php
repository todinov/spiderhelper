<div id="main">
	<div class="content">
		<ul>
		<?php foreach ($data as $name=>$value):?>
			<li>
				<div class="name">
					<img src="images/sitemap.png" alt="site" class="siteicon"/>
					<?php echo $name;?>
					<div class="siteid" style="text-align:right;"><?php echo count($value);?></div>
				</div>
				<?php foreach ($value as $job): ?>
				<ol class="hidden jl">
					<li class="first"><?php echo $job['spider'];?></li>
					<li><?php echo $job['id'];?></li>
					<li><?php echo $job['start_time'];?></li>
					<li><?php echo $job['end_time'];?></li>
				</ol>
			<?php endforeach;?>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>