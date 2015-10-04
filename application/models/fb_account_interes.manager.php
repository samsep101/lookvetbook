<?php
	class FbAccountInteresManager extends ModelManager
	{
		protected $table_name = 'fb_account_interes';
		protected $model_name = 'FbAccountInteresModel';

        /**
		 * return FbAccountInteresModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}