<?php declare(strict_types=1);

class Status {
	public static array $statuses = ["to_do", "in_progress", "done"];

	function __construct(public string $rawString) {}

	public function displayName(): string {
		return implode(' ', explode('_', $this->rawString));
	} 

	public function isValid(): bool {
		return is_int(array_search($this->rawString, Status::$statuses));
	}
}

?>