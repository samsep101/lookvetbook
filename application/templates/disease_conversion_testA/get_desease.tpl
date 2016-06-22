
<div class="inner">
	<div class="about-ilness-content flo">
		<div class="main-column">
            <div class="desease-phone"><a href="tel:+7(<?=SITE_PHONE_CODE?>)<?=SITE_PHONE?>">Мы найдём Вам врача +7 (<?php echo SITE_PHONE_CODE; ?>) <?php echo SITE_PHONE; ?></a></div>
			<div class="main-cont flo">
				<div class="illness-header flo">
					<h1 id="disease-title" data-id="<?php echo $disease->id; ?>" data-title="<?php echo $disease->title; ?>"><?php echo $disease->title; ?></h1>
					<?php if (Acc::isAuthed()) { ?>
						<a class="btn-bookmark btn-bookmark-illness"></a>
					<?php } ?>
					<?php if ($disease->alt_names) { ?>
						<p class="another"><span>...или:</span>
							<?php echo $disease->alt_names_string; ?>
						</p>
					<?php } ?>
				</div>
				<div class="illness-description">
					<?php $disease->content = preg_replace('/<br \/>/','',$disease->content);?>
					<?php $disease->content = preg_replace('/<br\/>/','',$disease->content);?>
					<div class="like_p"><?php echo html_entity_decode($disease->content,ENT_COMPAT,'UTF-8'); ?></div>
				</div>

				<?php if(0 and !empty($disease->alias) and in_array($disease->alias, ['mezhpozvonochnaya-gryzha', 'osteohondroz-pozvonochnika'])) { ?>
					<div class="desease-banner-line">
						<div class="ortospy-banner"></div>
					</div>
				<?php } ?>

				<?php if ($disease_blocks) { include('get_desease_blocks.tpl'); } ?>

				<?php if ($disease->extended_content || $disease->sources) { include('get_desease_extend.tpl'); } ?>

				<?php if ($disease_specialties) { include('get_desease_special.tpl'); } ?>
			</div>
			<div id="cards-wrap"></div>
		</div>

		<div class="side-column" <?php /* ?>data-spy="affix" data-offset-top="100"<?php */ ?>>
			<?php if ($disease_specialties) { ?>
				<div class="info-box doing-box what-to-do">
					<h3>Что делать при <span><?php echo trim($disease->prepositional_name);?>?
						</span></h3>
					<ol class="todo-list">
						<li>
							<?php if (!Acc::isAuthed()) { ?>
								<?php include('get_desease_doct_help_noauth.tpl');?>
							<?php } else { ?>
								<?php include('get_desease_doct_help_auth.tpl');?>
							<?php } ?>
						</li>
					</ol>
				</div>
			<?php } ?>

			<?php if ($disease->medicine) { ?>
				<div class="info-box">
					<h3>Медикаменты</h3>
					<div class="medicament-block">
						<p class="medicament-title"><?php echo $disease->medicine->name; ?></p>
						<a class="medicament-url" href="#">Список аптек</a>
						<?php if ($disease->medicine->image) { ?>
							<img src="<?php echo $disease->medicine->image->resize(234,200)->path; ?>" class="medicament-logo" alt="" />
						<?php } else { ?>
							<img src="/media/images/no-photo.gif" class="medicament-logo" alt="" />
						<?php } ?>
						<div class="center-align"><a class="medicament-btn" href="#">Купить онлайн <?php echo $disease->medicine->price; ?> P</a></div>
						<p class="medicament-info">Перед приемом лекарства проконсультируйтесь у врача</p>
					</div>
				</div>
			<?php } ?>

			<?php if(!empty($disease->alias) and in_array($disease->alias, ['varikoznaya-bolezn', 'hronicheskaya-venoznaya-nedostatochnost'])) {
				include('wikimed.tpl');
			} ?>
			<?php include('get_desease_maxlab.tpl'); ?>
			<?php //include('get_desease_byt_molodoj_babkoj.tpl'); ?>
			<?php //include('get_desease_amazonaws.tpl'); ?>

			<?php if(!empty($disease->alias) and in_array($disease->alias, ['mezhpozvonochnaya-gryzha', 'osteohondroz-pozvonochnika'])) { ?>
                           <div class="desease-banner-line">
                             <div class="ortospy-banner2" onclick="window.location='/clinic/ortospayn';return false;"></div>
                           </div>
                        <?php } ?>

			<?php include('get_desease_yandexdir.tpl'); ?>
			<div id="ban2"></div>
			<?php include('get_desease_secondopinions.tpl');?>

		</div>
	</div>

	<div class="pediatr-banner-container"></div>
</div>

<div class="inner-2">
	<?php if ($disease_specialties) { ?>
		<div class="search-count-block <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
			<p class="count">
				Мы нашли для Вас <span class="count-digit"></span> <span class="count-doctor"></span> <span class="count-specialty"></span>
			</p>
			<div class="divider-shadow" id="divider-shadow"></div>
		</div>
	<?php } ?>
	<div id="our-doctors">
		<a class="view-more short-one"><i class="icon-loader"></i></a>
	</div>
</div>

