<?php

namespace hypeJunction\Time;

use DateTime;
use DateTimeZone;
use Elgg\Request;
use ElggEntity;
use hypeJunction\Fields\Field;
use Symfony\Component\HttpFoundation\ParameterBag;

class CalendarStartField extends Field {

	public function isVisible(ElggEntity $entity, $context = null) {
		$params = [
			'entity' => $entity,
		];

		$enabled = elgg()->hooks->trigger(
			'uses:calendar_start',
			"$entity->type:$entity->subtype",
			$params,
			false
		);

		if (!$enabled) {
			return false;
		}

		return parent::isVisible($entity, $context);
	}

	public function raw(Request $request, ElggEntity $entity) {
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
	}

	public function save(ElggEntity $entity, ParameterBag $parameters) {
		$value = $parameters->get($this->name);

		$svc = elgg()->{'posts.calendar'};

		/* @var $svc CalendarService */

		return $svc->setCalendarStart($entity, $value);
	}

	public function retrieve(ElggEntity $entity) {
		$svc = elgg()->{'posts.calendar'};

		/* @var $svc CalendarService */

		return $svc->getCalendarStart($entity);
	}
}