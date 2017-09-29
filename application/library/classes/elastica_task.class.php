<?php

class ElasticaTask
{
	public static function indexClinic($clinic_id)
	{
		/**
		 * @var ClinicManager $clinic_manager
		 */
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
		$doctor_manager = ModelManagerFactory::getByName('doctor');
		$doctor = $doctor_manager->getOneById($doctor_id);

		$index_control = new ElasticSearchDoctorIndexControl();
		$index_control->addDocument($doctor);
	}
}