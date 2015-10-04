<script>
    $(function() {
        $(document).ready(function(){
            var help_controller = new HelpPageController();
            help_controller.init();
        });
    });
</script>

<div class="inner-3">
    <h1 class="help-title">Помощь</h1>
    <div class="search-block s-block-var flo">
        <form action="/help/searchResults" method="GET">
            <input type="text" class="txt" autocomplete="off" name="help_query" placeholder="Введите вопрос" />
            <input type="submit" class="btn-1" value="Искать" />
            <ul class="drop-menu help-drop-menu">
            </ul>
        </form>
    </div>
    <?if (isset($_GET['help_query'])):?>
        <div class="illness-catalog-error">По Вашему запросу ничего не найдено.</div>
    <?endif?>
    <div class="help-page flo" id="tabs">
        <div class="nav" style="display:none">
            <ul>
                <?foreach ($rubrics as $rubric):?>
                    <li id="<?=$rubric->id?>"><a href="#tabs-<?=$rubric->id?>"><i></i><?=$rubric->title?></a></li>
                <?endforeach?>
            </ul>
        </div>

        <?foreach ($rubrics as $rubric):?>

            <div id="tabs-<?=$rubric->id?>">

                <ul class="sub-menu">
                    <?foreach ($subrubrics as $subrubric):?>
                        <?if ($subrubric->help_rubric_id == $rubric->id):?>
                            <li><a href="#sub-<?=$rubric->id?>-<?=$subrubric->id?>"><?=$subrubric->title?></a></li>
                        <?endif?>
                    <?endforeach?>
                </ul>
                <div class="help-cont">
                    <?foreach ($subrubrics as $subrubric):?>
                        <?if ($subrubric->help_rubric_id == $rubric->id):?>
                            <h2 id="sub-<?=$rubric->id?>-<?=$subrubric->id?>"><?=$subrubric->title?></h2>
                            <ul class="help-list">
                                <?foreach ($materials as $material):?>
                                    <?if ($material->help_subrubric_id == $subrubric->id):?>
                                        <li> <a class="help-target" href="#"><?=$material->title?></a>
                                            <div class="drop">
                                                <p><?=$material->content?></p>
                                            </div>
                                        </li>
                                    <?endif?>
                                <?endforeach?>
                            </ul>
                        <?endif?>
                    <?endforeach?>
                </div>
            </div>

        <?endforeach?>

    </div>
</div>