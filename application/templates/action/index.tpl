<div class="actionsList">
    <?php foreach($actions as $e):?>
    <a href="<?=$e->getLink()?>"><?=$e->name?></a>
    <?php endforeach; ?>
</div>