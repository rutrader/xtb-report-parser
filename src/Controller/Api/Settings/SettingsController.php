<?php

namespace App\Controller\Api\Settings;

use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class SettingsController extends AbstractController
{


	/**
	 * @var \App\Service\SettingsService
	 */
	private $settingsService;


	/**
	 * @param \App\Service\SettingsService $settingsSevice
	 */
	public function __construct(SettingsService $settingsService)
	{
		$this->settingsService = $settingsService;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getCurrency(): Response
	{
		return $this->json(['code' => $this->settingsService->getUserCurrency($this->getUser()) ?? 'USD'], 200);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return bool|\Symfony\Component\HttpFoundation\Response
	 */
	public function setCurrency(Request $request): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		if(!$request->isMethod('POST')) {
			return $this->json(['OOPS!'], Response::HTTP_METHOD_NOT_ALLOWED);
		}

		if(!$code = $request->request->get('code')) {
			return $this->json(['Currency was not found'], Response::HTTP_NOT_FOUND);
		}

		return $this->settingsService->setUserCurrency($this->getUser(), $code) ? $this->json(['OK']) : $this->json['Error'];
	}

}
