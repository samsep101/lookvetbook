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
            <div>
                <? if ($current_subrubric) : ?>
                    <h1><?=$current_subrubric->title?></h1>
                    <p><?=$current_subrubric->content?></p>
                <?endif?>
            </div>
            <div>
                <? if ($materials) : ?>
                    <? foreach ($materials as $material) : ?>
                        <h1></a><a href="/help/material?id=<?=$material->id?>"><?=$material->title?></a></h1><br>
                    <? endforeach ?>
                <?endif?>
            </div>
        </div>
    </div>
</div>

<br>