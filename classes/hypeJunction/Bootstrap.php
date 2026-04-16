<?php

namespace hypeJunction;

use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function load() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		elgg_extend_view('elgg.css', 'input/timezone.css');
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/time');

		elgg_register_plugin_hook_handler('usersettings:save', 'user', \hypeJunction\Time\SetUserPreferences::class);
		elgg_register_plugin_hook_handler('view_vars', 'input/date', \hypeJunction\Time\ConfigureDatepicker::class);
		elgg_register_plugin_hook_handler('fields', 'object', \hypeJunction\Time\AddFormField::class);

		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$date_format = elgg_get_plugin_user_setting('format:date', $user->guid, 'hypetime');
			$time_format = elgg_get_plugin_user_setting('format:time', $user->guid, 'hypetime');
		} else {
			$date_format = elgg_get_plugin_setting('format:date', 'hypetime');
			$time_format = elgg_get_plugin_setting('format:time', 'hypetime');
		}

		elgg_set_config('date_format', $date_format);
		elgg_set_config('date_format_datepicker', \hypeJunction\Time::mapJsDateFormat($date_format));
		elgg_set_config('time_format', $time_format);
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}
}
