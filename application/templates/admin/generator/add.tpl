<p class="actionBar">
		<span class="button">
			<a href="<?=ADMIN_FOLDER . '/' . $this->dataModel->getModelName();?>"><img class="cursorPointer"
                                                                                       src="/media/admin/icons/clipboard-audit-24-ns.png"
                                                                                       align="absmiddle"
                                                                                       border="0"/></a>
			<a href="<?=ADMIN_FOLDER . '/' . $this->dataModel->getModelName();?>"><?=$this->dataModel->getListTitle()?></a>
		</span>
    <? if ($acl->hasRights($this->dataModel->getModelName(), 'add')) { ?>
    <span class="button">
			<img class="cursorPointer taskIndexLink" src="/media/admin/icons/badge-circle-plus-24-ns.png"
                 align="absmiddle"/>
			<a href="<?=$this->dataModel->getModelName();?>/add/"><?=$title;?></a>
		</span>
    <? }?>
</p>
<div style="clear:both"></div>

<?php ini_set("memory_limit", "256M");?>

<form action="<?php echo ADMIN_FOLDER; ?>/<?=$this->dataModel->getModelName();?>/add/?destination=<?php echo $destination; ?>" method="POST"
      enctype="multipart/form-data">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

    <?=$this->dataModel->getAddTooltip()?>

    <?php $this->block('admin/blocks/validation-errors'); ?>
    <?foreach ($tabs as $tabId=> $tabName) { ?>

    <div id="tab-<?=$tabId;?>" class="generatorEditDiv">
        <h4><?=$tabName?></h4>
        <br/>

        <div>
            <table class="generatorTable" cellpadding="3" cellspacing="3">
                <?foreach ($tabFields[$tabName] as $fieldName=> $field) { ?>
                <? if ($field->hasLayout) { ?>
                    <tr>
                        <?if ($this->dataModel->checkUserFilter($fieldName) && Acl::userGrant($this->dataModel->getModelName() . '_list_my')) { ?>
                        <input name="form[<?=$fieldName?>]" type="hidden" value="<?=Acl::userId();?>"/>
                        <? } else { ?>
                        <td class="label"><?=$this->dataModel->getFieldLabel($fieldName);?>:</td>
                        <td><?=$field->getFormValue($model->$fieldName);?></td>
                        <? }?>
                    </tr>
                    <? } ?>
                <? }?>
            </table>
        </div>
    </div>
    <? }?>
    </div>
    <br/>

    <p><input type="button" onclick="$($($(this).parent()).parent()).submit()" value="Создать" id="submit_action"></p>
</form>