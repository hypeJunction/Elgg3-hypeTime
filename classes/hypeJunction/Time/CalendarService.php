<?php

namespace hypeJunction\Time;

use Elgg\Di\ServiceFacade;
use ElggEntity;
use DateTime;
use DateTimeZone;
use hypeJunction\Time;

class CalendarService {

	use ServiceFacade;

	/**
	 * {@inheritdoc}
	 */
	public function name() {
		return 'posts.calendar';
	}

	/**
	 * Set starting date
	 *
	 * @param ElggEntity $entity Entity
	 * @param DateTime   $dt     DateTime object
	 *
	 * @return bool
	 */
	public function setCalendarStart(ElggEntity $entity, DateTime $dt = null) {

		if ($dt === null) {
			unset($entity->calendar_start);
			unset($entity->calendar_start_iso);
			unset($entity->calendar_start_utc);
			unset($entity->calendar_start_tz);

			return true;
		}

		$entity->calendar_start = $dt->getTimestamp();
		$entity->calendar_start_tz = $dt->getTimezone()->getName();
		$entity->calendar_start_iso = $dt->format(DATE_ISO8601);

		$dt->setTimezone(new DateTimeZone(Time::UTC));
		$entity->calendar_start_utc = $dt->getTimestamp();

		return elgg_trigger_event('update', 'object:calendar_start', $entity);
	}

	/**
	 * Set ending date
	 *
	 * @param ElggEntity $entity Entity
	 * @param DateTime   $dt     DateTime object
	 *
	 * @return bool
	 */
	public function setCalendarEnd(ElggEntity $entity, DateTime $dt = null) {

		if ($dt === null) {
			unset($entity->calendar_end);
			unset($entity->calendar_end_iso);
			unset($entity->calendar_end_utc);
			unset($entity->calendar_end_tz);

			return true;
		}

		$entity->calendar_end = $dt->getTimestamp();
		$entity->calendar_end_tz = $dt->getTimezone()->getName();
		$entity->calendar_end_iso = $dt->format(DATE_ISO8601);

		$dt->setTimezone(new DateTimeZone(Time::UTC));
		$entity->calendar_end_utc = $dt->getTimestamp();

		return elgg_trigger_event('update', 'object:calendar_end', $entity);
	}

	/**
	 * Get start time
	 *
	 * @param ElggEntity $entity   Entity
	 * @param string     $timezone Timezone to normalize the date to
	 *                             Defaults to timezone the time was stored with
	 *
	 * @return DateTime|null
	 */
	public function getCalendarStart(ElggEntity $entity, $timezone = null) {

		if (!isset($entity->calendar_start_utc)) {
			return null;
		}

		$dt = new DateTime('now', new DateTimeZone(Time::UTC));
		$dt->setTimestamp($entity->calendar_start_utc);

		$tz = $timezone ? new DateTimeZone($timezone) : new DateTimeZone($entity->calendar_start_tz);

		$dt->setTimezone($tz);

		return $dt;
	}

	/**
	 * Get end time
	 *
	 * @param ElggEntity $entity   Entity
	 * @param string     $timezone Timezone to normalize the date to
	 *                             Defaults to timezone the time was stored with
	 *
	 * @return DateTime|null
	 */
	public function getCalendarEnd(ElggEntity $entity, $timezone = null) {

		if (!isset($entity->calendar_end_utc)) {
			return null;
		}

		$dt = new DateTime('now', new DateTimeZone(Time::UTC));
		$dt->setTimestamp($entity->calendar_end_utc);

		$tz = $timezone ? new DateTimeZone($timezone) : new DateTimeZone($entity->calendar_end_tz);

		$dt->setTimezone($tz);

		return $dt;
	}
}
