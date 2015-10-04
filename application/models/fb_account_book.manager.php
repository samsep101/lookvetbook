<?php
	class FbAccountBookManager extends ModelManager
	{
		protected $table_name = 'fb_account_book';
		protected $model_name = 'FbAccountBookModel';

  		/**
		 * return FbAccountBookModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}