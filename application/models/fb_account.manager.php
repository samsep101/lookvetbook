<?php
	class FbAccountManager extends ModelManager
	{
		protected $table_name = 'fb_account';
		protected $model_name = 'FbAccountModel';

        /**
		 * return FbAccountModel
		 */
		public function getOneByUid($uid)
		{
			$data = $this->orm_model->select()->where('uid = ?', mysql_real_escape_string($uid))->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

        /**
		 * return FbAccountModel
		 */
		public function getOneByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', (int)$account_id)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}
	}