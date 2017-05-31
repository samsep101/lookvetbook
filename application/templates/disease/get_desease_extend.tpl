<div class="other-links">
	<ul>
		<?php if ($disease->sources) { ?>
			<li> <span>Источники</span>
				<div class="drop-box">
					<?php $disease->sources = preg_replace('/<br \/>/','',$disease->sources);?>
					<?php $disease->sources = preg_replace('/<br\/>/','',$disease->sources);?>
					<p><?php echo html_entity_decode($disease->sources,ENT_COMPAT,'UTF-8'); ?></p>
				</div>
			</li>
		<?php } ?>
		<?php if ($disease->extended_content) { ?>
			<li> <span>Расширенное описание</span>
				<div class="drop-box">
					<?php $disease->extended_content = preg_replace('/<br \/>/','',$disease->extended_content);?>
					<?php $disease->extended_content = preg_replace('/<br\/>/','',$disease->extended_content);?>
					<p><?php echo html_entity_decode($disease->extended_content,ENT_COMPAT,'UTF-8'); ?></p>
				</div>
			</li>
		<?php } ?>
	</ul>
</div>
                                <?php if($disease->alias=='diareya') {
                                ?>
                                <div class="other-links">
                                    <div class='like_p' style="font-size: 14px;">
                                    Материал опубликован при поддержке <a href='https://docdoc.ru' target='_blank'>DocDoc.ru</a> - сервиса по поиску <a href='https://docdoc.ru/doctor/gastroenterolog' target='_blank'>врачей</a>
                                    </div>
                                </div>
                                <?php
                                } else if ($disease->alias=='gripp') {
                                 ?>
                                <div class="other-links">
                                    <div class='like_p' style="font-size: 14px;">
                                    Материал опубликован при поддержке <a href='https://docdoc.ru' target='_blank'>DocDoc.ru</a> - сервиса по поиску врачей в <a href='https://docdoc.ru' target='_blank'>Москве</a> и <a href='https://spb.docdoc.ru' target='_blank'>Санкт-Петербурге</a>.
                                    </div>
                                </div>
                                 <?php
                                 }
                                 ?>
