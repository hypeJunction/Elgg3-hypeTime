<?php

namespace hypeJunction;

use DateTime;
use DateTimeZone;
use ElggEntity;
use ElggUser;
use stdClass;

class Time {

	/**
	 * Seconds
	 */
	const SECONDS_IN_A_MINUTE = 60;
	const SECONDS_IN_AN_HOUR = 3600;
	const SECONDS_IN_A_DAY = 86400;
	const SECONDS_IN_A_WEEK = 604800;

	/**
	 * Days of week
	 */
	const MONDAY = 'Mon';
	const TUESDAY = 'Tue';
	const WEDNESDAY = 'Wed';
	const THURSDAY = 'Thu';
	const FRIDAY = 'Fri';
	const SATURDAY = 'Sat';
	const SUNDAY = 'Sun';

	/**
	 * Timezones
	 */
	const UTC = 'UTC';
	const TIMEZONE_FORMAT_FULL = "\(\G\M\TP\) e - H:i T";
	const TIMEZONE_FORMAT_ABBR = "T";
	const TIMEZONE_FORMAT_NAME = "e";
	const TIMEZONE_SORT_ALPHA = 'alpha';
	const TIMEZONE_SORT_OFFSET = 'offset';

	/**
	 * Returns a timestamp for 0:00:00 of the date of the time
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 *
	 * @return string
	 */
	public static function getDayStart($ts = 'now', $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);
		$dt->setTime(0, 0, 0);

