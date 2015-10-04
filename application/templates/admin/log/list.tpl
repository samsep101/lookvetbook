<div id="generatorData">

    <p class="actionBar">
        <?php if(!$ajax): ?>
            <span class="button">
                <a class="taskIndexLink" href="/<?=$this->dataModel->getModelName();?>"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
                <a href="<?=ADMIN_FOLDER.'/'.$this->dataModel->getModelName();?>"><?=$dataModel->getListTitle()?></a>
            </span>
        <?php endif; ?>

        <?php if ($acl->hasRights($dataModel->getModelName(),'add')): ?>
            <span class="button">
                <img class="cursorPointer" src="/media/admin/icons/badge-circle-plus-24-ns.png" align="absmiddle" />
                <a href="<?=ADMIN_FOLDER.$addUrl;?>?destination=<?php echo $destination; ?>&<?php echo $params; ?>"><?=$addTitle;?></a>
            </span>
        <?php endif; ?>
    </p>

    <form id="export_log_form" action="/admin/index/log/exportCSVWithAccountActivity">
        <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
        <input type="submit" value="Выгрузить в *.csv" style="float: right">
    </form>


    <div style="clear:both"></div>

    <?if(Acl::userGrant($dataModel->getModelName().'_xls')){?>
        <p class="controls">
            <img src="/mhadmin/media/img/xls.gif" align="absmiddle" />&nbsp;<a href="/mhadmin/<?=$dataModel->getModelName()?>/xls/">Сохранить</a>
        </p>
    <?}?>


    <?if(count($data)){?>

    <? $filters = $dataModel->getFilters();?>

    <?/* Автофильтры */?>
    <?/*if(count($filters)){?>
        <form method="POST" id="filterForm">
            <table cellpadding="2" cellspacing="2">
                <tr>
                    <td><img src="/mhadmin/media/img/filter.png" /></td>

                <?foreach ($filters as $filter => $title){?>
                        <?$fileterValues = array();?>
                        <?foreach ($data as $row){?>
                            <? $dataModel->setValues($row);?>
                            <?foreach ($dataModel->getListFields() as $field){?>
                                <?if($field->getFieldName() == $filter){?>
                                    <?if($row[$filter] != 0){?>
                                        <?$fileterValues[$row[$filter]] = $field->getViewValue()?>
                                    <?}?>
                                <?}?>
                            <?}?>
                        <?}?>

                        <?if(count($fileterValues) > 0){?>
                            <td>
                            <?if((int) $_GET[$filter] and count($fileterValues)==1){?>
                                <?foreach($fileterValues as $filterKey=>$filterVal){?>
                                    <span class="grey"><?=$title?>:</span> <?=$filterVal;?> <img src="/mhadmin/media/img/icons/badge-circle-cross-16-ns.png" class="cursorPointer" align="absmiddle" onclick="filterDel('<?=$filter?>')" />
                                    <input type="hidden" name="<?=$filter?>" value="<?=$filterKey?>" />
                                <?}?>
                            <?} else {?>
                                <span class="grey"><?=$title?>:</span> <select style="width:150px;" name="<?=$filter?>" onchange="$('#filterForm').submit();">
                                    <option value=''>...</option>
                                    <?foreach($fileterValues as $filterKey=>$filterVal){?>
                                        <?if((int) $filterKey){?>
                                            <?if((int) $_GET[$filter] == (int) $filterKey){?>
                                            <option value="<?=$filterKey?>" selected><?=$filterVal?></option>
                                            <?} else {?>
                                            <option value="<?=$filterKey?>"><?=$filterVal?></option>
                                            <?}?>
                                        <?}?>
                                    <?}?>
                                </select>
                            <?}?>
                            </td>
                        <?}?>
                <?}?>
                </tr>
            </table>
            <br />
        </form>
        <script>
                $(document).ready(function() {
                    $('#filterForm').ajaxForm({
                        'success': function(data) {
                            url = '';
                            $('#filterForm').find('select, input').each(function(){
                                if($(this).val()){
                                    url = url+'&'+$(this).attr('name')+'='+$(this).val();
                                }
                            });
                            ajax('/<?=$dataModel->getModelName();?>/?ajax=1'+url,'<?=$dataModel->getModelName();?>Content');
                        }
                    });
                });

                function filterDel(name){
                    url = '';
                    $('#filterForm').find('select, input').each(function(){
                        if($(this).val() && $(this).attr('name') != name){
                            url = url+'&'+$(this).attr('name')+'='+$(this).val();
                        }
                    });
                    ajax('/<?php echo ADMIN_FOLDER; ?>/<?php echo $dataModel->getModelName();?>/?ajax=1'+url,'<?=$dataModel->getModelName();?>Content');
                }
        </script>
    <?}*/?>

    <?if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?endif?>

<form id="<?=$dataModel->getModelName();?>form" action="/admin/<?=$dataModel->getModelName();?>/delete_list/?destination=<?php echo $destination; ?>" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эти записи?');return false;">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

<table class="list">
	<thead>
	<tr>

            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                <th width="30px;"><input class="status_check" type="checkbox" onclick="checked_all();"/></th>
            <?php endif; ?>


		<?foreach ($fieldTitles as $fieldTitle){?>
			<th><?=$fieldTitle;?></th>
		<?}?>

		<?if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
			<th>&nbsp;</th>
		<?}?>

		<?if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
			<th>&nbsp;</th>
		<?}?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($data as $row): ?>
		<? $dataModel->setValues($row);?>
		<tr id="key[<?=$row->getId()?>][]">
                <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                    <td><input class="input_check"  type="checkbox" name="delete_list[]" value="<?=$row->getId();?>"/></td>
                <?php endif; ?>

			<?php foreach ($dataModel->getListFields() as $field): ?>
                <?php if ($field->fieldName == $hide_field) continue; ?>
				<td><?=$field->getViewValue($row->{$field->fieldName});?></td>
			<?php endforeach; ?>

			<?if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
				<td width="25px;" style="text-align:right;"><a href="<?=ADMIN_FOLDER.'/'.$dataModel->getModelName();?>/edit/?<?=$indexField;?>=<?=$row->getId();?>&destination=<?php echo $destination; ?>"><img title="Редактировать" border="0" src="/media/admin/icons/pencil-16-ns.png"/></a></td>
			<?} ?>

			<?if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
				<td width="25px;" style="text-align:right;"><a href="<?php echo ADMIN_FOLDER; ?>/<?=$dataModel->getModelName();?>/delete/?<?=$indexField;?>=<?=$row->getId();?>&destination=<?php echo $destination; ?>" onclick="return confirm('Вы действительно хотите удалить эту запись?');"><img title="Удалить" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png"/></a></td>
			<?}?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<br />

        <?if ($acl->hasRights($dataModel->getModelName(),'delete_list')){?>
            <div class="clear"><!-- --></div>
            <input type="button" value="Удалить выделенные" id="submit_action" onclick="$('#<?=$dataModel->getModelName();?>form').submit()" />
            <br />
        <?}?>
</form>
<br />
<?php if($this->pages_num>1): ?>
	<div class="pager">
		<?php echo PagingViewHelper::paging(ADMIN_FOLDER.'/'.$dataModel->getModelName()."/?page=:page:",$this->pages_num,$this->page,'generatorData')?>
	</div>
<?php endif; ?>

<?} else {?>

    <?if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?endif?>

	<p>Пока нет данных.</p>
<?}?>

</div>
