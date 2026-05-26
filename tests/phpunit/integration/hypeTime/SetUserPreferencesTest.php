<?php

namespace hypeJunction\Time;

use Elgg\IntegrationTestCase;
use Elgg\HooksRegistrationService\Hook;

class SetUserPreferencesTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypetime';
	}

	/** @var \ElggUser */
	private $user;

	public function up(): void {
		$this->user = $this->createUser();
		\elgg_get_session()->setLoggedInUser($this->user);
	}

	public function down(): void {
		\elgg_get_session()->removeLoggedInUser();
		if ($this->user) {
			$this->user->delete();
		}
	}

	private function invokeHandler(): void {
		$hook = new Hook(elgg(), 'usersettings:save', 'user', null, []);
		$handler = new SetUserPreferences();
		$handler($hook);
	}

	public function testHandlerSavesTimeFormat(): void {
		set_input('format_time', 'g:i A');
		$this->invokeHandler();

		$saved = $this->user->getPluginSetting('hypetime', 'format:time');
		$this->assertEquals('g:i A', $saved);
	}

	public function testHandlerSavesDateFormat(): void {
		set_input('format_date', 'Y-m-d');
		$this->invokeHandler();

		$saved = $this->user->getPluginSetting('hypetime', 'format:date');
		$this->assertEquals('Y-m-d', $saved);
	}

	public function testHandlerSavesTimezone(): void {
		set_input('timezone', 'America/Chicago');
		$this->invokeHandler();

		$saved = $this->user->getPluginSetting('hypetime', 'timezone');
		$this->assertEquals('America/Chicago', $saved);
	}

	public function testHandlerSavesWeekStarts(): void {
		set_input('week_starts', 'Sun');
		$this->invokeHandler();

		$saved = $this->user->getPluginSetting('hypetime', 'week:starts');
		$this->assertEquals('Sun', $saved);
	}

	public function testHandlerDoesNothingWithoutUser(): void {
		\elgg_get_session()->removeLoggedInUser();

		set_input('format_time', 'H:i');
		// Should not throw — handler gracefully exits when user is not found
		$this->invokeHandler();
		$this->assertTrue(true);
	}
}
