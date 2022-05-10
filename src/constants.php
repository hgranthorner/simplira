<?php

const STRFTIME_FORMAT = '%Y%m%d%H%M%S';

enum Priority: int
{
	case High = 1;
	case Medium = 2;
	case Low = 3;

	public function toString(): string {
		return match($this) {
			Priority::High => 'High',
			Priority::Medium => 'Medium',
			Priority::Low => 'Low',
		};
	}
}
