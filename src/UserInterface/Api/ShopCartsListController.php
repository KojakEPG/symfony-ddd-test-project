<?php

declare(strict_types=1);

namespace App\UserInterface\Api;

use App\Application\UseCase\Query\ShopCart\GetShopCarts\GetShopCartsQuery;
use App\Domain\ShopCart\Repository\Pagination;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopCartsListController extends AbstractController
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/shop-cart/list", name="shop_carts_api_shop_carts_list", methods={"GET"})
     */
    public function getShopCartsList(Request $request): JsonResponse
    {
        $pagination = new Pagination(
            (int)$request->get('page', 1),
            (int)$request->get('per_page', Pagination::DEFAULT_PER_PAGE)
        );

        $results = $this->queryBus->handle(new GetShopCartsQuery($pagination));

        return new JsonResponse(['data' => $results], Response::HTTP_OK);
    }
}
