<div id="main">
	<div class="content">
		<ul>
		<?php foreach ($files as $path=>$data):?>
			<li>
				<div class="name">
					<img src="images/sitemap.png" alt="site" class="siteicon"/> <?php echo $path;?> 
				</div>
				<?php foreach ($data as $file): ?>
				<ol class="hidden jl">
					<li class="first"><?php echo $file['date'];?></li>
					
					<li class="path">
						<a href="open.php?file=<?php echo $file['path'];?>">
							<?php echo $file['name'];?>
						</a>
					</li>

					<li>
						<img src="images/json.png" class="btn" title="<?php echo $file['path'];?>"/>
					</li>

					<li>
						<img src="images/testparser.png" class="btn" title="<?php echo $file['testparsecmd'];?>"/>
					</li>

					<li>
						<a href="analyze.php?file=<?php echo $file['path'];?>">
							<img src="images/category.png" alt="analyze" title="analyze"/>
						</a>
					</li>

					<li class="size"><?php echo $file['size'];?></li>

				</ol>
			<?php endforeach;?>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>