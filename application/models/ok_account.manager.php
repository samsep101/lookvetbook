<?php
	class OkAccountManager extends ModelManager
	{
		protected $table_name = 'ok_account';
		protected $model_name = 'OkAccountModel';

        /**
		 * return OkAccountModel
		 */
		public function getOneByUid($uid)
		{
			$data = $this->orm_model->select()->where('uid = ?', mysql_real_escape_string($uid))->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

        /**
		 * return OkAccountModel
		 */
		public function getOneByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', (int)$account_id)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}
	}