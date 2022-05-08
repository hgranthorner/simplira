<?php declare(strict_types=1);

class Url
{
	function __construct(string $url) {
		[
			'scheme' => $this->scheme,
			'query' => $this->queryString,
			'host' => $this->host,
			'port' => $this->port,
			'path' => $this->path,
		] = parse_url($url);
		parse_str($this->queryString, $this->query);
	}
}

?>