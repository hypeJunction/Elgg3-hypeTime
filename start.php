<?php

use hypeJunction\Time\AddFormField;
use hypeJunction\Time\ConfigureDatepicker;
use hypeJunction\Time\SetUserPreferences;

require_once __DIR__ . '/autoloader.php';

return function () {

	/**
	 * @todo: date range input/output
	 * @todo: datetime range input/output
	 */
	elgg_register_event_handler('init', 'system', function () {

		elgg_extend_view('elgg.css', 'input/timezone.css');

		elgg_extend_view('forms/usersettings/save', 'core/settings/account/time');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', SetUserPreferences::class);

		elgg_register_plugin_hook_handler('view_vars', 'input/date', ConfigureDatepicker::class);

		elgg_register_plugin_hook_handler('fields', 'object', AddFormField::class);

		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$date_format = elgg_get_plugin_user_setting('format:date', $user->guid, 'hypeTime');
			$time_format = elgg_get_plugin_user_setting('format:time', $user->guid, 'hypeTime');
		} else {
			$date_format = elgg_get_plugin_setting('format:date', 'hypeTime');
			$time_format = elgg_get_plugin_setting('format:time', 'hypeTime');
		}

		elgg_set_config('date_format', $date_format);
		elgg_set_config('date_format_datepicker', \hypeJunction\Time::mapJsDateFormat($date_format));
		elgg_set_config('time_format', $time_format);

	});

};
