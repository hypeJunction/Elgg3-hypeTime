<?php

namespace hypeJunction\Time;

use Elgg\Hook;

class SetUserPreferences {

	/**
	 * Save user settings
	 *
	 * @param Hook $hook Hook
	 *
	 * @return void
	 * @throws \DatabaseException
	 */
	public function __invoke(Hook $hook) {

		$user_guid = get_input('guid');

		if ($user_guid) {
			$user = get_user($user_guid);
		} else {
			$user = elgg_get_logged_in_user_entity();
		}

		if (!$user) {
			return;
		}

		$settings = [
			'format_time' => 'format:time',
			'format_date' => 'format:date',
			'timezone' => 'timezone',
			'week_starts' => 'week:starts',
		];

		foreach ($settings as $input => $setting) {
			$value = get_input($input);
			if (isset($value)) {
				elgg_set_plugin_user_setting($setting, $value, $user->guid, 'hypeTime');
			}
		}
	}
}
