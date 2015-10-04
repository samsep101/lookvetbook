<?=$this->block('blocks/help-search-block');?>

<div class="help-tabs">

    <div class="rybriki">
        <? if ($rubrics) : ?>
            <? foreach ($rubrics as $rubric) : ?>
                <a href="/help/rubric?id=<?=$rubric->id?>"><?=$rubric->title?></a>
            <? endforeach ?>
        <?endif?>
    </div>
    <div class="rybrika-content">
        <div class="podrybriki-left">
            <? if ($subrubrics) : ?>
                <? foreach ($subrubrics as $subrubric) : ?>
                    <a href="/help/subrubric?id=<?=$subrubric->id?>"><?=$subrubric->title?></a><br>
                <? endforeach ?>
            <?endif?>
        </div>
        <div class="materials-right">
            <? if ($current_material) : ?>
                <h1><?=$current_material->title?></h1>
                <p><?=$current_material->content?></p>
            <?endif?>
        </div>
    </div>
</div>

<br>