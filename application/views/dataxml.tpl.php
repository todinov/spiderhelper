<?php include 'header.tpl.php';?>
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
					
					<li class="path" style="width:430px">
						<a href="openxml.php?path=<?php echo $file['path'];?>">
							<?php echo $file['name'];?>
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
<?php include 'footer.tpl.php';?>