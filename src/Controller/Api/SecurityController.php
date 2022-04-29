<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class SecurityController extends AbstractController
{
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function check(): JsonResponse
	{
		return $this->json([], $this->isGranted('IS_AUTHENTICATED_FULLY') ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
	}
	
}
