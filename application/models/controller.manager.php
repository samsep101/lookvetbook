<?php
	class ControllerManager extends ModelManager
	{
		protected $table_name = 'controller';
		protected $model_name = 'ControllerModel';

        /**
		 * return ControllerModel
		 */
		public function getOneByCode($controllerCode)
		{
			$db = $this->db;

			$sql = 'SELECT *
                    FROM controller
                    WHERE `code` = "' . mysql_real_escape_string($controllerCode) . '"';

			$data = $db->query($sql);
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = 1')->fetchAll();
			return (isset($data)) ? $this->initList($data) : array();
		}
	}