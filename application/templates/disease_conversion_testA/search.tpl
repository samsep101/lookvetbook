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
            <?php if (isset($_GET['disease_query'])):?>
            <div class="illness-catalog-error">По запросу мы не нашли заболевания. Уточни название в каталоге.</div>
            <?php endif?>
            <?php
                $start = 0;
                $end = count($divided_diseases);
                $line_num = 0;

            while($start < $end)
            {
                $line_end = ( ($start+3) < $end ) ? ($start+3) : $end;
            ?>
            <div class="row deseaseRow">
            <?php
                for($j = $start; $j <= $line_end; $j++): ?>
                            <?php if(!isset($divided_diseases[$j])) break;?>
                            <div class="list-item">
                                <h2><?php echo $divided_diseases[$j]['letter']; ?></h2>
                                <ul>
                                    <?php foreach($divided_diseases[$j]['result'] as $disease): ?>
                                        <li><a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>"><?php echo $disease->title; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (count($divided_diseases[$j]['result']) > 5): ?>
                                    <a class="adjust" href="javascript:void(0);">Еще заболевания</a>
                                <?php endif; ?>
                            </div>
                <?php endfor; ?>
            </div>
            <?php $this->block('disease/blocks/adv_search_line',['line_num' => $line_num]); ?>
            <?php
            $start = $j;
            $line_num++;
            } ?>
        </div>
    </div>