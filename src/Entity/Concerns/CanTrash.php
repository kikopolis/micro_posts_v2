<?php

namespace App\Entity\Concerns;

use App\Entity\Contracts\Trashable;
use DateTime;
use DateTimeInterface;

trait CanTrash
{
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var DateTimeInterface
	 */
	private $trashedAt;
	
	/**
	 * @param  null|DateTimeInterface  $trashedAt
	 * @return $this|Trashable
	 */
	public function setTrashedAt(?DateTimeInterface $trashedAt): Trashable
	{
		$this->{$this->getTrashedAtColumn()} = $trashedAt;
		
		return $this;
	}
	
	/**
	 * @return null|DateTimeInterface
	 */
	public function getTrashedAt(): ?DateTimeInterface
	{
		return $this->{$this->getTrashedAtColumn()};
	}
	
	/**
	 * @return null|string
	 */
	public function getTrashedAtColumn(): ?string
	{
		return defined('static::TRASHED_AT') ? static::TRASHED_AT : 'trashedAt';
	}
	
	public function isTrashed(): bool
	{
		return !is_null($this->{$this->getTrashedAtColumn()});
	}
	
	/**
	 * Send an entity to trash.
	 * @return $this|Trashable
	 */
	public function trash(): Trashable
	{
		$this->setTrashedAt(new DateTime());
		
		$this->em->flush();
		
		return $this;
	}
	
	/**
	 * @return $this|Trashable
	 */
	public function restore(): Trashable
	{
		$this->setTrashedAt(null);
		
		$this->em->flush();
		
		return $this;
	}
	
	/**
	 * Completely destroy a soft deleted entity.
	 */
	public function destroy(): void
	{
		if (!$this->isTrashed()) {
			// If this entity is not yet soft deleted, return without modification.
			return;
		}
		
		$this->em->remove($this);
		$this->em->flush();
	}
}