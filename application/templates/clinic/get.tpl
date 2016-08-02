<?php
	/**
	 * @var ClinicModel $clinic
	 * @var ClinicReviewModel[] $clinic_reviews
	 * @var ClinicReviewModel[] $all_reviews
	 * @var SpecialtyModel[] $specialties
	 * @var PurposeOfVisitModel[] $purposes
	 * @var EqualClinicModel[] $equal_clinics
	 */
?>

<?php
	if (isset($_COOKIE['already_registred_account']))
		$already_registred_account = 1;
	else
		$already_registred_account = 0;
?>

<script type="text/javascript">
	$(document).ready(function(){
		var clinic_controller = new ClinicPageController('<?php echo $clinic->id?>', '<?php echo $clinic->latitude; ?>', '<?php echo $clinic->longitude; ?>', <?php echo (isset($landing_page) && !Acc::isAuthed()) ? false : true; ?>, <?php echo $already_registred_account; ?>,"<?php echo $_SERVER['REQUEST_URI']; ?>");
		$('.btn-bookmark').click(function(){
			clinic_controller.block_title = 'для добавления в закладки';
			clinic_controller.block_over_textbox = 'Получите доступ ко всем возможностям <?php echo SITE_NAME; ?>!';
		});
		clinic_controller.city_id = <?php echo $clinic->city_id; ?>;

		<?php if(!empty($main_specialty)) { ?>
			clinic_controller.main_specialty = <?php echo $main_specialty->getId(); ?>;
		<?php } ?>

		clinic_controller.init();
	});
