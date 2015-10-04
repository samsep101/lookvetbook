<?php
	class FbAccountGroupManager extends ModelManager
	{
		protected $table_name = 'fb_account_group';
		protected $model_name = 'FbAccountGroupModel';


        /**
		 * return FbAccountGroupModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}