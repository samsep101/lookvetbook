<?php
	class FbAccountMusicManager extends ModelManager
	{
		protected $table_name = 'fb_account_music';
		protected $model_name = 'FbAccountMusicModel';

        /**
		 * return FbAccountMusicModel[]
		 */
		public function getListByFbAccountId($fb_account_id){
			$data = $this->orm_model->select()->where('fb_account_id = ?', $fb_account_id)->fetchAll();
			return $this->initList($data);
		}

	}