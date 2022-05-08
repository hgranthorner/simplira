<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
	public function testCanExtractUrl(): void {
		$url = new Url('http://localhost:8000/thing?egg=foo');
		$this->assertEquals('8000', $url->port);
		$this->assertEquals('/thing', $url->path);
		$this->assertEquals('foo', $url->query['egg']);
		$this->assertEquals('egg=foo', $url->queryString);
	}
	
	public function testCanUpdateQuery(): void {
		$url = new Url('http://localhost:8000/thing?egg=foo');
		$url->updateQuery('egg', 'bar');
		$this->assertEquals('bar', $url->query['egg']);
	}

	public function testUpdateQueryUpdatesQueryString(): void {
		$url = new Url('http://localhost:8000/thing?egg=foo');
		$url->updateQuery('egg', 'bar');
		$this->assertEquals('egg=bar', $url->queryString);
	}

	public function testBuildsCorrectUrl(): void {
		$url = new Url('http://localhost:8000/thing?egg=foo');
		
		$url->updateQuery('egg', 'bar');
		$this->assertEquals('http://localhost:8000/thing?egg=bar', 
			$url->toString());

		$url->updateQuery('quq', 'bazz');
		$this->assertEquals('http://localhost:8000/thing?egg=bar&quq=bazz', 
			$url->toString());
	}
}