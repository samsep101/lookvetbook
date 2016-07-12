<?php

class ElasticaController extends BaseController
{
  /**
   * @var Elastica\Client
   */
  private $elastica_client = array();

  public function __construct()
  {
    $this->elastica_client = ElasticaFactory::getApi();
  }

  public function beforeAction()
  {
    set_time_limit(0);
    ModelManager::disableEntityMapGlobal();
  }

  public function createIndex()
  {
    $manager = new ElasticSearchIndexControl();
    $manager->createIndex(Register::get('ELASTIC_SEARCH_INDEX'));
    exit;//так было на сервере!!
  }

  public function applyMapping()
  {
    $elastic_doctor_manager = new ElasticSearchDoctorIndexControl();
    $elastic_doctor_manager->applyMapping();

    $elastic_clinic_manager = new ElasticSearchClinicIndexControl();
    $elastic_clinic_manager->applyMapping();

    $elastic_disease_manager = new ElasticSearchDiseaseIndexControl();
    $elastic_disease_manager->applyMapping();

    $elastic_product_manager = new ElasticSearchProductIndexControl();
    $elastic_product_manager->applyMapping();

    $elastic_laboratory_manager = new ElasticSearchLaboratoryIndexControl();
    $elastic_laboratory_manager->applyMapping();
  }

  public function addDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processNotIndexedDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->processNotIndexedDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->processNotIndexedDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->processNotIndexedDocuments();

    $laboratory_index_command = new LaboratoryIndexCommand();
    $laboratory_index_command->processNotIndexedDocuments();
  }

  public function addDoctorDocument()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processNotIndexedDocuments();
  }

  public function deleteDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processDeletedDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->processDeletedDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->processDeletedDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->processDeletedDocuments();

    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->processDeletedDocuments();
  }

  public function deleteAllDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->deletedAllDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->deletedAllDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->deletedAllDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->deletedAllDocuments();

    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->deletedAllDocuments();
  }

  public function deleteLaboratoryDocuments()
  {
    set_time_limit(0);
    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->deletedAllDocuments();
  }


  public function deleteProductDocuments()
  {
    set_time_limit(0);
    $product_index_command = new ProductIndexCommand();
    $product_index_command->deletedAllDocuments();
  }

  public function deleteDiseaseDocuments()
  {
    set_time_limit(0);
    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->deletedAllDocuments();
  }

  public function deleteClinicDocuments()
  {
    set_time_limit(0);
    ini_set('memory_limit', '512M');
    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->deletedAllDocuments();
  }

  public function deleteDoctorDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->deletedAllDocuments();
  }

  public function reIndexAll()
  {
    set_time_limit(0);

    ini_set('memory_limit', '512M');

    ModelManager::disableEntityMapGlobal();

    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->reIndexAll();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->reIndexAll();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->reIndexAll();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->reIndexAll();

    $laboratory_index_command = new LaboratoryIndexCommand();
    $laboratory_index_command->reIndexAll();
  }

  public function reindexDoctors()
  {
    ini_set('memory_limit', '512M');

    ModelManager::disableEntityMapGlobal();

    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->reIndexAll();
  }

  public function reindexDiseases()
  {
    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->reIndexAll();
  }

  public function reindexProducts()
  {
    $product_index_command = new ProductIndexCommand();
    $product_index_command->reIndexAll();
  }

  public function reindexClinics()
  {
    ini_set('memory_limit', '512M');
    ModelManager::disableEntityMapGlobal();
    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->reIndexAll();
  }

  public function reindexLaboratories()
  {
    ini_set('memory_limit', '512M');
    ModelManager::disableEntityMapGlobal();
    $laboratory_index_command = new LaboratoryIndexCommand();
    $laboratory_index_command->reIndexAll();
  }

  public function beforeRender()
  {
    exit();
  }
}