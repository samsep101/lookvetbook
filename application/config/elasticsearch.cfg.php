<?php

	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/classes', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/formatters', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/interfaces', '', 'interface');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/mappings', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/index_controls', '', 'class');
	Application::addClassDir(Application::getApplicationDir() . '/library/classes/elasticsearch/index_commands', '', 'class');

	Register::add('ELASTIC_SEARCH_INDEX', 'lookvetbook');

        Register::add('ELASTICA_SERVERS', array(
                "servers" => array(array(
                        'host' => 'vet_elastic',
                        'port' => 9200
                ))
        ));

	$index_models = array(
		'ProductModel',
		'DiseaseModel',
		'ClinicModel',
		'DoctorModel'
	);

	Register::add('index_models', $index_models);
