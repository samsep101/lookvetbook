<?php
	class FbAccountLikeManager extends ModelManager
	{
		protected $table_name = 'fb_account_like';
		protected $model_name = 'FbAccountLikeModel';

        /**
		 * return FbAccountLikeModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}