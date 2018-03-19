<?php

return [

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
		'week:starts' => \hypeJunction\Time::MONDAY,
		'timezone' => (new DateTime())->getTimezone(),
	],

	'user_settings' => [
		'format:time' => 'H:i',
		'format:date' => 'M j, Y',
		'week:starts' => \hypeJunction\Time::MONDAY,
		'timezone' => (new DateTime())->getTimezone(),
	],
];
