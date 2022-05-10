<?php

namespace App\Entity\Trades;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Entity\Types\Market;
use App\Entity\Types\Order;
use App\Entity\Users\User;
use App\Repository\Trades\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *     "get"={
 *              "normalization_context"={"groups"={"history:read", "history:item:get"}},
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *          },
 *     },
 *     collectionOperations={"get" = {
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')"
 *     }},
 *     normalizationContext={"groups"={"history:read"}}
 * )
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(SearchFilter::class, properties={
 *     "symbol": "partial",
 *     "trader.id": "exact"
 *     })
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 * @ORM\Table(name="trades_history")
 */
class History
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
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	private $symbol;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="float")
	 * @var float
	 */
	private $lots;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="datetime_immutable")
	 * @var \DateTimeImmutable
	 */
	private $openedAt;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="float")
	 * @var float
	 */
	private $openPrice;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="datetime_immutable")
	 */
	private $closedAt;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="float")
	 */
	private $closedPrice;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="float", options={"default: 0.0"})
	 * @var float
	 */
	private $profit;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="float", options={"default: 0.0"})
	 * @var float
	 */
	private $netProfit;
	
	/**
	 * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="trades")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \App\Entity\Types\Order
	 */
	private $orderType;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\ManyToOne(targetEntity=Market::class, inversedBy="trades")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \App\Entity\Types\Market
	 */
	private $marketType;
	
	/**
	 * @Groups({"history:read"})
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trades")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $trader;
	
	/**
	 * @Groups({"history:read", "user:read"})
	 * @ORM\Column(type="datetime_immutable", nullable=true)
	 * @var \DateTimeImmutable
	 */
	private $importedAt;
	
	public function __construct()
	{
		$this->importedAt = new \DateTimeImmutable;
		$this->profit = 0.0;
		$this->netProfit = 0.0;
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
	public function getSymbol(): ?string
	{
		return $this->symbol;
	}
	
	/**
	 * @param string $symbol
	 * @return $this
	 */
	public function setSymbol(string $symbol): self
	{
		$this->symbol = $symbol;
		
		return $this;
	}
	
	/**
	 * @return float
	 */
	public function getLots(): ?float
	{
		return $this->lots;
	}
	
	/**
	 * @param bool $lots
	 * @return $this
	 */
	public function setLots(bool $lots): self
	{
		$this->lots = $lots;
		
		return $this;
	}
	
	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getOpenedAt(): ?\DateTimeImmutable
	{
		return $this->openedAt;
	}
	
	/**
	 * @param \DateTimeImmutable $openedAt
	 * @return $this
	 */
	public function setOpenedAt(\DateTimeImmutable $openedAt): self
	{
		$this->openedAt = $openedAt;
		
		return $this;
	}
	
	/**
	 * @return bool|null
	 */
	public function getOpenPrice(): ?bool
	{
		return $this->openPrice;
	}
	
	/**
	 * @param bool $openPrice
	 * @return $this
	 */
	public function setOpenPrice(bool $openPrice): self
	{
		$this->openPrice = $openPrice;
		
		return $this;
	}
	
	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getClosedAt(): ?\DateTimeImmutable
	{
		return $this->closedAt;
	}
	
	/**
	 * @param \DateTimeImmutable $closedAt
	 * @return $this
	 */
	public function setClosedAt(\DateTimeImmutable $closedAt): self
	{
		$this->closedAt = $closedAt;
		
		return $this;
	}
	
	/**
	 * @return float|null
	 */
	public function getClosedPrice(): ?float
	{
		return $this->closedPrice;
	}
	
	/**
	 * @param float $closedPrice
	 * @return $this
	 */
	public function setClosedPrice(float $closedPrice): self
	{
		$this->closedPrice = $closedPrice;
		
		return $this;
	}
	
	/**
	 * @return float|null
	 */
	public function getProfit(): ?float
	{
		return $this->profit;
	}
	
	/**
	 * @param float $profit
	 * @return $this
	 */
	public function setProfit(float $profit): self
	{
		$this->profit = $profit;
		
		return $this;
	}
	
	/**
	 * @return float|null
	 */
	public function getNetProfit(): ?float
	{
		return $this->netProfit;
	}
	
	/**
	 * @param float $netProfit
	 * @return $this
	 */
	public function setNetProfit(float $netProfit): self
	{
		$this->netProfit = $netProfit;
		
		return $this;
	}
	
	/**
	 * @return \App\Entity\Types\Order|null
	 */
	public function getType(): ?Order
	{
		return $this->orderType;
	}
	
	/**
	 * @param \App\Entity\Types\Order|null $orderType
	 * @return $this
	 */
	public function setOrderType(?Order $orderType): self
	{
		$this->orderType = $orderType;
		
		return $this;
	}
	
	/**
	 * @return \App\Entity\Types\Market|null
	 */
	public function getMarketType(): ?Market
	{
		return $this->marketType;
	}
	
	/**
	 * @param \App\Entity\Types\Market|null $marketType
	 * @return $this
	 */
	public function setMarketType(?Market $marketType): self
	{
		$this->marketType = $marketType;
		
		return $this;
	}
	
	/**
	 * @return \App\Entity\Users\User|null
	 */
	public function getTrader(): ?User
	{
		return $this->trader;
	}
	
	/**
	 * @param \App\Entity\Users\User|null $trader
	 * @return $this
	 */
	public function setTrader(?User $trader): self
	{
		$this->trader = $trader;
		
		return $this;
	}
	
	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getImportedAt(): ?\DateTimeImmutable
	{
		return $this->importedAt;
	}
	
	/**
	 * @param \DateTimeImmutable $importedAt
	 * @return $this
	 */
	public function setImportedAt(\DateTimeImmutable $importedAt): self
	{
		$this->importedAt = $importedAt;
		
		return $this;
	}
	
}
