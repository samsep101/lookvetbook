<ul>
    <?if(Acl::userId()){?>
        <?$controllers = Acl::getUserControllers()?>
        <?foreach($controllers as $key => $value){?>
            <li class="menuCat"><a href="/admin/<?=$key?>" class="<?=$key?> nl" ><span><?=$value?></span></a></li>
        <?}?>
		<?php if (Acl::userRole() == RoleModel::ESHOP_MANAGER): ?>
			<li class="menuCat"><a href="/admin/statistic/shop_manager" class="nl" ><span>Отчет по контент-менеджерам</span></a></li>
		<?php endif; ?>
    <?}?>
</ul>