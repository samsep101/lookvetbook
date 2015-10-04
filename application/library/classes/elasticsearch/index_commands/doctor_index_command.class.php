<?php
	class DoctorIndexCommand extends ModelIndexCommand
	{
		public function __construct()
		{
			$this->model_manager = new SearchIndexDoctorManager();
			$this->index_manager = new ElasticSearchDoctorIndexControl();
		}
	}