<?php

/**
 * Display a timezone input
 */

$name = elgg_extract('name', $vars, 'timezone');
$value = elgg_extract('value', $vars, \hypeJunction\Time::getClientTimezone(), false);

if ($value instanceof DateTimeZone) {
	$this_timezone = $value;
} else if ($value instanceof DateTime) {
	$this_timezone = $value->getTimezone();
} else if (is_string($value) && \hypeJunction\Time::isValidTimezone($value)) {
	$this_timezone = new DateTimeZone($value);
} else {
	$tz = \hypeJunction\Time::getClientTimezone();
	$this_timezone = new DateTimeZone($tz);
}

$this_location = $this_timezone->getLocation();
$this_country_code = isset($this_location['country_code']) ? $this_location['country_code'] : 'US';

$timezones = \hypeJunction\Time::getTimezonesByCountry();

$country_options = [];
$timezone_options = [
	'UTC' => 'UTC',
];

foreach ($timezones as $country_code => $country_timezones) {
	$country_options[$country_code] = elgg_echo("country:$country_code");
	if ($country_code == $this_country_code) {
		foreach ($country_timezones as $country_timezone) {
			$timezone_options[$country_timezone->id] = $country_timezone->label;
		}
	}
}
asort($country_options);
?>
<div class="elgg-input-timezone">
	<?php
	echo elgg_view_field([
		'#type' => 'select',
		'data-timezone-country' => $this_country_code,
		'value' => $this_country_code,
		'options_values' => $country_options,
	]);

	echo elgg_view_field([
		'#type' => 'select',
		'data-timezone-id' => $this_timezone->getName(),
		'name' => $name,
		'value' => $this_timezone->getName(),
		'options_values' => $timezone_options,
	]);
	?>
</div>
<script>
	require(['input/timezone']);
</script>
