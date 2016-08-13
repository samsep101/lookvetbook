<?php
	class ClinicValidator extends ModelValidator
	{
		public function validate(DynamicModel $model)
		{
			/**
			 * @var ClinicModel $model
			 */
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($model->date_contract, $validation_rules->get('date'), $model);
			$validator->validate($model->name, $validation_rules->get('required'), $model);

            //if (!$model->getId() && $model->latitude && $model->longitude)
			//    $validator->validate($model->latitude.'|'.$model->longitude, $validation_rules->get('two_coordinates'), $model);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}