<?php

namespace hypeJunction\Time;

use DateTime;
use DateTimeZone;
use Elgg\Hook;
use Elgg\Request;
use ElggEntity;
use hypeJunction\Fields\Collection;

class AddFormField {

	/**
	 * Add slug field
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(Hook $hook) {

		$fields = $hook->getValue();
		/* @var $field Collection */

		$fields->add('calendar_start', new CalendarStartField([
			'type' => 'datetime',
			'priority' => 420,
		]));

		$fields->add('calendar_end', new CalendarEndField([
			'type' => 'datetime',
			'priority' => 421,
		]));

		$fields->add('timezone', new TimezoneField([
			'type' => 'timezone',
			'priority' => 422,
			'is_profile_field' => false,
		]));

		return $fields;
	}
}
