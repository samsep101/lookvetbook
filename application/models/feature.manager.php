<?php
	class FeatureManager extends ModelManager
	{
		protected $table_name = 'feature';
		protected $model_name = 'FeatureModel';

		public function getActiveListByClinicId($clinic_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                FROM feature
                WHERE (
                    SELECT COUNT(*)
                    FROM feature_to_clinic
                    WHERE feature_id = feature.id
                    AND clinic_id = ' . $clinic_id . '
                )>0';

			$data = $db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * return FeatureModel
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();

			return $this->initOne($data);
		}
	}