</script>

	<?php $this->block('blocks/top_number'); ?>
	<div class="inner flo">
		<?php if (SiteUriHelper::refererFromClinicPage()): ?>
			<a class="back-to-search-link" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">&larr; Назад к результатам поиска</a>
		<?php endif; ?>
		<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
			<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem">
				&nbsp;
				&nbsp;
				<a itemprop="item" href="/">
					<span itemprop="name">Главная</span></a> -&nbsp;
				<meta itemprop="position" content="1" />
			</li>
			<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="/clinic">
					<span itemprop="name">Клиники</span></a> -&nbsp;
				<meta itemprop="position" content="2" />
			</li>
			<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                    <span itemprop="name"><?=$clinic->name?></span></span>
				<meta itemprop="position" content="3" />
			</li>
		</ol>

		<div class="clinic-landing" itemscope itemtype="http://schema.org/Organization">
			<meta itemprop="url" content="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">
			<div class="main-box">
				<div class="head-info flo">
					<div class="rating" itemscope itemtype="http://schema.org/AggregateRating">
						<meta itemprop="name" content="<?php echo $clinic->name; ?>"/>

						<?php echo RateViewHelper::view($clinic->rate, 0, $clinic->is_best); ?>

						<?php if($clinic->is_best) { ?>
							<div class="is_best_recomm">Рекомендуем</div>
						<?php } ?>

						<?php if (count($clinic->reviews)): ?>
							<div class="comments-count">
								<a href="#reviews">
									<span itemprop="reviewCount">
										<?php echo StringHelper::getCorrectSuffixForReview(count($clinic->reviews));?>
									</span>
								</a>
							</div>
						<?php endif; ?>

					</div>
					<h1 itemprop="name"><?php echo $clinic->name; ?></h1>
					<p>
						<?php if ($clinic->metro_stations): ?>
							<?php foreach ($clinic->metro_stations as $metro_station) {?>
								<?php if ($metro_station->metro_branch): ?>
									<?php echo MetroBranchIconViewHelper::getImage($metro_station->metro_branch); ?>
								<?php endif; ?>
								<?php echo $metro_station->name;?><br>
							<?php } ?>
						<?php endif; ?>

						<span itemscope itemprop="geo" itemtype="http://schema.org/GeoCoordinates" style="display: block; position: absolute; width: 1px; height: 1px; overflow: hidden;">
							<meta itemprop="latitude" content="<?php echo $clinic->latitude ?>" />
							<meta itemprop="longitude" content="<?php echo $clinic->longitude ?>" />
						</span>
						<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="streetAddress"><?php echo $clinic->address; ?></span>
						</span>
					</p>
				</div>
				<div class="vis-block">
					<div class="nav"> <!--<span class="inf">Первый визит: <strong>бесплатно</strong></span>-->
						<ul>
							<li class="tab-foto"><a href="#tabs-1"><i></i>Фото</a></li>
							<li class="tab-map"><a href="#tabs-2" class="map-link"><i></i>Карта</a></li>
						</ul>
					</div>
					<div class="content">
						<div id="tabs-1" class="section">
							<?php $this->block('clinic/blocks/photo_carousel'); ?>
						</div>
						<div id="tabs-2" class="section">
							<div class="map-block" id="map-block" style="width: 658px; height: 360px">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="side-box">
				<div class="btns">
					<a href="#divider-shadow" onclick="recordController.showForm(0,<?php echo $clinic->id?>,0)" class="btn-find-doctor-2"><span class="txt appoint">Записаться на прием</span></a>

					<a class="btn-bookmark btn-bookmark-big click_btn_bookmark">
						<script>
							<?php if ($clinic->my_clinic): ?>
								$('.btn-bookmark.btn-bookmark-big').addClass('btn-bookmark-added');
								$('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i><span class="txt txt-added">В закладках</span>');
							<?php else: ?>
								$('.btn-bookmark.btn-bookmark-big').removeClass('btn-bookmark-added');
								$('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i><span class="txt">Добавить в закладки</span>');
							<?php endif; ?>
						</script>
					</a>
				</div>

				<div class="time_clinic">
					<?php if($clinic->is_day_and_night):?>
						<p class="h-txt">Часы работы:</p>
						<p class="time-line time-line-center">круглосуточная</p>
					<?php else:?>
						<?php echo ScheduleViewHelper::view($clinic); ?>
					<?php endif; ?>

					<?php if(!empty($current_account) && $current_account->is_call_centre_operator && $clinic->direct_phone):?>
                    <p class="our_time">
                        <span class="h-txt">Прямой номер:</span><br/>
                        <span class="info-phone" itemprop="telephone">
							<?=$clinic->direct_phone;?>
						</span>
                    </p>
                    <?php else:?>
					<p class="our_time">
						<span class="h-txt">Запись на прием:</span><br/>
						<span class="info-phone" itemprop="telephone">
							<?php
								if($clinic->top_phone) {
									$phone = $clinic->top_phone;
								} else {
									$phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
								}
								echo $phone;
							?>
						</span>
					</p>
                    <?php endif; ?>
				</div>
				<?php $this->block('clinic/blocks/call-centre-operator-hint'); ?>

				<?php if (!empty($current_account) && $current_account->is_call_centre_operator && $clinic->not_work) { ?>
				<div class="not-work-message">
					НЕ РАБОТАЕМ
				</div>
				<?php } ?>
			</div>
		</div>
		
		<?php if ($clinic->alias == 'dobromed-m-bratislavskaya' and 0) { ?>
			<div class="dobromed-banner">
				<table>
					<tr>
						<td rowspan="3" class="left-img"><img width="165px" src="/media/images/dobromed-img1.png" /></td>
						<td colspan="3" class="title">Скидка 20 % на рентген в "Добромед" на Братиславской</td>
					</tr>
					<tr>
						<td class="number">1</td>
						<td>Запишись сейчас к <span class="service-link" data-specialty-id="26"><a class="like_service_ul" href="#our-doctors">рентгенологу</a></span> с<br/>помощью <b>Look<span style="color: #e23a79;">Med</span>Book</b></td>
						<td align="right" class="appoint">
							<a onclick=" if (window.is_test == 1) $(this).attr('href','javascript:void(0)');
							else {
								send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=zapis&bt=55&pz=0&rnd=![rnd]')
								var block = new RecordToTheDoctorBlockController(123799, $(this), null);
								block.action_for_counters = 'button';
								block.init();
							}" href="#record-to-the-doctor-popup-123799" class="btn-appoint">Записаться сейчас</a>
							<div class="arrow"><img src="/media/images/dobromed-arrow.png" /></div>
						</td>
					</tr>
					<tr>
						<td class="number">2</td>
						<td>Получи <b>скидку 20% на все услуги<br/>рентгенолога</b> при посещении врача</td>
						<td align="center" class="td-phone">
							<div class="small-text">или по телефону</div>
							<div class="info-phone">8 (495) 215-09-07</div>
						</td>
					</tr>
				</table>
			</div>
		<?php } ?>
	</div>

	<div class="full-width">
		<div id="clinic-description" style="position:absolute; top: -30px;"></div>
		<div class="inner flo">

			<div class="info-col col-about">
				<i class="icon"></i>
				<div class="about-cont">
					<?php if(!empty($clinic->name)) { ?>
					<h3>О клинике: <?php echo $clinic->name; ?></h3>
					<?php } else { ?>
					<h3>О клинике</h3>
					<?php } ?>
					<div id="about-clinic-content">
						<?php echo $clinic->about; ?>
					</div>
				</div>
				<a class="more-link">Узнать больше</a>
			</div>

			<?php if ($clinic->specializations): ?>
				<div class="info-col col-services">
					<i class="icon"></i>
					<h3>Виды услуг</h3>
					<ul>
						<?php foreach ($clinic->specializations as $specialization): ?>
							<?php if($specialization->getMainSpecialty($clinic->getId())): ?>
								<li class="service-link" data-specialty-id="<?php echo $specialization->main_specialty->getId(); ?>">
									<a class="like_service_ul" href="#our-doctors"><?php echo $specialization->name; ?></a>
								</li>
							<?php else: ?>
								<li class="service-link"><?php echo $specialization->name; ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php if (count($clinic->specializations)>14): ?>
						<a class="more-link" href="javascript:void(0);">Узнать больше</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($clinic->features):?>
				<div class="info-col col-comfort">
					<i class="icon"></i>
					<h3>Удобства</h3>
					<ul>
						<?php foreach ($clinic->features as $feature) :?>
							<li><?php echo $feature->name; ?></li>
						<?php endforeach?>
					</ul>
					<?php if (count($clinic->features)>14): ?>
						<a class="more-link">Узнать больше</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>


	<div class="inner-2">
		<?php if ($clinic_reviews):?>
			<div id="reviews">
				<div class="heading-line">
					<h2>
						<span>
							ОТЗЫВЫ О КЛИНИКЕ:
							<br />
							<?php if(!empty($clinic->name)) { ?>
								<?php echo mb_strtoupper($clinic->name, 'utf-8'); ?>
							<?php } ?>
						</span>
					</h2>
				</div>
				<div class="item-row flo">

				<?php
					$this->reviews = $clinic_reviews;
					$this->isClinic = 1;
					$this->itemreviewedName = $clinic->name;
					$this->block('/blocks/reviews-list');
				?>

				</div>

				<?php if ($all_reviews && $all_reviews > 4) { ?>
					<div id="view_more_reviews">
						<a href="javascript:void(0)" class="view-more" id="more_reviews"><i></i>Показать ещё 10 отзывов</a>
					</div>
				<?php } ?>

		<?php endif; ?>
		<div class="divider-shadow" id="divider-shadow"></div>

		<div id="our-doctors">
			<div class="heading-line">
				<h2><span>ВРАЧИ КЛИНИКИ</span></h2>
				<h2><span>Выберите специалиста и запишитесь на прием:</span></h2>
			</div>
			<div class="select-area flo" id="doctor_search_form">
				<div class="sel-box">
					<select data-placeholder="Специальность врача" name="specialty_id" class="chzn-select" style="width:308px;">
						<?php $this->specialties = $specialties; ?>
						<?php $this->block('blocks/specialties_options'); ?>
					</select>
				</div>
				<div class="sel-box" id="purpose_of_visit_block">
					<?php $this->purposes = $purposes; ?>
					<?php $this->select_style = 'width: 308px'; ?>
					<?php $this->block('ajax/purposes_select'); ?>
				</div>
				<div class="sel-box">
					<select data-placeholder="Время визита" name="time_of_visit" class="chzn-select" style="width:308px;">
						<option></option>
						<option value="any">в любое время</option>
						<option value="weekend">в выходные дни</option>
						<option value="evening">вечером</option>
						<option value="leave_house">выезд на дом</option>
						<option value="morning">утром</option>
					</select>
				</div>
			</div>

			<div class="item-row flo">
				<div id="doctor-container">
				</div>
			</div>

			<div id="view_more_doctors">
				<a class="view-more" id="more_doctors" href="javascript:void(0)"><i></i>Показать ещё</a>
			</div>
			<?php if(isset($equal_clinics) && $equal_clinics): ?>
				<?php $this->equal_elements_type = 'clinic'; ?>
				<?php $this->clinic = $clinic; ?>
				<?php $this->equal_clinics = $equal_clinics; ?>
				<?php $this->block('blocks/equal_elements'); ?>
			<?php endif; ?>
		</div>
        <?php if ($actions): ?>
            <?php $this->block('clinic/blocks/actions'); ?>
        <?php endif; ?>
	</div>



<script>
	$(function() {
		<?php if (count($clinic->images)>5): ?>
			$('.connected-carousels .next-navigation').removeClass('inactive');
		<?php endif; ?>

		$( ".vis-block" ).tabs();
	});

	<?php if (!$clinic->images): ?>
		$(document).ready(function (){
			$('.tab-foto').css('display', 'none');
			$('.nav .tab-map').addClass('ui-state-active');
			$('#tabs-1').hide();
			$('#tabs-2').show();
		});
	<?php endif; ?>

</script>