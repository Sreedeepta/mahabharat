<?php
use Norm\Schema\String;
use Norm\Schema\Password;

return array(
	'schema' => array(
		'username'   => String::create('username')->filter('trim|required|unique:User,username'),
		'password'   => Password::create('password')->filter('trim|confirmed|salt'),
		'email'      => String::create('email')->filter('trim|required|unique:User,email'),
		'first_name' => String::create('first_name')->filter('trim|required'),
		'last_name'  => String::create('last_name')->filter('trim|required'),
    ),
);
