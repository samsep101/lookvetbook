<?php
	class FbAccountTelevisionManager extends ModelManager
	{
		protected $table_name = 'fb_account_television';
		protected $model_name = 'FbAccountTelevisionModel';

        /**
		 * return FbAccountTelevisionModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}