<?php include 'header.tpl.php';?>
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
				<ol class="jl">
					<li class="first"><?php echo $job['spider'];?></li>
					<li><?php echo $job['id'];?></li>
					<li><?php echo date('d.m.Y H:i:s',strtotime($job['start_time']));?></li>
					<li><?php echo date('d.m.Y H:i:s',strtotime($job['end_time']));?></li>
					<li><a href="<?php echo $logurl.$job['spider'].'/'.$job['id'].'.log';?>" target="_blank">log</a></li>
					<li><a href="<?php echo $jsonurl.$job['spider'].'/'.$job['id'].'.jl';?>" target="_blank">json</a></li>
					<li><a href="#">stop</a></li>
				</ol>
			<?php endforeach;?>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>
<?php include 'footer.tpl.php';?>