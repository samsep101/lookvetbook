<?php
	class PrimaryDoctors {

		/**
		 * @var MemcacheFacade
		 */
		protected $memcache;

		private $max_usage_count = 3;

		private $primary_count = 4;

		public function __construct()
		{
			$this->memcache = MemcacheFacadeFactory::getService();
		}

		public function setMaxUsageCount($max_usage_count)
		{
			$this->max_usage_count = $max_usage_count;
		}

		public function getIdsByDoctorSearchParams(DoctorSearchParams $params)
		{
			$key = 'primary_doctors:'.$params->getParamsHash();

			$data = $this->memcache->get($key);

			if(!$data || !isset($data['usage_count']) || ($data['usage_count'] >= $this->max_usage_count))
			{
				$tags = array(
					'doctor',
					'doctor:list',
				);

				$search_params = clone $params;
				$search_params->has_avatar = 1;
				$search_params->page = null;
				$search_params->by_page = null;

				/**
				 * @var DoctorManager $doctor_manager
				 */
				$doctor_manager = ModelManagerFactory::getByName('doctor');
				$doctors = $doctor_manager->getListByDoctorSearchParams($search_params);

				$primary_count = $this->primary_count;

				if (count($doctors) < $primary_count)
				{
					$primary_count = count($doctors);
				}

				$indexes = $this->getRandomIndexes($primary_count, count($doctors) - 1);

				$data = array();

				if ($indexes)
				{
					foreach($indexes as $index)
					{
						$data[] = $doctors[$index]->getId();
					}
				}

				$cache_data = array(
					'data' => $data,
					'usage_count' => 1
				);

				$this->memcache->set($key, $cache_data, $tags);

				return $data;
			} else {
				$data['usage_count']++;
				$this->memcache->update($key, $data);
				return $data['data'];
			}
		}

		private function getRandomIndexes($count, $max_value)
		{
			$arr = range(0, $max_value)	;
			shuffle($arr);

			$result = array();
			for ($i = 0; $i < $count; $i++)
			{
				$result[] = $arr[$i];
			}

			return $result;
		}
	}