<?=$this->block('blocks/help-search-block');?>

<div id="content" style="padding:20px; width:95%">
						<h1 class="title">Результаты</h1>
						<div class="searchResult">
						
						<?if (isset($_REQUEST['query'])){?>
							<p style="font-size:11px;">Для: "<?=strip_tags($query);?>".
							
							<?if (!$err){?>
								<ul class="searchResultUl">
									<?$i=1;?>
									<?foreach($results as $key=>$result){?>
									<li>
										<b><?=$i++;?>.</b> <?=$result['search_content'];?>
										<br />
										<span class="searchSmall"><a href="<?=$result['url'];?>"><?=$_SERVER['SERVER_NAME']?><?=$result['url'];?></a></span>
									</li>	
									<?}?>
								</ul>
							<?}elseif ($err == 'wrong_query'){?>
								<br />Строка запроса должна содержать не менее 3 символов, не считая пробелы.</p>
							<?}elseif ($err == 'not_found'){?>
								<br />К сожалению, по Вашему запросу ничего не найдено.</p>
							<?}?>
						<?}?>
							</ul>
						</div>
</div>

