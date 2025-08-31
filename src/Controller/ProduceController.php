<?php

namespace App\Controller;

use App\Collection\FruitCollection as Fruits;
use App\Collection\VegetableCollection as Vegetables;
use App\Criteria\ProduceCriteria as Criteria;
use App\Dto\ProduceQueryParams as QueryParams;
use App\Repository\ProduceRepository as Repository;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produce', name: 'produce_')]
class ProduceController extends AbstractController
{
    #[Route(name: 'list', methods: Request::METHOD_GET)]
    public function list(
        Request $request,
        Repository $repository
    ): JsonResponse
    {
        $queryParams = QueryParams::fromQuery($request->query->all());

        $criteria = Criteria::fromQueryParams($queryParams);

        $data = $repository->matching($criteria);

        return $this->json($data, JsonResponse::HTTP_OK);
    }

    #[Route(name: 'add', methods: Request::METHOD_POST)]
    public function add(
        Request $request,
        Fruits $fruits,
        Vegetables $vegetables,
        StorageService $storageService
    ): JsonResponse
    {
        try {
            $rawData = $request->getContent();

            $storageService->handle($rawData, true);

            $storageService->storeCollections();

            $data = array_merge($fruits->toArray(), $vegetables->toArray());

            $response = $this->json($data, JsonResponse::HTTP_CREATED);
            
        } catch (\Throwable $th) {
            $response = $this->json(['message' => $th->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }
}
