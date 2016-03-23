<?php
	class DoctorMapDataGenerator
	{
		public function generate(DoctorSearchParams $doctor_search_params)
		{
			$doctor_search_params = clone $doctor_search_params;

			$doctor_search_params->page = 1;
			$doctor_search_params->by_page = 1000;

            $hasFilename = './media/map/' . $doctor_search_params->getParamsHash() . '.js';

			if(file_exists($hasFilename) && filesize($hasFilename) > 0)
				return $doctor_search_params->getParamsHash();

            /**
             * @var DoctorManager $doctor_manager
             */
            $doctor_manager = ModelManagerFactory::getByName('doctor');

			$doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

            $clinics = array();
            if($doctor_search_params->city_id)
            {
                /**
                 * @var ClinicManager $clinic_manager
                 */
                $clinic_manager = ModelManagerFactory::getByName('clinic');
                $temp = $clinic_manager->getListByCityId($doctor_search_params->city_id);

                foreach($temp as $v)
                {
                    $clinics[$v->getId()]  = $v;
                }
            }

			$str = '';
			if ($doctors)
				foreach ($doctors as $doctor) {
					if ($doctor->clinics) {
						foreach ($doctor->clinics as $clinic) {
                            if(!isset($clinics[$clinic->getId()]))
                            {
                                continue;
                            }
							$str .= $doctor->getId() . '-' . $clinic->getId() . ':' .
								$clinic->address . ':' . $doctor->full_name . ':' .
								$clinic->latitude . ':' . $clinic->longitude . ':4|';
						}
					}
				}

			$str = trim($str, '|');

			file_put_contents('./media/map/' . $doctor_search_params->getParamsHash() . '.js', $str);

			return $doctor_search_params->getParamsHash();
		}
	}