<?php

namespace hypeJunction\Time;

use Elgg\IntegrationTestCase;
use hypeJunction\Time;

class TimeTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypetime';
	}

	public function up(): void {}

	public function down(): void {}

	public function testIsValidTimezoneAcceptsValidId(): void {
		$this->assertTrue(Time::isValidTimezone('America/New_York'));
		$this->assertTrue(Time::isValidTimezone('UTC'));
		$this->assertTrue(Time::isValidTimezone('Europe/Berlin'));
	}

	public function testIsValidTimezoneRejectsInvalidId(): void {
		$this->assertFalse(Time::isValidTimezone('Not/ATimezone'));
		$this->assertFalse(Time::isValidTimezone(''));
		$this->assertFalse(Time::isValidTimezone(null));
	}

	public function testGetDayStartReturnsMidnight(): void {
		$ts = mktime(15, 30, 0, 6, 15, 2024); // 2024-06-15 15:30:00 UTC
		$dayStart = (int) Time::getDayStart($ts, 'U', 'UTC');

		$dt = new \DateTime('@' . $dayStart);
		$this->assertEquals('00:00:00', $dt->format('H:i:s'));
	}

	public function testGetDayEndReturnsEndOfDay(): void {
		$ts = mktime(15, 30, 0, 6, 15, 2024); // 2024-06-15 15:30:00 UTC
		$dayEnd = (int) Time::getDayEnd($ts, 'U', 'UTC');

		$dt = new \DateTime('@' . $dayEnd);
		$this->assertEquals('23:59:59', $dt->format('H:i:s'));
	}

	public function testGetDayStartAndEndAreSameDay(): void {
		$ts = mktime(15, 30, 0, 6, 15, 2024);
		$start = (int) Time::getDayStart($ts, 'U', 'UTC');
		$end = (int) Time::getDayEnd($ts, 'U', 'UTC');

		$this->assertLessThan($end, $start);

		$dtStart = new \DateTime('@' . $start);
		$dtEnd = new \DateTime('@' . $end);
		$this->assertEquals($dtStart->format('Y-m-d'), $dtEnd->format('Y-m-d'));
	}

	public function testMapJsDateFormatConvertsPhpTokens(): void {
		$this->assertEquals('dd/mm/yy', Time::mapJsDateFormat('d/m/Y'));
		$this->assertEquals('M d, yy', Time::mapJsDateFormat('M j, Y'));
		$this->assertEquals('DD, MM d yy', Time::mapJsDateFormat('l, F j Y'));
	}

	public function testGetClientTimezoneDefaultsToUtc(): void {
		// Without a logged-in user and no plugin setting, falls back to UTC
		$tz = Time::getClientTimezone();
		$this->assertTrue(Time::isValidTimezone($tz));
	}

	public function testGetMonthStartReturnsFirstDayOfMonth(): void {
		$ts = mktime(0, 0, 0, 6, 15, 2024); // June 15
		$monthStart = (int) Time::getMonthStart($ts, 'U', 'UTC');

		$dt = new \DateTime('@' . $monthStart);
		$this->assertEquals('2024-06-01', $dt->format('Y-m-d'));
	}

	public function testGetMonthEndReturnsLastDayOfMonth(): void {
		$ts = mktime(0, 0, 0, 6, 15, 2024); // June 15
		$monthEnd = (int) Time::getMonthEnd($ts, 'U', 'UTC');

		$dt = new \DateTime('@' . $monthEnd);
		$this->assertEquals('2024-06-30', $dt->format('Y-m-d'));
	}
}
