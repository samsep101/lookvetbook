<?php
	class ServicesCategoriesManager extends ModelManager
	{
		protected $table_name = 'services_categories';
		protected $model_name = 'ServicesCategoriesModel';

        /**
		 * return ControllerModel
		 */
		public function getOneBySlug($slug)
		{
			$sql = 'SELECT *
                    FROM services_categories
                    WHERE `slug` = "' . $this->db->escape($slug) . '"';

			$data = $this->db->query($sql);
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}
	}