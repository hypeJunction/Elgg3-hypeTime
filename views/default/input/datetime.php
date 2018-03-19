<?php

/**
 * Display date and time input
 */

$name = elgg_extract('name', $vars);
$value = elgg_extract('value', $vars);
$show_timezone = elgg_extract('show_timezone', $vars);

if (is_array($value)) {
	// sticky values
	$date = elgg_extract('date', $value);
	$time = elgg_extract('time', $value);
	$timezone = elgg_extract('timezone', $value);
} else {
	$date = $time = $timezone = $value;
}

$fields = [
	[
		'#type' => 'date',
		'name' => "{$name}[date]",
		'value' => $date,
		'timestamp' => false,
	],
	[
		'#type' => 'time',
		'name' => "{$name}[time]",
		'value' => $time,
		'timestamp' => false,
	],
];

if ($show_timezone) {
	$fields[] = [
		'#type' => 'timezone',
		'name' => "{$name}[timezone]",
		'value' => $timezone,
	];
}

$output = '';

foreach ($fields as $field) {
	$output .= elgg_view_field($field);
}

echo elgg_format_element('div', [
	'class' => 'elgg-input-datetime',
], $output);