		return $dt->format($format);
	}

	/**
	 * Returns a timestamp for 23:59:59 of the date of the time
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 *
	 * @return string
	 */
	public static function getDayEnd($ts = 'now', $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);
		$dt->setTime(23, 59, 59);

		return $dt->format($format);
	}

	/**
	 * Returns a timestamp for the first of the month at 0:00:00
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 *
	 * @return string
	 */
	public static function getMonthStart($ts = 'now', $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

		$month = (int) $dt->format('m'); // month
		$year = (int) $dt->format('Y'); // year

		$dt->setDate($year, $month, 1);
		$dt->setTime(0, 0, 0);

		return $dt->format($format);
	}

	/**
	 * Returns a timestamp for the last day of themonth at 23:59:59
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 * @param string $tz     Timezone
	 *
	 * @return string
	 */
	public static function getMonthEnd($ts = 'now', $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

		$dt->modify('+1 month');

		$month = (int) $dt->format('m'); // month
		$year = (int) $dt->format('Y'); // year

		$dt->setDate($year, $month, 1);
		$dt->setTime(0, 0, 0);

		$dt->modify('-1 second');

		return $dt->format($format);
	}

	/**
	 * Extracts time of the day timestamp
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 *
	 * @return string
	 */
	public static function getTime($ts = 'now', $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

		$time = (int) $dt->format('H') * self::SECONDS_IN_AN_HOUR;
		$time += (int) $dt->format('i') * self::SECONDS_IN_A_MINUTE;
		$time += (int) $dt->format('s');

		return $dt->setTimestamp($time)->format($format);
	}

	/**
	 * Calculates a timestamp by extracting time from $ts_time and adding it to the day start on $ts_day
	 *
	 * @param mixed $ts_time Date/time string containing time information
	 * @param mixed $ts_day  Date/time string containing day information
	 *
	 * @return int
	 */
	public static function getTimeOfDay($ts_time = 0, $ts_day = null, $format = 'U', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$time = (int) Time::getTime($ts_time, 'U', $tz);
		$day_start = (int) Time::getDayStart($ts_day, 'U', $tz);

		$dt = new DateTime(null, new DateTimeZone($tz));

		return $dt->setTimestamp($time + $day_start)->format($format);

	}

	/**
	 * Returns day of week
	 *
	 * @param mixed  $ts     Date/time value
	 * @param string $format Format of the return value
	 *
	 * @return string
	 */
	public static function getDayOfWeek($ts = 'now', $tz = null, $format = 'D') {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

		return $dt->format($format);
	}

	/**
	 * Returns the week number if a month (e.g. 2nd week of the month)
	 *
	 * @param mixed $ts Date/time value
	 *
	 * @return int
	 */
	public static function getWeekOfMonth($ts = 'now', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);
		$week_num_ts = (int) $dt->format('W');
		$week_num_month_start = $dt->setTimestamp(self::getMonthStart($ts, 'U', $tz))->format('W');

		return $week_num_ts - $week_num_month_start + 1;
	}

	/**
	 * Returns nth position of a weekday in a month (e.g. 2nd Monday of a month)
	 *
	 * @param mixed $ts Date/time value
	 *
	 * @return int
	 */
	public static function getWeekDayNthInMonth($ts = 'now', $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}
		$dt = new DateTime(null, new DateTimeZone($tz));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

		return ceil($dt->format('j') / 7);
	}

	/**
	 * Checks if two timestamps fall on the same day of the week (e.g. Monday)
	 *
	 * @param int $ts1 First timestamp
	 * @param int $ts2 Second timestamp
	 *
	 * @return bool
	 */
	public static function isOnSameDayOfWeek($ts1 = 0, $ts2 = 0, $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}
		$dt1 = new DateTime(null, new DateTimeZone($tz));
		$dt2 = new DateTime(null, new DateTimeZone($tz));

		return $dt1->setTimestamp($ts1)->format('D') == $dt2->setTimestamp($ts2)->format('D');
	}

	/**
	 * Checks if two timestamps fall on the same date of the month (e.g. 25th)
	 *
	 * @param int $ts1 First timestamp
	 * @param int $ts2 Second timestamp
	 *
	 * @return bool
	 */
	public static function isOnSameDayOfMonth($ts1 = 0, $ts2 = 0, $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}
		$dt1 = new DateTime(null, new DateTimeZone($tz));
		$dt2 = new DateTime(null, new DateTimeZone($tz));

		return $dt1->setTimestamp($ts1)->format('j') == $dt2->setTimestamp($ts2)->format('j');
	}

	/**
	 * Checks if two timestamps fall on the same date of the year (e.g. February 25th)
	 *
	 * @param int $ts1 First timestamp
	 * @param int $ts2 Second timestamp
	 *
	 * @return bool
	 */
	public static function isOnSameDayOfYear($ts1 = 0, $ts2 = 0, $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		$dt1 = new DateTime(null, new DateTimeZone($tz));
		$dt2 = new DateTime(null, new DateTimeZone($tz));

		return $dt1->setTimestamp($ts1)->format('m-j') == $dt2->setTimestamp($ts2)->format('m-j');
	}

	/**
	 * Checks if two timestamps fall on the same week day of the month (e.g. 3rd Monday)
	 *
	 * @param int $ts1 First timestamp
	 * @param int $ts2 Second timestamp
	 *
	 * @return bool
	 */
	public static function isOnSameWeekDayOfMonth($ts1 = 0, $ts2 = 0, $tz = null) {
		if (!Time::isValidTimezone($tz)) {
			$tz = Time::getClientTimezone();
		}

		if (!self::isOnSameDayOfWeek($ts1, $ts2, $tz)) {
			return false;
		}

		return self::getWeekDayNthInMonth($ts1, $tz) == self::getWeekDayNthInMonth($ts2, $tz);
	}

	/**
	 * Returns an array of weekdays
	 * @return array
	 */
	public static function getWeekdays() {
		return [
			self::MONDAY,
			self::TUESDAY,
			self::WEDNESDAY,
			self::THURSDAY,
			self::FRIDAY,
			self::SATURDAY,
			self::SUNDAY,
		];
	}

	/**
	 * Calculates the offset between timezones at a given date/time
	 *
	 * @param mixed  $ts       Date/time value
	 * @param string $timezone Timezone of the date/time value
	 *                         $param string $target_timezone Target timezone
	 *
	 * @return int
	 */
	public static function getOffset($ts = 'now', $timezone = self::UTC, $target_timezone = self::UTC) {

		if (!self::isValidTimezone($timezone) || !self::isValidTimezone($target_timezone)) {
			return 0;
		}

		$dta = new DateTime(null, new DateTimeZone($timezone));
		$dtb = new DateTime(null, new DateTimeZone($target_timezone));

		if (is_int($ts)) {
			$dta->setTimestamp($ts);
			$dtb->setTimestamp($ts);
		} else {
			$dta->modify($ts);
			$dtb->modify($ts);
		}

		return $dtb->getOffset() - $dta->getOffset();
	}

	/**
	 * Returns a list of supported timezones
	 * Triggers 'timezones','system' hook if $filter is set to true
	 *
	 * @param boolean $filter  If false, returns all supported PHP timezones
	 * @param mixed   $format  Timezone label date format; if false, uses elgg_echo($tz_id)
	 * @param mixed   $ts      Optional timestamp for label format
	 * @param string  $sort_by 'alpha' or 'offset'
	 *
	 * @return array
	 */
	public static function getTimezones($filter = true, $format = false, $ts = 'now', $sort = self::TIMEZONE_SORT_ALPHA) {

		$tz_ids = DateTimeZone::listIdentifiers();

		$defaults = [];

		foreach ($tz_ids as $tz_id) {
			$defaults[$tz_id] = Time::getTimezoneLabel($tz_id, $format, $ts);
		}

		switch ($sort) {
			case self::TIMEZONE_SORT_ALPHA :
				asort($defaults);
				break;
			case self::TIMEZONE_SORT_OFFSET :
				uksort($defaults, [self, 'compareTimezonesByOffset']);
				break;
		}

		if ($filter) {
			return elgg_trigger_plugin_hook('timezones', 'system', null, $defaults);
		}

		return $defaults;
	}

	/**
	 * Returns an array of timezones by country
	 * @return array
	 */
	public static function getTimezonesByCountry() {
		$timezones = [];
		$tz_ids = array_keys(self::getTimezones(true, false, 'now', self::TIMEZONE_SORT_OFFSET));
		foreach ($tz_ids as $tz_id) {
			if ($tz_id == Time::UTC) {
				continue;
			}
			$info = Time::getTimezoneInfo($tz_id);
			$cc = $info->country_code;
			$abbr = $info->abbr;
			if (!isset($timezones[$cc])) {
				$timezones[$cc] = [];
			}
			$timezones[$cc][] = $info;
		}
		ksort($timezones);

		return $timezones;
	}

	/**
	 * Expands timezone ID into a usable source of data about the timezone
	 *
	 * @param string $tz_id Timezone ID e.g. America\New_York
	 *
	 * @return stdClass
	 */
	public static function getTimezoneInfo($tz_id) {

		$tz = new \DateTimeZone($tz_id);
		$location = $tz->getLocation();
		$country_code = $location['country_code'];

		$dt = new DateTime(null, $tz);

		$region = explode('/', $tz_id);
		if (sizeof($region) > 1) {
			array_shift($region);
		}
		$region = str_replace('_', ' ', implode(', ', $region));

		$tzinfo = new stdClass();
		$tzinfo->id = $tz_id;
		$tzinfo->abbr = $dt->format('T');
		$tzinfo->country_code = $country_code;
		$tzinfo->country = elgg_echo("country:$country_code");
		$tzinfo->region = $region;
		$tzinfo->offset = $dt->getOffset();
		$tzinfo->gmt = $dt->format('\(\G\M\TP\)');

		$name = "timezone:$tzinfo->country_code:$tzinfo->abbr";
		$name_tr = elgg_echo($name);
		$tzinfo->name = ($name == $name_tr) ? $tzinfo->abbr : $name_tr;
		$tzinfo->label = "$tzinfo->gmt $tzinfo->name - $tzinfo->region";

		return $tzinfo;
	}

	/**
	 * Checks if $timezone id is valid
	 *
	 * @param string $timezone
	 *
	 * @return bool
	 */
	public static function isValidTimezone($timezone) {
		static $cache;

		if (empty($timezone) || !is_string($timezone)) {
			return false;
		}

		if ($timezone instanceof DateTimeZone) {
			return true;
		}

		if (!is_array($cache)) {
			$cache = [];
		}

		if (isset($cache[$timezone])) {
			return $cache[$timezone];
		}

		$cache[$timezone] = in_array($timezone, DateTimeZone::listIdentifiers());

		return $cache[$timezone];
	}

	/**
	 * Returns a label for a timezone
	 *
	 * @param string $tz_id  PHP timezone id
	 * @param string $format Format
	 * @param mixed  $ts     Optional timestamp
	 *
	 * @return string
	 */
	public static function getTimezoneLabel($tz_id, $format = null, $ts = 'now') {
		if (self::isValidTimezone($tz_id) && $format) {
			$dt = new DateTime(null, new DateTimeZone($tz_id));
			(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);

			return $dt->format($format);
		}

		return elgg_echo($tz_id);
	}

	/**
	 * Sorting callback function for comparing timezones by offset
	 * @return int
	 */
	public static function compareTimezonesByOffset($a, $b) {

		$dta = new DateTime(null, new DateTimeZone($a));
		$dtb = new DateTime(null, new DateTimeZone($b));

		if ($dta->getOffset() == $dtb->getOffset()) {
			return (strcmp($a, $b) < 0) ? -1 : 1;
		}

		return ($dta->getOffset() < $dtb->getOffset()) ? -1 : 1;
	}

	/**
	 * Returns display timezone
	 *
	 * @param ElggEntity $entity User
	 *
	 * @return string
	 */
	public static function getClientTimezone(ElggEntity $entity = null) {

		$preferred = [];

		if ($entity == null) {
			$entity = elgg_get_logged_in_user_entity();
		}

		if ($entity instanceof ElggUser) {
			$preferred[] = elgg_get_plugin_user_setting('timezone', $entity->guid, 'hypeTime');
		}

		$preferred[] = elgg_get_plugin_setting('timezone', 'hypeTime');

		if (defined('ELGG_SITE_TIMEZONE')) {
			$preferred[] = ELGG_SITE_TIMEZONE;
		}

		$preferred[] = date('e');

		$preferred[] = self::UTC;

		foreach ($preferred as $id) {
			if (self::isValidTimezone($id)) {
				return $id;
			}
		}
	}

	/**
	 * Returns a representation of $ts in ISO8601 format using $tz_id as a base timezone
	 *
	 * @param mixed  $ts              Date/time value
	 * @param string $timezone        Base timezone of the date/time value
	 * @param string $target_timezone Target timezone of the formatted value
	 *
	 * @return string
	 */
	public static function toISO8601($ts = 'now', $timezone = Time::UTC, $target_timezone = Time::UTC) {
		$dt = new DateTime(null, new DateTimeZone($timezone));
		(is_int($ts)) ? $dt->setTimestamp($ts) : $dt->modify($ts);
		$dt->setTimezone(new DateTimeZone($target_timezone));

		return $dt->format('c');
	}

	/**
	 * Map PHP date format to JS date format
	 *
	 * @param string $format PHP format
	 *
	 * @return string
	 */
	public static function mapJsDateFormat($format) {

		$map = [
			// Day
			'd' => 'dd',
			'j' => 'd',
			'l' => 'DD',
			'D' => 'D',
			// Month
			'm' => 'mm',
			'n' => 'm',
			'F' => 'MM',
			'M' => 'M',
			// Year
			'Y' => 'yy',
			'y' => 'y',
		];

		return strtr($format, $map);

	}
}
