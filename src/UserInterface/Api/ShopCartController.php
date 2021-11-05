<?php
namespace App\UserInterface\Api;

use App\Application\UseCase\Command\ShopCart\AddToShopCart\AddToShopCartCommand;
use App\Application\UseCase\Command\ShopCart\Create\CreateCommand;
use App\Application\UseCase\Command\ShopCart\RemoveFromShopCart\RemoveFromShopCartCommand;
use App\Domain\SharedKernel\Exception\DomainConsistencyException;
use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopCartController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/shop-cart/create-shop-cart", name="shop_cart_api_shop_cart_create_shop_cart", methods={"POST"})
     */
    public function createShopCart(Request $request): JsonResponse
    {
        $uuid = (string)(Uuid::uuid4());

        $createShopCartCommand = new CreateCommand($uuid);

        try {
            $this->commandBus->handle($createShopCartCommand);
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

        return new JsonResponse(
            [
                'status' => 'ok',
                'shop_cart_uuid' => $uuid,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/shop-cart/add-to-shop-cart", name="shop_cart_api_shop_cart_add_to_shop_cart", methods={"PUT"})
     */
    public function addToShopCart(Request $request): JsonResponse
    {
        $addToShopCartCommand = new AddToShopCartCommand(
            (string)$request->get('shop_cart_uuid', ''),
            (string)$request->get('product_uuid', ''),
            (int)$request->get('quantity', '')
        );

        try {
            $this->commandBus->handle($addToShopCartCommand);
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
     * @Route("/shop-cart/remove-from-shop-cart", name="shop_cart_api_shop_cart_add_to_shop_cart", methods={"DELETE"})
     */
    public function removeFromShopCart(Request $request): JsonResponse
    {
        $removeFromShopCartCommand = new RemoveFromShopCartCommand(
            (string)$request->get('shop_cart_uuid', ''),
            (string)$request->get('product_uuid', '')
        );

        try {
            $this->commandBus->handle($removeFromShopCartCommand);
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