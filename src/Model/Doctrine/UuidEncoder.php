<?php

declare(strict_types = 1);

namespace App\Model\Doctrine;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Throwable;

class UuidEncoder
{
	/**
	 * @param  UuidInterface  $uuid
	 * @return string
	 */
	public function encode(UuidInterface $uuid): string
	{
		return gmp_strval(
			gmp_init(
				str_replace('-', '', $uuid->toString()),
				16
			),
			62
		);
	}
	
	/**
	 * @param  string  $encoded
	 * @return null|UuidInterface
	 */
	public function decode(string $encoded): ?UuidInterface
	{
		try {
			return Uuid::fromString(
				array_reduce(
					[20, 16, 12, 8],
					function ($uuid, $offset) {
						return substr_replace($uuid, '-', $offset, 0);
					},
					str_pad(
						gmp_strval(
							gmp_init($encoded, 62), 16
						),
						32,
						0,
						STR_PAD_LEFT
					)
				)
			);
		} catch (Throwable $exception) {
			return null;
		}
	}
}