<?php

/**
 * Interface IElasticSearchModelManager
 *
 * Абстрактный класс для классов, которые умеют управлять данными данной модели в индексе:
 * добавлять, обновлять, искать
 */
class ElasticSearchIndexControl implements IElasticSearchIndexControl
{
  /**
   * @var \Elastica\Client
   */
  protected $elastica_api;

  public function __construct()
  {
    $this->elastica_api = ElasticaFactory::getApi();
  }

  /**
   * @return \Elastica\Client
   */
  protected function getElasticaApi()
  {
    return $this->elastica_api;
  }


  /**
   * Создание нового индекса
   *
   * @param $index_name
   *
   * @return mixed
   */
  public function createIndex($index_name)
  {
    $index = $this->getIndex($index_name);
    $index->create(array(
      'number_of_shards' => 4,
      'number_of_replicas' => 1,
      'analysis' => array(
        'analyzer' => array(
          'indexAnalyzer' => array(
            'type' => 'custom',
            'tokenizer' => 'standard',
            'filter' => array('lowercase', 'russian_morphology', 'mynGram', 'tags_filter'),
          ),
          'searchAnalyzer' => array(
            'type' => 'custom',
            'tokenizer' => 'standard',
            'filter' => array('lowercase', 'russian_morphology', 'mynGram', 'tags_filter'),
          ),
          'autocomplete' => array(
            'type' => 'custom',
            'tokenizer' => 'standard',
            'filter' => array('lowercase', 'russian_morphology', 'mynGram', 'tags_filter'),
          ),
        ),
        'filter' => array(
          'mynGram' => array(
            "type" => "EdgeNGram",
            "min_gram" => 3,
            "max_gram" => 30
          ),
          "tags_filter" => array(
            "type" => "word_delimiter",
            "type_table" => array("-" => "ALPHA"),
          ),
        ),
      ),
    ), true);

    /* удаление существующего индекса true, оставляем - false */
  }

  /**
   * Получение индекса
   *
   * @param $index_name
   *
   * @return \Elastica\Index
   */
  public function getIndex($index_name)
  {
    return $this->getElasticaApi()->getIndex($index_name);
  }

  /**
   * Удаление индекса
   *
   * @param $index_name
   *
   * @return mixed
   */
  public function deleteIndex($index_name)
  {
    $this->getIndex($index_name)->delete();
  }

  /**
   * Обновление индекса
   *
   * @param $index_name название индекса
   *
   * @return mixed
   */
  public function refreshIndex($index_name)
  {
    $this->getIndex($index_name)->refresh();
  }

}