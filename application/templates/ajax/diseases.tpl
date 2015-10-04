<?php if ($diseases): ?>
    <?php foreach($diseases as $disease): ?>
        <a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>"><li><?php echo $disease->title; ?></li></a>
    <?php endforeach; ?>
<?php endif; ?>