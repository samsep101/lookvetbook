<?php
	class DiseaseTagManager extends ModelManager
	{
		protected $table_name = 'disease_tag';
		protected $model_name = 'DiseaseTagModel';

        /**
		 * return DiseaseTagModel[]
		 */
		public function getListByTag($disease_query)
		{
			$sql = 'SELECT *
                FROM disease_tag
                WHERE tag LIKE  "%' . $this->db->escape($disease_query) . '%"';

			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initList($data) : array();
		}

		public function getIdByTag($tag)
		{
			$sql = 'SELECT id
                    FROM ' . $this->table_name . '
                    WHERE tag = "' . $this->db->escape($tag) . '"';

			$data = $this->db->query($sql);

			return (isset($data[0]['id'])) ? $data[0]['id'] : false;
		}

        /**
		 * return DiseaseTagModel[]
		 */
		public function getListByDiseaseId($disease_id)
		{
			$sql = 'SELECT dt.* FROM `disease_tag` dt
                    INNER JOIN disease_to_disease_tag d2dt ON d2dt.disease_tag_id = dt.id
                    WHERE d2dt.disease_id = ' . (int)$disease_id . '
                    ORDER BY dt.tag';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}