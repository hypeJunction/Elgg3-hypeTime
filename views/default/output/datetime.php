<?php

$format = elgg_extract('format', $vars);
if (!$format) {
	$format = elgg_get_config('date_format') . ' ' . elgg_get_config('time_format');
}

$value = elgg_extract('value', $vars);
if (!$value) {
	return;
}

try {
	$dt = \Elgg\Values::normalizeTime($value);
	echo $dt->format($format);
} catch (DataFormatException $ex) {
}
