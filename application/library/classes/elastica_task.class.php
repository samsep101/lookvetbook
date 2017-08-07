<?php

class ElasticaTask
{
	public static function indexClinic($clinic_id)
	{
		/**
		 * @var ClinicManager $clinic_manager
		 */
        if( ! self::elasticIsActive()) return true;
		$clinic_manager = ModelManagerFactory::getByName('clinic');
		$clinic = $clinic_manager->getOneById($clinic_id);

		$index_control = new ElasticSearchClinicIndexControl();
		$index_control->addDocument($clinic);
	}

	public static function indexDoctor($doctor_id)
	{
		/**
		 * @var DoctorManager $doctor_manager
		 */
        if( ! self::elasticIsActive()) return true;
		$doctor_manager = ModelManagerFactory::getByName('doctor');
		$doctor = $doctor_manager->getOneById($doctor_id);

		$index_control = new ElasticSearchDoctorIndexControl();
		$index_control->addDocument($doctor);
	}


    public static function elasticIsActive(){

        ob_start();
        $ch =  curl_init("localhost:9200");
        $res = curl_exec($ch);
        curl_close($ch);
        ob_get_clean();

        return ($res === false);


    }

}