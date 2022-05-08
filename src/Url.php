<?php declare(strict_types=1);

class Url
{
	public ?string $scheme;
	public ?string $queryString;
	public ?string $host;
	public ?int $port;
	public ?string $path;
	public array $query = [];

	function __construct(string $url) {
		@[
			'scheme' => $this->scheme,
			'query' => $this->queryString,
			'host' => $this->host,
			'port' => $this->port,
			'path' => $this->path,
		] = parse_url($url);
		
		parse_str($this->queryString ?? '', $this->query);
	}

	public function updateQuery(string $key, mixed $value): void {
		$this->query[$key] = $value;
		$this->queryString = http_build_query($this->query);
	}

	public function toString(): string
	{
		return "{$this->scheme}://{$this->host}:{$this->port}{$this->path}?{$this->queryString}";
	}
}
