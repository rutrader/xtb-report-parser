<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class LoginController extends AbstractController
{
	
	/**
	 * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(AuthenticationUtils $authenticationUtils): Response
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		
		$lastUsername = $authenticationUtils->getLastUsername();
		
		return $this->render('login/index.html.twig', [
			'last_username' => $lastUsername,
			'error'         => $error,
		]);
	}
	
}
