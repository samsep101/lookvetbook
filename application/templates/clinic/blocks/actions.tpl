<div class="actionsList">
    <?php foreach($actions as $e):?>
    <div class="oneAction">
        <div class="actionImage">
	        <div class="actionName"><a href="<?=$e->getLink()?>"><?=$e->name?></a></div>
        	<img src="http://imgn.omskpress.ru/news/b_1f15268647a952f98d860099904f6006.jpg" width="200" height="200">
    	</div>
        <!-- <div class="actionText"><?=$e->info?></div> -->
    </div>
    <?php endforeach; ?>
</div>