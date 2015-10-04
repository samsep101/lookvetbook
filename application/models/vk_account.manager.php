<?php
	class VkAccountManager extends ModelManager
	{
		protected $table_name = 'vk_account';
		protected $model_name = 'VkAccountModel';

        /**
		 * return VkAccountModel
		 */
		public function getOneByUid($uid)
		{
			$data = $this->orm_model->select()->where('uid = ?', mysql_real_escape_string($uid))->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

        /**
		 * return VkAccountModel
		 */
		public function getOneByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', (int)$account_id)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}
	}