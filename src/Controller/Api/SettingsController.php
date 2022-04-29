<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class SettingsController extends AbstractController
{
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function clearHistory(): Response
	{
		return $this->json([
			'message' => 'Welcome to your new controller!',
			'path' => 'src/Controller/Api/SettingsController.php',
		]);
	}
	
}
