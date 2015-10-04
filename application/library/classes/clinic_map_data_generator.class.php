<?php
    class ClinicMapDataGenerator
    {
        public function generate(ClinicSearchParams $clinic_search_params)
        {
            $clinic_search_params = clone $clinic_search_params;
            $clinic_search_params->page = null;
            $clinic_search_params->by_page = null;

            $hash = $clinic_search_params->getParamsHash();

            if (!file_exists('/media/map/' . $hash . '.js')) {
                /**
                 * @var ClinicManager $clinic_manager
                 */
                $clinic_manager = ModelManagerFactory::getByName('clinic');
                $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

                $str = '';

                /**
                 * @var ClinicModel[] $clinics
                 */
                if ($clinics)
                    foreach ($clinics as $clinic) {
                        $str .= $clinic->getId() . ':' .
                            $clinic->address . ':' . $clinic->name . ':' .
                            $clinic->latitude . ':' . $clinic->longitude . ':4|';
                    }


                $str = trim($str, '|');

                file_put_contents('./media/map/' . $hash . '.js', $str);
            }

            return $hash;
        }
    }