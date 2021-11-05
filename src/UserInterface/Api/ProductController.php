<?php

declare(strict_types=1);

namespace App\UserInterface\Api;

use App\Application\UseCase\Command\Product\ChangeName\ChangeNameCommand;
use App\Application\UseCase\Command\Product\ChangePrice\ChangePriceCommand;
use App\Application\UseCase\Command\Product\Create\CreateCommand;
use App\Application\UseCase\Command\Product\Remove\RemoveCommand;
use App\Domain\SharedKernel\Exception\DomainConsistencyException;
use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/products-catalog/create-product", name="products_catalog_api_product_create_product", methods={"POST"})
     */
    public function createProduct(Request $request): JsonResponse
    {
        $createProductCommand = new CreateCommand(
            (string)(Uuid::uuid4()),
            (string)$request->get('product_name', ''),
            (string)$request->get('product_description', ''),
            (float)$request->get('product_amount', 0.0),
            (string)$request->get('product_currency_code', '')
        );

        try {
            $this->commandBus->handle($createProductCommand);
        } catch (DomainConsistencyException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['status' => 'ok'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/products-catalog/change-product-name", name="products_catalog_api_product_change_product_name", methods={"PUT"})
     */
    public function changeProductName(Request $request): JsonResponse
    {
        $changeProductName = new ChangeNameCommand(
            (string)$request->get('product_uuid', ''),
            (string)$request->get('product_name', '')
        );

        try {
            $this->commandBus->handle($changeProductName);
        } catch (DomainConsistencyException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (NotFoundException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['status' => 'ok'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/products-catalog/change-product-price", name="products_catalog_api_product_change_product_price", methods={"PUT"})
     */
    public function changeProductPrice(Request $request): JsonResponse
    {
        $changeProductPriceCommand = new ChangePriceCommand(
            (string)$request->get('product_uuid', ''),
            (float)$request->get('product_amount', 0.0),
            (string)$request->get('product_currency_code', '')
        );

        try {
            $this->commandBus->handle($changeProductPriceCommand);
        } catch (DomainConsistencyException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (NotFoundException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['status' => 'ok'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/products-catalog/remove-product", name="products_catalog_api_product_remove_product", methods={"DELETE"})
     */
    public function removeProduct(Request $request): JsonResponse
    {
        $removeProductCommand = new RemoveCommand((string)$request->get('product_uuid', ''));

        try {
            $this->commandBus->handle($removeProductCommand);
        } catch (DomainConsistencyException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (NotFoundException $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'error_msg' => $exception->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['status' => 'ok'], Response::HTTP_NO_CONTENT);
    }
}