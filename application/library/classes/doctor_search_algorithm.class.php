<?php

class DoctorSearchAlgorithm
{
  private $manager;
  private $good_search_flag = true;
  private $primary_doctors_ids = null;

  private $next_page_flag = true;

  private $use_discard_criteria_algorithm = true;

  private $primary_doctors_algorithm = null;

  /**
   * @var DoctorSearchParams
   */
  private $search_params = null;

  public function __construct()
  {
    $this->manager = new DoctorManager();
    $this->primary_doctors_algorithm = new PrimaryDoctors();
    $this->primary_doctors_algorithm->setMaxUsageCount(SettingsManager::get('doctor_search_primary_show_count', 3));
  }

  public function setUseDiscardCriteriaAlgorithm($value)
  {
    $this->use_discard_criteria_algorithm = (bool)$value;
  }

  public function getGoodSearchFlag()
  {
    return $this->good_search_flag;
  }

  public function getPrimaryDoctorsIds()
  {
    if (!isset($this->primary_doctors_ids)) {
      $this->primary_doctors_ids = $this->primary_doctors_algorithm->getIdsByDoctorSearchParams($this->search_params);
    }
    return $this->primary_doctors_ids;
  }

  public function getNextPageFlag()
  {
    return $this->next_page_flag;
  }

  public function search(DoctorSearchParams $doctor_search_params)
  {
    $this->search_params = $doctor_search_params;
    $this->search_params->is_active = 1;
    $this->search_params->is_has_active_clinic = true;
    $this->search_params->sort_by = 'balls';
    
    // Получаем приоритетных врачей
    // (тех, которые отображаются на первых четырех позициях)
    // на страницах с геопоиском это не работает
    if (($doctor_search_params->page == 1)
      && !$doctor_search_params->geo_point
      && !$doctor_search_params->doctor_name
      && !$doctor_search_params->street_id
      && !$doctor_search_params->region_id
      && !$doctor_search_params->district_id
      && !$doctor_search_params->metro_station_id
    ) {
      $doctor_search_params->primary_doctors_ids = $this->getPrimaryDoctorsIds();
    } else {
      $this->primary_doctors_ids = $doctor_search_params->primary_doctors_ids;
    }

    if ($doctor_search_params->specialty_id || $doctor_search_params->purpose_of_visit_id) {
      $specialty_manager = new SpecialtyManager();

      $suitable_specialties = $specialty_manager->getSuitableListBySpecialtyIdAndPurposeOfVisitId($doctor_search_params->specialty_id, $doctor_search_params->purpose_of_visit_id);

      if ($suitable_specialties) {
        foreach ($suitable_specialties as $suitable_specialty) {
          $doctor_search_params->suitable_specialties_ids[] = $suitable_specialty->getId();
        }
      }
    }

    $doctor_search_params->get_extra_item = true;

    $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);

//			if (!$doctors)
//			{
//				$doctors = $this->removeCriteriaAlgorithm($doctor_search_params);
//			}
    if (count($doctors) == ($doctor_search_params->by_page + 1)) {
      $this->next_page_flag = true;
      unset($doctors[$doctor_search_params->by_page]);
    } else {
      $this->next_page_flag = false;
    }

    return $doctors;
  }

  private function addDoctorSearchQueryTask(DoctorSearchParams $doctor_search_params)
  {
    // Добавление задания для построения полного списка врачей по данному доктору
    $hash = $doctor_search_params->getParamsHash();

    $doctor_search_query_task_manager = new DoctorSearchQueryTaskManager();

    if (!$doctor_search_query_task_manager->getOneByHash($hash)) {
      $doctor_search_query_task_model = new DoctorSearchQueryTaskModel();
      $doctor_search_query_task_model->sql = serialize($doctor_search_params);
      $doctor_search_query_task_model->task_status_id = TaskStatusModel::IN_QUEUE;
      $doctor_search_query_task_model->hash = $hash;
      $doctor_search_query_task_model->save();
    }
  }

  private function removeCriteriaAlgorithm(DoctorSearchParams $doctor_search_params)
  {
    $doctors = array();

    while (!$doctors) {
      if ($doctor_search_params->geo_point && $doctor_search_params->distance < 64000) {
        $doctor_search_params->distance *= 2;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->distance >= 64000) {
        $doctor_search_params->geo_point = null;
        $doctor_search_params->distance = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        $doctor_search_params->metro_station_name = null;
        continue;
      }

      $this->good_search_flag = false;

      if (!$this->use_discard_criteria_algorithm)
        break;

      if ($doctor_search_params->district_id) {
        $doctor_search_params->district_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->region_id) {
        $doctor_search_params->region_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->street_id) {
        $doctor_search_params->street_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->visit_type == 'home') {
        $doctor_search_params->visit_type = 'clinic';
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_sex_id) {
        $doctor_search_params->doctor_sex_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_name) {
        $doctor_search_params->doctor_name = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->weekend_time) {
        $doctor_search_params->weekend_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->morning_time) {
        $doctor_search_params->morning_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->evening_time) {
        $doctor_search_params->evening_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_type) {
        $doctor_search_params->doctor_type = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->purpose_of_visit_id) {
        $doctor_search_params->purpose_of_visit_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->specialty_id) {
        $doctor_search_params->specialty_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }


      break;
    }

    return $doctors;
  }
}