<?php
	class GeoBaseManager extends ModelManager
	{
		protected $table_name = 'geo_base';
		protected $model_name = 'GeoBaseModel';

    /**
		 * return GeoBaseModel
		 */
		public function getOneByLongIp($long_ip)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                FROM ' . $this->table_name . '
                WHERE long_ip1 <= "' . mysql_real_escape_string($long_ip) . '"
                AND long_ip2 >= "' . mysql_real_escape_string($long_ip) . '"';

			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}
	}
