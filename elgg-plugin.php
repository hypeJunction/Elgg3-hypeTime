<?php

return [
	'plugin' => [
		'name' => 'hypeTime',
		'description' => 'Utilities for working with dates and time',
		'version' => '4.0.0',
		'dependencies' => [
			'hypepost' => [
				'must_be_active' => true,
				'position' => 'after',
			],
		],
	],

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

	'view_extensions' => [
		'elgg.css' => [
			'input/timezone.css' => [],
		],
		'forms/usersettings/save' => [
			'core/settings/account/time' => [],
		],
	],

	'events' => [
		'usersettings:save' => [
			'user' => [
				\hypeJunction\Time\SetUserPreferences::class => [],
			],
		],
		'view_vars' => [
			'input/date' => [
				\hypeJunction\Time\ConfigureDatepicker::class => [],
			],
		],
		'fields' => [
			'object' => [
				\hypeJunction\Time\AddFormField::class => [],
			],
		],
	],
];
