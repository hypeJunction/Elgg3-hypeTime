<?php

namespace hypeJunction\Time;

use DateTime;
use DateTimeZone;
use Elgg\Hook;
use Elgg\Request;
use ElggEntity;

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

		$fields['calendar_start'] = [
			'#type' => 'datetime',
			'#input' => function (Request $request) {
				$value = $request->getParam('calendar_start', []);
				$timezone = elgg_extract('timezone', $value, get_input('timezone'));
				$tz = (new DateTime())->getTimezone();
				if ($timezone) {
					$tz = new DateTimeZone($timezone);
				}

				$date_str = elgg_extract('date', $value);
				$time_str = elgg_extract('time', $value);

				if (!$date_str || !$time_str) {
					return null;
				}

				$time = "$date_str $time_str";

				return new DateTime($time, $tz);
			},
			'#setter' => function (ElggEntity $entity, $value) use ($hook) {
				$svc = $hook->elgg()->{'posts.calendar'};

				/* @var $svc Post */

				return $svc->setCalendarStart($entity, $value);
			},
			'#getter' => function (ElggEntity $entity) use ($hook) {
				$svc = $hook->elgg()->{'posts.calendar'};

				/* @var $svc Post */

				return $svc->getCalendarStart($entity);
			},
			'#priority' => 420,
			'#visibility' => function (ElggEntity $entity) use ($hook) {
				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:calendar_start',
					"$entity->type:$entity->subtype",
					$params,
					false
				);
			}
		];

		$fields['calendar_end'] = [
			'#type' => 'datetime',
			'#input' => function (Request $request) {
				$value = $request->getParam('calendar_end', []);
				$timezone = elgg_extract('timezone', $value, get_input('timezone'));
				if (!$timezone) {
					$start = get_input('calendar_start', []);
					$timezone = elgg_extract('timezone', $start);
				}
				if ($timezone) {
					$tz = new DateTimeZone($timezone);
				} else {
					$tz = (new DateTime())->getTimezone();
				}

				$date_str = elgg_extract('date', $value);
				$time_str = elgg_extract('time', $value);

				if (!$date_str || !$time_str) {
					return null;
				}

				$time = "$date_str $time_str";

				return new DateTime($time, $tz);
			},
			'#setter' => function (ElggEntity $entity, $value) use ($hook) {
				$svc = $hook->elgg()->{'posts.calendar'};

				/* @var $svc Post */

				return $svc->setCalendarEnd($entity, $value);
			},
			'#getter' => function (ElggEntity $entity) use ($hook) {
				$svc = $hook->elgg()->{'posts.calendar'};

				/* @var $svc Post */

				return $svc->getCalendarEnd($entity);
			},
			'#priority' => 421,
			'#visibility' => function (ElggEntity $entity) use ($hook) {
				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:calendar_end',
					"$entity->type:$entity->subtype",
					$params,
					false
				);
			}
		];

		$fields['timezone'] = [
			'#type' => 'timezone',
			'#getter' => function (ElggEntity $entity) use ($hook) {
				$svc = $hook->elgg()->{'posts.calendar'};

				/* @var $svc Post */

				$start = $svc->getCalendarStart($entity);
				if (!$start) {
					return null;
				}

				return $start->getTimezone();
			},
			'#priority' => 422,
			'#visibility' => function (ElggEntity $entity) use ($hook) {
				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:timezone',
					"$entity->type:$entity->subtype",
					$params,
					false
				);
			},
			'#profile' => false,
		];

		return $fields;
	}
}
