<?php
	/**
	 * @var View $this
	 * @var string $csrf
	 *
	 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$this->company?></title>
    <?php echo $this->block('admin/blocks/head');?>
	<script type="text/javascript">
		SessionInfo.csrf = '<?php echo (isset($csrf)) ? $csrf : ''; ?>';
	</script>
</head>
<body class="admin">

<div class="clear"></div>

<?if(Acl::userId()){?>
    <img src="/media/images/eyes_owl_line_tr.png" id="logo">
    <p align="right"><a href="/admin/security/logout"><b>Выйти</b></a></p>


<table style="width:100%" cellpadding="0" cellspacing="0">
    <tr>
        <td id="leftMenu" style="width:240px" valign="top">
            <div id="tabs" class="tabs">
                <?php $this->block('admin/blocks/left-menu'); ?>
            </div>
        </td>

        <td valign="top" id="rightContent">
<?}?>
            <?php echo $this->content(); ?>
<?if(Acl::userId()){?>
        </td>
    </tr>
</table>
<?}?>
<div class="clear"></div>
</body>
</html>


