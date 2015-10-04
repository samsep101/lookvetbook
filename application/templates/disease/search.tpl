	<script type="text/javascript">
        $(document).ready(function () {
            var controller = new DiseaseSearchPageController('<?php echo $label_for_counters; ?>');
            controller.init();
        });
    </script>

    <?php
        $divided_diseases = DiseaseCatalogViewHelper::divideByLetters($diseases);
    ?>
    <div class="inner">
        <div class="search-block flo">
            <form action="/disease/searchResults" method="GET">
                <input type="text" class="txt illness-search-input" autocomplete="off" name="disease_query" placeholder="Найти заболевание" />
                <input type="submit" class="btn-1 illness-search-submit" data-action-for-counters="disease" value="Искать" />
                <ul class="drop-menu">
                </ul>
            </form>
        </div>
        <div class="ilness-list flo">
            <?if (isset($_GET['disease_query'])):?>
            <div class="illness-catalog-error">По запросу мы не нашли заболевания. Уточни название в каталоге.</div>
            <?endif?>
            <?php for($j = 0; $j <= 3; $j++): ?>
                <div class="list-col">
                    <?php for ($i=0; $i < count($divided_diseases); $i += 4): ?>
                        <?php if(!isset($divided_diseases[$i+$j])) break;?>
                        <div class="list-item">
                            <h2><?php echo $divided_diseases[$i+$j]['letter']; ?></h2>
                            <ul>
                                <?php foreach($divided_diseases[$i+$j]['result'] as $disease): ?>
                                    <li><a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>"><?php echo $disease->title; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($divided_diseases[$i+$j]['result']) > 5): ?>
                                <a class="adjust" href="javascript:void(0);">Еще заболевания</a>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
