<?php

declare(strict_types = 1);

namespace App\Entity\Contracts;

/**
 * Check if entity implements uuid functionality.
 */
interface Uniqable
{
	/**
	 * @return null|string
	 */
	public function getUuid(): ?string;
}