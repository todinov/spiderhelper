<?php include 'header.tpl.php';?>
<div id="main">
	<div class="content">
		<ul>
			<li>
				<ol>
					<li class="category header">Category Structs</li> 
					<li class="info header"><?php echo $results['categorycount'];?></li>
				</ol>
			</li>
			<li>
				<ol>
					<li class="category header">Products</li>
					<li class="info header"><?php echo $results['productcount'];?></li>
				</ol>
			</li>
			<li>
				<ol>
					<li class="category header">Categories</li> 
					<li class="info header"><?php echo count($results['categories']);?></li>
				</ol>
			</li>

			<li>
				<ol>
					<li class="category header">Category name</li>
					<li class="prodcount header">Products</li>
					<li class="prodcount header">Pages</li>
				</ol>
			</li>
		<?php foreach ($results['categories'] as $category):?>
			<li>
				<ol>
					<li class="category"><?php echo $category['name'];?></li>
					<li class="prodcount"><?php echo $category['products'];?></li>
					<li class="prodcount"><?php echo $category['pages'];?></li>
				</ol>
			</li>
		<?php endforeach;?>
			<li>
				<ol>
					<li class="category header">All</li>
					<li class="prodcount header"><?php echo $allprod;?></li>
					<li class="prodcount header"><?php echo $allpage;?></li>
				</ol>
			</li>
		</ul>
	</div>
</div>
<?php include 'footer.tpl.php';?>