# hypeTime — Architecture (Elgg 4.x)

## Overview

hypeTime provides date/time utilities for Elgg plugins: configurable date/time
formats per user, timezone management, calendar start/end metadata on entities,
and JS datepicker integration. It is a low-level utility plugin required by
calendar-aware plugins (via `must_be_active` on hypepost).

## Plugin Structure

```
hypetime/
├── elgg-plugin.php          — declarative plugin config (routes, hooks, settings)
├── classes/hypeJunction/
│   ├── Bootstrap.php        — sets date_format / time_format config on init
│   └── Time.php             — static utility class (timezone list, format mapping,
│   Time/                      date arithmetic helpers)
│       ├── AddFormField.php         — hook: injects calendar fields into entity forms
│       ├── CalendarEndField.php     — field: reads/saves calendar_end metadata
│       ├── CalendarStartField.php   — field: reads/saves calendar_start metadata
│       ├── CalendarService.php      — service: set/get calendar_start/end on entities
│       ├── ConfigureDatepicker.php  — hook: injects datepicker format into input/date vars
│       ├── SetUserPreferences.php   — hook: saves user time/timezone settings
│       ├── TimezoneField.php        — field: reads/saves timezone metadata
│       └── TimezoneProvider.php    — AJAX route controller: returns timezone list as JSON
├── views/default/
│   ├── core/settings/account/time.php  — user settings form extension
│   ├── input/
│   │   ├── date.php       — date picker input
│   │   ├── datetime.php   — combined date+time+timezone input
│   │   ├── time.php       — time picker input
│   │   └── timezone.php   — timezone select input (AJAX-populated)
│   └── output/
│       └── datetime.php   — formats a DateTime value for display
└── tests/phpunit/integration/hypeTime/
    ├── BootstrapTest.php
    ├── TimeTest.php
    └── SetUserPreferencesTest.php
```

## Registered Routes

| Route      | Path             | Controller            | Middleware         |
|------------|------------------|-----------------------|--------------------|
| `timezones`| `/data/timezones`| `TimezoneProvider`    | `AjaxGatekeeper`   |

## Registered Hooks (Elgg 4.x `hooks` key)

| Hook                         | Type     | Handler                    | Purpose                              |
|------------------------------|----------|----------------------------|--------------------------------------|
| `usersettings:save`          | `user`   | `SetUserPreferences`       | Persists user's format/timezone prefs|
| `view_vars`, `input/date`    | —        | `ConfigureDatepicker`      | Injects JS datepicker format string  |
| `fields`, `object`           | —        | `AddFormField`             | Adds calendar fields to entity forms |

## Services

`posts.calendar` (registered externally by hypepost) — `CalendarService` is
used through this DI alias; hypetime provides the service class but the
container binding lives in hypepost.

## Entity Metadata (calendar)

`CalendarService` stores four metadata keys per entity per calendar boundary:

| Key                   | Type        | Notes                              |
|-----------------------|-------------|------------------------------------|
| `calendar_start`      | int         | Unix timestamp (local TZ)          |
| `calendar_start_utc`  | int         | Unix timestamp (UTC)               |
| `calendar_start_iso`  | string      | ISO 8601 string                    |
| `calendar_start_tz`   | string      | IANA timezone name                 |
| `calendar_end`        | int         | Unix timestamp (local TZ)          |
| `calendar_end_utc`    | int         | Unix timestamp (UTC)               |
| `calendar_end_iso`    | string      | ISO 8601 string                    |
| `calendar_end_tz`     | string      | IANA timezone name                 |

## Settings

| Key             | Default               | Scope       |
|-----------------|-----------------------|-------------|
| `format:time`   | `H:i`                 | site + user |
| `format:date`   | `M j, Y`              | site + user |
| `week:starts`   | `Mon`                 | site + user |
| `timezone`      | PHP default timezone  | site + user |

## Migration Notes (3.x → 4.x)

- `ElggPlugin::setUserSetting()` removed → replaced with `ElggUser::setPluginSetting()`
- `elgg_trigger_event_results()` (5.x API) → reverted to `elgg_trigger_plugin_hook()` (4.x)
- `hooks` key used instead of `events` in `elgg-plugin.php` (4.x declarative format)
- `start.php` and `manifest.xml` removed; all registration is declarative
- `Bootstrap::init()` replaces the init event handler
- `SetUserPreferences` was rewritten to use `ElggUser::setPluginSetting()` directly
