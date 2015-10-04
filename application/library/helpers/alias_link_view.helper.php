<?php
	class AliasLinkViewHelper
	{
		public static function getLink($controller_name, DynamicModel $model)
		{
			$subdomain = ($model && $model->city) ? $model->city->alias : '';

			if($model && $model->city && ($model->city->name == 'Москва'))
			{
				$subdomain = '';
			}

			if($subdomain)
				$subdomain .= '.';

			if ($model->alias)
			{
				return 'http://'.$subdomain.LinkHelper::getDomain().'/'.$controller_name.'/'.$model->alias;
			}
			else
			{
				return 'http://'.$subdomain.LinkHelper::getDomain().'/'.$controller_name.'/get?id='.$model->getId();
			}
		}
	}