<?php
	/**
	 * @var int $counter_number
	 */
?>
<?php
	if (isset($_COOKIE['already_registred_account']))
		$already_registred_account = 1;
	else
		$already_registred_account = 0;
?>

	<script>
		$(function() {
			<?php if (isset($disease_tabs_flags)): ?>
			var first_tab = 0;
			first_tab = '<?php echo $card; ?>';
			<?php endif; ?>

			$(document).ready(function(){
				var disease_controller = new DiseasePageController('<?php echo $disease->id?>',<?php echo (!Acc::isAuthed()) ? 1 : 0; ?>, <?php echo $already_registred_account; ?>, first_tab, "<?php echo (isset($label_for_counters)) ? $label_for_counters : ''; ?>");
				disease_controller.changeSpecialtyBlock(first_tab);
				disease_controller.counter_number = <?php echo $counter_number;?>;
				disease_controller.disease_green_btn = <?php echo ($disease_green_btn)?1:0; ?>;
				disease_controller.init();
			});


			<?php if ($disease->my_disease) {?>
			$('.btn-bookmark-illness').addClass('btn-bookmark-added');
			$('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
			<?php } else if (Acc::isAuthed()) {?>
			$('.btn-bookmark-illness').removeClass('btn-bookmark-added');
			$('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
			<?php }?>

		});

	</script>
<?php if ($disease) :?>

	<div class="inner">
		<div class="about-ilness-content flo">
			<div class="main-column">
				<div class="main-cont flo">
				<div class="illness-header flo">
					<h1 id="disease-title" data-id="<?php echo $disease->id; ?>" data-title="<?php echo $disease->title; ?>"><?php echo $disease->title; ?></h1>
					<?php if (Acc::isAuthed()):?>
						<a class="btn-bookmark btn-bookmark-illness"></a>
					<?php endif?>
					<?php if ($disease->alt_names):?>
						<p class="another"><span>...или:</span>
							<?php echo $disease->alt_names_string; ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="illness-description">
					<?php $disease->content = preg_replace('/<br \/>/','',$disease->content);?>
					<?php $disease->content = preg_replace('/<br\/>/','',$disease->content);?>
					<div class="like_p"><?php echo html_entity_decode($disease->content,ENT_COMPAT,'UTF-8'); ?></div>
				</div>

				<?php if ($disease_blocks) :?>
					<div id="tabs">
						<div class="illness-nav-wrap">
							<div class="illness-nav" data-spy="affix">
								<div class="nav">
									<ul>
										<?php foreach ($disease_tabs_flags as $key=>$value):?>
											<?php if ($value):?>
												<li data-tab-name="<?php echo $key; ?>" class="tab-people tab-<?php echo $key; ?> <?php echo $key == $card ? 'ui-state-active' : ''; ?>"><a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>/<?php echo $key; ?><?php if ($disease_green_btn) echo "?dis=new3"; ?>" onclick="return false;" id="<?php echo $key; ?>"><?php echo DiseaseTabNameViewHelper::getNameByTabFlag($key); ?></a></li>
											<?php endif?>
										<?php endforeach?>
									</ul>
								</div>
								<div id="MyBigAjaxElement"></div>
								<!--add class "carousel" to sub-nav for sliding-->

								<?php foreach ($disease_tabs_flags as $key=>$value):?>

									<?php if ($value):?>
										<?php $display = ($key == $card); ?>

										<div class="sub-nav sub-nav-<?php echo $key; ?>" id="<?php echo $key; ?>" style="<?php echo $display ? 'display: block' : 'display:none'; ?>">
											<?php $sub_counter = 0; ?>
											<ul>
												<?php foreach ($disease_blocks as $block):?>

													<?php $field_name = $key.'_flag';?>
													<?php $field_anchor = 'b'.$block->id;?>

													<?php if ($block->$field_name == 1):?>
														<li><a data-section-id="<?php echo $block->disease_block_type_id?>" class="section-name content-active-<?php echo DiseaseBlockAliasViewHelper::getAlias($block->disease_block_type_id); ?>" data-section-name="content-active-<?php echo DiseaseBlockAliasViewHelper::getAlias($block->disease_block_type_id); ?>" data-t="<?php echo $field_anchor; ?>" href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>/<?php echo $key; ?>#<?php echo $field_anchor; ?>"><?php echo $block->disease_block_type->name; ?></a></li>
														<?php $sub_counter++;?>
													<?php endif?>
												<?php endforeach; ?>

											</ul>
											<?php if ($sub_counter>=7): ?>
												<script type="text/javascript">
													$(document).ready(function(){
														first_tab = '<?php echo $card; ?>';
														$('.sub-nav-<?php echo $key; ?>').addClass('carousel');
														$('.sub-nav-'+first_tab+'.carousel ul').each(function(){
															if($(this).find('li').length >= 7)
															{
																$(this).carouFredSel({
																	auto: false,
																	prev: '.prev',
																	next: '.next',
																	scroll:{items:1},
																	circular: false,
																	infinite:false
																});
															}
														});
													});
												</script>
												<a class="prev" href="#"></a> <a class="next" href="#"></a>
											<?php else: ?>
											<?php $sub_width = $sub_counter*101; ?>
												<script>

													$(document).ready(function(){
														$('.sub-nav-<?php echo $key; ?>').removeClass('carousel');
														$('#<?php echo $key; ?>.sub-nav ul').css('width', '<?php echo $sub_width; ?>');
													});
												</script>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								<?php endforeach?>

							</div>
						</div>

						<div class="content read">
							<?php $this->block('disease/blocks/disease_blocks_content'); ?>
						</div>
						<!--<div class="content">
							<div class="section section-help flo" style="background: url(/media/images/section_cont_shadow.png) no-repeat;"> <span class="info-title">Текст понятен?</span>
								<div class="info-buttons"></div>
								<div class="disease-only-info">
									<p class="info-line">ИНФОРМАЦИЯ ДЛЯ ОЗНАКОМЛЕНИЯ</p>
									<p class="info-line">Необходима консультация с врачом</p>
								</div>
							</div>
						</div>-->
					</div>
				<?php endif?>

				<?php if ($disease->extended_content || $disease->sources):?>
					<div class="other-links">
						<ul>
							<?php if ($disease->sources):?>
								<li> <span>Источники</span>
									<div class="drop-box">
										<?php $disease->sources = preg_replace('/<br \/>/','',$disease->sources);?>
										<?php $disease->sources = preg_replace('/<br\/>/','',$disease->sources);?>
										<p><?php echo html_entity_decode($disease->sources,ENT_COMPAT,'UTF-8'); ?></p>
									</div>
								</li>
							<?php endif?>
							<?php if ($disease->extended_content):?>
								<li> <span>Расширенное описание</span>
									<div class="drop-box">
										<?php $disease->extended_content = preg_replace('/<br \/>/','',$disease->extended_content);?>
										<?php $disease->extended_content = preg_replace('/<br\/>/','',$disease->extended_content);?>
										<p><?php echo html_entity_decode($disease->extended_content,ENT_COMPAT,'UTF-8'); ?></p>
									</div>
								</li>
							<?php endif?>
						</ul>
					</div>
				<?php endif?>

				<?php if ($disease_specialties):?>
					<div class="other-links bottom-what-to-do">
						<div class="info-box doing-box not-hide">
							<h3 class="attention-block">Что делать при <span><?php echo trim($disease->prepositional_name);?>?</span></h3>
							<ol class="todo-list">
								<li>
									<?php if (!Acc::isAuthed()): ?>
										<p>Врач
											<?php foreach ($disease_specialties as $specialty):?>
												<a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" data-text="<?php echo $specialty->plural_name; ?>" data-url="<?php echo $specialty->specialtyUrl ?>" href="<?php echo $specialty->specialtyUrl ?>"><?php echo $specialty->name; ?></a>
											<?php endforeach;?>
											поможет при лечении заболевания
										</p>
										<?php foreach ($disease_specialties as $specialty):?>
											<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-action-for-counters="find-doctor" data-category-counters="find-doctor" data-action="FindDocButton" data-position="Right" data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>" href="<?php echo $specialty->specialtyUrl ?>">
												<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
												<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
											</a>
										<?php endforeach;?>
									<?php else: ?>
										<p>Врач
											<?php foreach ($disease_specialties as $specialty):?>
												<a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>" data-text="<?php echo $specialty->plural_name; ?>"><?php echo $specialty->name; ?></a>
											<?php endforeach;?>
											поможет при лечении заболевания
										</p>
										<?php foreach ($disease_specialties as $specialty):?>
											<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="find-doctor" data-action="FindDocButton" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>">
												<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
												<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
											</a>
										<?php endforeach;?>
									<?php endif; ?>
								</li>
							</ol>
						</div>
					</div>
				<?php endif?>

				</div>
				<div id="cards-wrap"></div>
			</div>

			<div class="side-column" <?php /* ?>data-spy="affix" data-offset-top="100"<?php */ ?>>
				<?php if ($disease_specialties):?>
					<div class="info-box doing-box what-to-do">
						<h3>Что делать при <span><?php echo trim($disease->prepositional_name);?>?
							</span></h3>
						<ol class="todo-list">
							<li>
								<?php if (!Acc::isAuthed()): ?>
									<p>Врач
										<?php foreach ($disease_specialties as $specialty):?>
											<a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" data-text="<?php echo $specialty->plural_name; ?>" data-url="<?php echo $specialty->specialtyUrl ?>" href="<?php echo $specialty->specialtyUrl ?>"><?php echo $specialty->name; ?></a>
										<?php endforeach;?>
										поможет при лечении заболевания
									</p>
									<?php foreach ($disease_specialties as $specialty):?>
										<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-action-for-counters="find-doctor" data-category-counters="find-doctor" data-action="FindDocButton" data-position="Right" data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>" href="<?php echo $specialty->specialtyUrl ?>">
											<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
											<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
										</a>
									<?php endforeach;?>
								<?php else: ?>
									<p>Врач
										<?php foreach ($disease_specialties as $specialty):?>
											<a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>" data-text="<?php echo $specialty->plural_name; ?>"><?php echo $specialty->name; ?></a>
										<?php endforeach;?>
										поможет при лечении заболевания
									</p>
									<?php foreach ($disease_specialties as $specialty):?>
										<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="find-doctor" data-action="FindDocButton" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>">
											<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
											<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
										</a>
									<?php endforeach;?>
								<?php endif; ?>
							</li>
						</ol>
					</div>
				<?php endif?>

				<?php if ($disease->medicine):?>
					<div class="info-box">
						<h3>Медикаменты</h3>
						<div class="medicament-block">
							<p class="medicament-title"><?php echo $disease->medicine->name; ?></p>
							<a class="medicament-url" href="#">Список аптек</a>
							<?php if ($disease->medicine->image):?>
								<img src="<?php echo $disease->medicine->image->resize(234,200)->path; ?>" class="medicament-logo" alt="" />
							<?php  else:?>
								<img src="/media/images/no-photo.gif" class="medicament-logo" alt="" />
							<?php endif?>
							<div class="center-align"><a class="medicament-btn" href="#">Купить онлайн <?php echo $disease->medicine->price; ?> P</a></div>
							<p class="medicament-info">Перед приемом лекарства проконсультируйтесь у врача</p>
						</div>
					</div>
				<?php endif?>

				<div id="ban_wikimed" style="display:none;">
					<div>
						<p class="titled">Клиника WikiMed.</p>
						<p class="desc">Комплексное дуплексное сканирование сосудов (вен, артерий, брахиоцефальных и транскраниальных сосудов)</p>
						<p class="price">6000 руб. (<span class="old_price">8200 руб.</span>)</p>
					</div>
					<div>
						<p class="titled">Клиника WikiMed.</p>
						<p class="desc">Консультации врачей терапевта, гинеколога, уролога, флеболога с дуплексным сканирование вен. Консультация терапевта бесплатно.</p>
						7000 руб. (<span class="old_price">8300 руб.</span>)</p>
					</div>
					<div>
						<p class="titled">Клиника WikiMed.</p>
						<p class="desc">Полное ультразвуковое обследование женщин (органы брюшной полости, малого таза, мочевыделительной системы, молочных и щитовидной желез)</p>
						<p class="price">5600 руб. (<span class="old_price">8100 руб.</span>)</p>
					</div>
					<div>
						<p class="titled">Клиника WikiMed.</p>
						<p class="desc">Полное ультразвуковое обследование мужчин  (органы брюшной полости,  мошонки, мочевыделительной системы, предстательной и щитовидной желез)</p>
						<p class="price">4200 руб. (<span class="old_price">7700 руб.</span>)</p>
					</div>
				</div>

				<?php /* Яндекс.Директ */ ?>
				<div class="info-box disease-banner" style="padding:8px; background: none; box-shadow: none;">
					<div id="yandex_ad"></div>
					<script type="text/javascript">
						(function(w, d, n, s, t) {
							w[n] = w[n] || [];
							w[n].push(function() {
								Ya.Direct.insertInto(147148, "yandex_ad", {
									ad_format: "direct",
									type: "posterVertical",
									border_type: "block",
									limit: 4,
									title_font_size: 2,
									border_radius: true,
									links_underline: false,
									site_bg_color: "FFFFFF",
									border_color: "FFFFFF",
									title_color: "006699",
									url_color: "000000",
									text_color: "000000",
									hover_color: "6699CC",
									no_sitelinks: true
								});
							});
							t = d.getElementsByTagName("script")[0];
							s = d.createElement("script");
							s.src = "//an.yandex.ru/system/context.js";
							s.type = "text/javascript";
							s.async = true;
							t.parentNode.insertBefore(s, t);
						})(window, document, "yandex_context_callbacks");
					</script>
				</div>

				<script language="JavaScript">
					if( window.location.pathname == '/disease/varikoznaya-bolezn' || window.location.pathname == '/disease/hronicheskaya-venoznaya-nedostatochnost' ) {
						$('#yandex_ad').hide();
						$('#ban_wikimed').show();
						$('#ban_wikimed').click(function(){ window.location = '/clinic/klinika-WikiMed'; return false; });
					}
				</script>


				<div id="ban2"></div>

				<?php /* secondopinions */ ?>
				<div class="info-box disease-banner" style="padding:8px; background: none; box-shadow: none;">
				<script src="http://secondopinions.ru/lp7/js/banner_lite.js" type="text/javascript"></script>
				<div id="ban2"></div>

				<script>
					get_banner_lite("ban2");
				</script>

				<script>(function() {
						var _fbq = window._fbq || (window._fbq = []);
						if (!_fbq.loaded) {
							var fbds = document.createElement('script');
							fbds.async = true;
							fbds.src = '//connect.facebook.net/en_US/fbds.js';
							var s = document.getElementsByTagName('script')[0];
							s.parentNode.insertBefore(fbds, s);
							_fbq.loaded = true;
						}
						_fbq.push(['addPixelId', '664813706888979']);
					})();
					window._fbq = window._fbq || [];
					window._fbq.push(['track', 'PixelInitialized', {}]);
				</script>

				<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=664813706888979&amp;ev=PixelInitialized" /></noscript>

				<script type="text/javascript">
					/* <![CDATA[ */
					var google_conversion_id = 942698838;
					var google_custom_params = window.google_tag_params;
					var google_remarketing_only = true;
					/* ]]> */
				</script>

				<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>

				<noscript>
					<div style="display:inline;">
						<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/942698838/?value=0&amp;guid=ON&amp;script=0"/>
					</div>
				</noscript>

				<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=Yp1AxrLZjs4Fxs/Hq7TmIkXqx1XeesIngj17EAPDF/P8*EgPgFP1pfXX9HeVruJfjLqc9o7o5LRkSHXOZ86a2X4aeL9WydZ*Jui4rMvWSwvzs0UPx5HqGRjLf3v1t/aqx6XJqpDKCJCNEwJmm6PJwXS4hz5FYw3KZBp5*cA3iFo-';</script>
			</div>

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

<?php endif?>