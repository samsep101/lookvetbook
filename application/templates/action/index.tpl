<div class="content">
	<div class="inner">
		<h1 style="text-align:center;">Акции</h1>
		<div class="actionsList">
		    <?php foreach($actions as $e):?>
	        <div class="oneAction">
		        <div class="actionImage">
			        <div class="actionName"><a href="<?=$e->getLink()?>"><?=$e->name?></a></div>
		        	<img src="<?=$e->image->resize(200, 200)->path?>" width="200" height="200">
	        	</div>
		        <!-- <div class="actionText"><?=$e->info?></div> -->
		    </div>
		    <?php endforeach; ?>
		</div>
	</div>
</div>