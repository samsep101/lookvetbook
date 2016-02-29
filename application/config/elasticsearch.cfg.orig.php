<?php

	function __autoload_elastica($class)
	{
		$path = str_replace('\\', '/', substr($class, 0));

		if(file_exists('application/library/third_party/' . $path . '.php'))
		{
			require_once('application/library/third_party/' . $path . '.php');
		}
	}

	spl_autoload_register('__autoload_elastica');


	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/classes', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/formatters', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/interfaces', '', 'interface');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/mappings', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/index_controls', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/index_commands', '', 'class');

	Register::add('ELASTIC_SEARCH_INDEX', 'lookmedbook');

	Register::add('ELASTICA_SERVERS', array(
		array(
			'host' => 'localhost',
			'port' => 9200
		)
	));

	$index_models = array(
		'ProductModel',
		'DiseaseModel',
		'ClinicModel',
		'DoctorModel'
	);

	Register::add('index_models', $index_models);