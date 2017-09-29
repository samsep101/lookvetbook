<div class="actionsList">
    <?php foreach($actions as $e):?>
    <?php if ($e->name){ ?>
	<div align="center">
        <div class="oneAction" style="width: 600px; margin-left: 34px;">
            <div class="actionImage">
                <div class="actionName" style="width: 600px"><a href="<?=$e->getLink()?>"><?=$e->name?></a></div>
				<?php if ($e->get_image_full_width()){?>
                <img src="<?=$e->get_image_full_width()->crop(600, 200)->path?>" width="600" height="200">
				<?php } ?>
            </div>
            <!-- <div class="actionText"><?=$e->info?></div> -->
        </div>
	</div>
    <?php } ?>
    <?php endforeach; ?>
</div>