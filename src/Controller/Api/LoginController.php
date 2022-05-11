<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class LoginController extends AbstractController
{
	
	/**
	 * @Route("/login", name="")
	 */
	public function index(IriConverterInterface $iriConverter): Response
	{
		if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->json([
				'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
			], 400);
		}
		
		return new Response(null, 204, [
			'location' => $iriConverter->getIriFromItem($this->getUser())
		]);
	}
	
	/**
	 * @Route("/logout", name="app_json_logout")
	 * @throws \Exception
	 */
	public function logout()
	{
		throw new \Exception('should not be reached');
	}
	
}
