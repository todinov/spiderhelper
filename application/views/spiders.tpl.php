<?php include 'header.tpl.php';?>
<div id="main">
	<div class="content">
		<ul>
			<li>
				<ol>
					<li class="category header"><a href="spiders.php">Spider</a></li>
					<li class="prodcount header"><a href="spiders.php?sort=class">Class</a></li>
				</ol>
			</li>
			<?php foreach ($files as $name => $file):?>
			<li>
				<ol>
					<li class="category"><?php echo $file['name'];?></li>
					<li class="prodcount"><?php echo $file['class'];?></li>
				</ol>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>
<?php include 'footer.tpl.php';?>