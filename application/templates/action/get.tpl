<div class="content">
	<div class="inner">
		<h1 style="text-align:left;padding-left: 80px;"><?=$action->name?></h1>
		<br>
		<div class="about-ilness-content">
			<div class="main-cont">
				<div class="actionsList content">
                    <?php if ($action->get_image_full_width()){?>
					<div style="text-align:center;">
                        <img src="<?=$action->get_image_full_width()->crop(600, 200)->path?>" width="600" height="200">
					</div>
                    <?php } ?>
			        <div class="actionText section">
			        	<?=$action->info?>
						<div style="text-align:center;">
		                    <div style="float: left; width: 250px">
                                <a class="btn-double-floor" href="#" onclick="learnController.showForm(0,0,0)">
                                    <span style="padding:10px 20px" class="just-text">Узнать подробности</span>
                                </a>
                            </div>
                            <div style="float: left;  font-size: 20px;  width: 547px;  margin-top: 11px;">
                                <a style="color: #000;" href="tel:+7(495)215-09-27">Или позвоните нам и мы все расскажем +7 (495) 215-09-27</a>
                            </div>
		                    <br>
		                    <br>
		                    <br>
		                    <br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>