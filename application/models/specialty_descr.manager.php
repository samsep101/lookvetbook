<?php
	class SpecialtyDescrManager extends AliasManager
	{
		protected $table_name = 'specialty_descr';
		protected $model_name = 'SpecialtyDescrModel';

		public function __construct()
		{
			parent::__construct();
		}

    	/**
		 * return string
		 */
		public function getSpecialtyPageDescrBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT *
                    FROM specialty_descr
                    WHERE specialty_id = "' . $this->db->escape($specialty_id) . '";';
			$data = $this->db->query($sql);
			return (isset($data[0])) ? $data[0]['specialty_page_descr'] : null;
		}

		/**
		 * return string
		 */
		public function getClinicPageDescrBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT *
                    FROM specialty_descr
                    WHERE specialty_id = "' . $this->db->escape($specialty_id) . '";';
			$data = $this->db->query($sql);
			return (isset($data[0])) ? $data[0]['clinic_page_descr'] : null;
		}
	}
