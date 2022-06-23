<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class SettingsService
{

	/**
	 * @param \Symfony\Component\Cache\Adapter\AdapterInterface $adapterInterface
	 */
	public function __construct(AdapterInterface $adapterInterface)
	{
		$this->cacheAdapter = $adapterInterface;
	}

	/**
	 *
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @param string $isoCode
	 * @return boolean
	 */
	public function setUserCurrency(UserInterface $user, string $isoCode)
	{
		$cacheKey = '_user_base_currency';

		$cached = $this->cacheAdapter->getItem($cacheKey);

		$cached->set($isoCode);
		$cached->expiresAfter(new \DateInterval('P1Y'));
		$this->cacheAdapter->save($cached);

		return true;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return null|string
	 */
	public function getUserCurrency(UserInterface $user)
	{
		$cacheKey = '_user_base_currency';

		$cached = $this->cacheAdapter->getItem($cacheKey);

		return $cached->isHit() ? $cached->get() : null;
	}

}
