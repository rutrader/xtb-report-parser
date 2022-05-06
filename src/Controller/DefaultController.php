<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
	
	/**
	 * @param \Symfony\Component\Serializer\SerializerInterface $serializer
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(SerializerInterface $serializer): Response
	{
//		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		return $this->render('default/index.html.twig', [
			'user' => $serializer->serialize($this->getUser(), 'jsonld')
		]);
	}
	
}
