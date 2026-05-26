<?php

namespace hypeJunction;

use Elgg\DefaultPluginBootstrap;

/**
 * Plugin bootstrap: sets date/time format config on init.
 */
class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init(): void {
		$user = \elgg_get_logged_in_user_entity();
		if ($user) {
			$date_format = \elgg_get_plugin_user_setting('format:date', $user->guid, 'hypetime');
			$time_format = \elgg_get_plugin_user_setting('format:time', $user->guid, 'hypetime');
		} else {
			$date_format = \elgg_get_plugin_setting('format:date', 'hypetime');
			$time_format = \elgg_get_plugin_setting('format:time', 'hypetime');
		}

		\elgg_set_config('date_format', $date_format);
		\elgg_set_config('date_format_datepicker', \hypeJunction\Time::mapJsDateFormat($date_format));
		\elgg_set_config('time_format', $time_format);
	}
}
