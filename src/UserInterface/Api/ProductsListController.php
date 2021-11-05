<?php

declare(strict_types=1);

namespace App\UserInterface\Api;

use App\Application\UseCase\Query\Product\GetProducts\GetProductsQuery;
use App\Domain\Product\Repository\Filters;
use App\Domain\Product\Repository\Pagination;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsListController extends AbstractController
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/products-catalog/list", name="products_catalog_api_products_list", methods={"GET"})
     */
    public function getProductsList(Request $request): JsonResponse
    {
        $filters = new Filters(false);

        $pagination = new Pagination(
            (int)$request->get('page', 1),
            (int)$request->get('per_page', Pagination::DEFAULT_PER_PAGE)
        );

        $results = $this->queryBus->handle(new GetProductsQuery($filters, $pagination));

        return new JsonResponse(['data' => $results], Response::HTTP_OK);
    }
}
