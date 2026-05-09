<?php

namespace hypeJunction\Time;

use Elgg\Event;

/**
 * Event handler: saves user time/timezone preferences from the settings form.
 */
class SetUserPreferences {

	/**
	 * Save user settings
	 *
	 * @param Event $event Event
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\DatabaseException
	 */
	public function __invoke(Event $event) {

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
				$user->setPluginSetting('hypetime', $setting, $value);
			}
		}
	}
}
