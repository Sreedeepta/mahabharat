<?php
use Norm\Schema\String;
use Norm\Schema\Text;

return array(
	'schema' => array(
		'episode'     => String::create('episode')->filter('trim|required'),
		'movie'       => String::create('movie')->filter('trim|required'),
		// 'description' => Text::create('description')->filter('trim')
    ),
);
