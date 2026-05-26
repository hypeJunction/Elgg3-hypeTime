<?php

namespace hypeJunction\Time;

use Elgg\Event;
use hypeJunction\Time;

/**
 * Injects week-start setting into datepicker view_vars.
 */
class ConfigureDatepicker {

	/**
	 * Configure datepicker
	 *
	 * @param Event $event Event
	 *
	 * @return mixed
	 */
	public function __invoke(Event $event) {

		$vars = $event->getValue();

		$options = (array) \elgg_extract('datepicker_options', $vars, []);

		if (!isset($options['firstDay'])) {
			$user = \elgg_get_logged_in_user_entity();
			if ($user) {
				$setting = \elgg_get_plugin_user_setting('week:starts', $user->guid, 'hypetime');
			} else {
				$setting = \elgg_get_plugin_setting('week:starts', 'hypetime');
			}

			if ($setting === Time::SUNDAY) {
				$options['firstDay'] = 0;
			} else {
				$options['firstDay'] = 1;
			}
		}

		$vars['datepicker_options'] = $options;

		return $vars;
	}
}
