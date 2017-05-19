<div class="content read">
<div class="section">
<!--	<div class="info-box doing-box not-hide"> -->
		<h3 class="attention-block">Что делать при <span><?php echo trim($disease->prepositional_name);?>?</span></h3>
<!--
                <ol class="todo-list">
			<li>
-->
				<?php if (!Acc::isAuthed()) { ?>
					<?php include('get_desease_doct_help_noauth2.tpl');?>
				<?php } else { ?>
					<?php include('get_desease_doct_help_auth2.tpl');?>

				<?php } ?>
<!--
                        </li>
		</ol>
	</div>
-->
</div>
</div>