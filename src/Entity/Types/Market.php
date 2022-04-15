<?php

namespace App\Entity\Types;

use App\Entity\Trades\History;
use App\Repository\Types\MarketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Hidehalo\Nanoid;

/**
 * @ORM\Entity(repositoryClass=MarketRepository::class)
 * @ORM\Table(name="`type_market`")
 */
class Market
{
	
	/**
	 * @var \Ramsey\Uuid\UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	private $alias;
	
	/**
	 * @ORM\Column(type="string", length=64)
	 * @var string
	 */
	private $token;
	
	/**
	 * @ORM\OneToMany(targetEntity=History::class, mappedBy="marketType")
	 * @var \App\Entity\Trades\History[]|\Doctrine\Common\Collections\ArrayCollection
	 */
	private $trades;
	
	public function __construct()
	{
		$nanoId = new Nanoid\Client();
		$this->token = $nanoId->generateId(64);
		$this->trades = new ArrayCollection();
	}
	
	/**
	 * @return string|null
	 */
	public function getId(): ?string
	{
		return $this->id;
	}
	
	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
	
	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName(string $name): self
	{
		$slugger = new AsciiSlugger();
		
		$this->name = $name;
		$this->alias = $slugger->slug($name);
		
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getAlias(): ?string
	{
		return $this->alias;
	}
	
	/**
	 * @param string $alias
	 * @return $this
	 */
	public function setAlias(string $alias): self
	{
		$this->alias = $alias;
		
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getToken(): ?string
	{
		return $this->token;
	}
	
	/**
	 * @param string $token
	 * @return $this
	 */
	public function setToken(string $token): self
	{
		$this->token = $token;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->name;
	}
	
	/**
	 * @return Collection<int, History>
	 */
	public function getTrades(): Collection
	{
		return $this->trades;
	}
	
	/**
	 * @param \App\Entity\Trades\History $trade
	 * @return $this
	 */
	public function addTrade(History $trade): self
	{
		if (!$this->trades->contains($trade)) {
			$this->trades[] = $trade;
			$trade->setMarketType($this);
		}
		
		return $this;
	}
	
	/**
	 * @param \App\Entity\Trades\History $trade
	 * @return $this
	 */
	public function removeTrade(History $trade): self
	{
		if ($this->trades->removeElement($trade)) {
			// set the owning side to null (unless already changed)
			if ($trade->getMarketType() === $this) {
				$trade->setMarketType(null);
			}
		}
		
		return $this;
	}
	
}