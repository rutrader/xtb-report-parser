<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class OpenApiFactory implements OpenApiFactoryInterface
{

    /** @var \ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface */
    private $decorated;

    /**
     * @param \ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface $decorated
     */
    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $path = $openApi->getPaths();

        $path->addPath('/api/stats', new PathItem(null, null, null, new Operation(
                    'get',
                    ['Stats'],
                    [
                        Response::HTTP_OK => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'profit_orders' => [
                                                'type' => 'integer',
                                                'example' => 10,
                                            ],
                                            'loss_orders' => [
                                                'type' => 'integer',
                                                'example' => 5,
                                            ],
                                            'buy_orders' => [
                                                'type' => 'integer',
                                                'example' => 12,
                                            ],
                                            'sell_orders' => [
                                                'type' => 'integer',
                                                'example' => 3,
                                            ],
                                            'pl' => [
                                                'type' => 'float',
                                                'example' => 100.2,
                                            ],
                                            'total_orders' => [
                                                'type' => 'integer',
                                                'example' => 15,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Retrieves the overall trading stats.'
                )
            ))
        ;

        $path->addPath('/api/performance/overall', new PathItem(null, null, null, new Operation(
                'get',
                ['Performance'],
                [
                    Response::HTTP_OK => [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'date' => [
                                            'type' => 'date',
                                            'example' => '2021-12-31',
                                        ],
                                        'net_profit' => [
                                            'type' => 'float',
                                            'example' => 100.12,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Retrieves the overall trading performance.',
                '',
                null, [], null, null,false,
            )
        ))
        ;

        return $openApi;
    }
}