<?php

namespace App\Entity\Users;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Entity\Trades\History;
use App\Repository\Users\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     "security"="is_granted('ROLE_ADMIN')"
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     normalizationContext={"groups"={"user:read"}},
 *     forceEager=false
 * )
 * @ApiFilter(PropertyFilter::class)
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	
	/**
	 * @Groups({"history:read"})
	 * @var \Ramsey\Uuid\UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;
	
	/**
	 * @Groups({"user:read", "history:item:get", "history:read"})
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @var string
	 */
	private $email;
	
	/**
	 * @ORM\Column(type="json")
	 * @var array
	 */
	private $roles = [];
	
	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $isVerified = false;
	
	/**
	 * @Groups({"user:read", "history:read", "history:item:get"})
	 * @ApiSubresource()
	 * @ORM\OneToMany(targetEntity=History::class, mappedBy="trader")
	 * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Trades\History[]
	 */
	private $trades;
	
	public function __construct()
	{
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
	public function getEmail(): ?string
	{
		return $this->email;
	}
	
	/**
	 * @param string $email
	 * @return $this
	 */
	public function setEmail(string $email): self
	{
		$this->email = $email;
		
		return $this;
	}
	
	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string)$this->email;
	}
	
	/**
	 * @deprecated since Symfony 5.3, use getUserIdentifier instead
	 */
	public function getUsername(): string
	{
		return (string)$this->email;
	}
	
	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';
		
		return array_unique($roles);
	}
	
	/**
	 * @param array $roles
	 * @return $this
	 */
	public function setRoles(array $roles): self
	{
		$this->roles = $roles;
		
		return $this;
	}
	
	/**
	 * @see \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
	{
		return $this->password;
	}
	
	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword(string $password): self
	{
		$this->password = $password;
		
		return $this;
	}
	
	/**
	 * Returning a salt is only needed, if you are not using a modern
	 * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
	{
		return null;
	}
	
	/**
	 * @see \Symfony\Component\Security\Core\User\UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}
	
	/**
	 * @return bool
	 */
	public function isVerified(): bool
	{
		return $this->isVerified;
	}
	
	/**
	 * @param bool $isVerified
	 * @return $this
	 */
	public function setIsVerified(bool $isVerified): self
	{
		$this->isVerified = $isVerified;
		
		return $this;
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
			$trade->setTrader($this);
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
			if ($trade->getTrader() === $this) {
				$trade->setTrader(null);
			}
		}
		
		return $this;
	}
	
}
