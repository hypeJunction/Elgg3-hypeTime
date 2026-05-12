<?php

namespace hypeJunction\Time;

use ElggEntity;
use hypeJunction\Fields\Field;
use Symfony\Component\HttpFoundation\ParameterBag;

class TimezoneField extends Field {

	/**
     * @param ElggEntity $entity
     * @param mixed $context
     * @return mixed
     */
    public function isVisible(ElggEntity $entity, $context = null) {
		$params = [
			'entity' => $entity,
		];

		$enabled = elgg()->hooks->trigger(
			'uses:timezone',
			"$entity->type:$entity->subtype",
			$params,
			false
		);

		if (!$enabled) {
			return false;
		}

		return parent::isVisible($entity, $context);
	}

	/**
     * @param ElggEntity $entity
     * @param ParameterBag $parameters
     */
    public function save(ElggEntity $entity, ParameterBag $parameters) {
		// The value is set with one of the other fields
	}

	/**
     * @param ElggEntity $entity
     * @return mixed
     */
    public function retrieve(ElggEntity $entity) {
		$svc = elgg()->{'posts.calendar'};

		/* @var $svc CalendarService */

		$start = $svc->getCalendarStart($entity);
		if (!$start) {
			return null;
		}

		return $start->getTimezone();
	}
}