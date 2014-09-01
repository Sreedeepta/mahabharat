<?php  
$films = Norm::factory('Episode')->find(array('status' => 1))->toArray();
?>
<h2>List <?php echo f('controller.name') ?></h2>

<div class="command-bar">
    <a href="<?php echo f('controller.url', '/null/create') ?>"><span class="fa fa-plus"></span> Add <?php echo f('controller.name') ?></a>
</div>

<div class="wrapper">
	<ul class="listview text">
		<li class="list-group-container">
			<ul class="list-group">
				<?php foreach ($films as $key => $film): ?>
				<li class="plain">
					<a href="<?php echo URL::Site('film/'.$film['$id']) ?>">
						<div class="span-2">
							<span class="fa fa-film">&nbsp;&nbsp;&nbsp;</span><span>Episode <?php echo $film['episode'] ?></span>
						</div>
					</a>
				</li>
				<?php endforeach ?>
			</ul>
		</li>
	</ul>
</div>


