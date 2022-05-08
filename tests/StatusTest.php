<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
	public function testCanValidateStatus(): void
	{
		$status = new Status('to_do');
		$this->assertTrue($status->isValid());
		$status = new Status('todo');
		$this->assertFalse($status->isValid());
		$status = new Status('in_progress');
		$this->assertTrue($status->isValid());
		$status = new Status('in progress');
		$this->assertFalse($status->isValid());
	}

	public function testDisplayNameWorks(): void
	{
		$status = new Status('to_do');
		$this->assertEquals('to do', $status->displayName());
		$status = new Status('done');
		$this->assertEquals('done', $status->displayName());
		$status = new Status('not_real_status');
		$this->assertEquals('not real status', $status->displayName());
	}
}
