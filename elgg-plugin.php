<?php

return [
	'bootstrap' => \hypeJunction\Bootstrap::class,

	'routes' => [
		'timezones' => [
			'path' => '/data/timezones',
			'controller' => \hypeJunction\Time\TimezoneProvider::class,
			'middleware' => [
				\Elgg\Router\Middleware\AjaxGatekeeper::class,
			],
		],
	],

	'settings' => [
		'format:time' => 'H:i',
		'format:date' => 'M j, Y',
		'week:starts' => 'Mon',
		'timezone' => date_default_timezone_get(),
	],

	'user_settings' => [
		'format:time' => 'H:i',
		'format:date' => 'M j, Y',
		'week:starts' => 'Mon',
		'timezone' => date_default_timezone_get(),
	],
];
