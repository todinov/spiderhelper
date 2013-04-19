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
				<table class="jl">
					<?php foreach ($value as $job): ?>
					<tr>
						<td><?php echo $job['spider'];?></td>
						<td><?php echo $job['id'];?></td>
						<td><?php echo date('d.m.Y H:i:s',strtotime($job['start_time']));?></td>
						<td><?php echo date('d.m.Y H:i:s',strtotime($job['end_time']));?></td>
						<td><a href="<?php echo $logurl.$job['spider'].'/'.$job['id'].'.log';?>" target="_blank">log</a></td>
						<td><a href="<?php echo $jsonurl.$job['spider'].'/'.$job['id'].'.jl';?>" target="_blank">json</a></td>
						<?php if ($name == 'running'):?>
							<td><a href="javascript:void(0);" class="stopcrawl" data-jobid="<?php echo $job['id'];?>">stop</a></td>
						<?php endif;?>
					</tr>
					<?php endforeach;?>
				</table>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>

<script type="text/javascript">
$('.stopcrawl').click(function () {
	jobid = $(this).attr('data-jobid');
	$.ajax({
		type: "POST",
		url: "index.php?scrapydstatus/stopcrawl",
		data: { "jobid": jobid }
	}).done(function( res ) {
		alert(res);
	});
});
</script>

<?php include 'footer.tpl.php';?>