<?php

namespace hypeJunction\Time;

use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use hypeJunction\Time;

class TimezoneProvider {

	/**
	 * Provide timezone info
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {

		elgg_set_http_header('Content-Type: application/json');

		$country = get_input('country');
		$timezones = Time::getTimezonesByCountry();

		if ($country) {
			$country = strtoupper($country);
			$country_timezones = elgg_extract($country, $timezones);
			$data = json_encode($country_timezones);
		} else {
			$data = json_encode($timezones);
		}

		return elgg_ok_response($data);
	}
}
