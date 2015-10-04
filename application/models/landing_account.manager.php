<?php
	class LandingAccountManager extends ModelManager
	{
		protected $table_name = 'landing_account';
		protected $model_name = 'LandingAccountModel';

        /**
		 * return LandingAccountModel
		 */
		public function getOneByHash($hash)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `hash` = "' . mysql_real_escape_string($hash) . '"';

			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function setIsConfirmedByAccountId($account_id)
		{
			$this->orm_model->update(array('is_confirmed' => 1), 'account_id = "' . (int)$account_id . '"');
		}

	}