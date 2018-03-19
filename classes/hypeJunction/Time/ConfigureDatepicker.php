<?php

namespace hypeJunction\Time;

use Elgg\Hook;
use hypeJunction\Time;

class ConfigureDatepicker {

	/**
	 * Configure datepicker
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(Hook $hook) {

		$vars = $hook->getValue();

		$options = (array) elgg_extract('datepicker_options', $vars, []);

		if (!isset($options['firstDay'])) {
			$user = elgg_get_logged_in_user_entity();
			if ($user) {
				$setting = elgg_get_plugin_user_setting('week:starts', $user->guid, 'hypeTime');
			} else {
				$setting = elgg_get_plugin_setting('week:starts', 'hypeTime');
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
