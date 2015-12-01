<?php

return [
	
	'users' => [
		'class' => 'Umcms\modules\Users\Module',
		'defaultRoute' => 'user/index',
	],
	
	'rbac' => [
		'class' => 'Rbac\backend\Module',
		'defaultRoute' => 'roles/index',
	]
];