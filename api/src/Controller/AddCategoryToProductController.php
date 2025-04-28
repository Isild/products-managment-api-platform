<?php

namespace App\Controller;

use App\Dto\AddCategoryToProductDto;
use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

#[AsController]
class AddCategoryToProductController
{
    #[Route('/api/products/{id}/add-category', name: 'add_category_to_product', methods: ['PATCH'])]
    public function __invoke(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): JsonResponse {
        /** @var AddCategoryToProductDto $data */
        $data = $serializer->deserialize($request->getContent(), AddCategoryToProductDto::class, 'json');

        $product = $entityManager->getRepository(ProductEntity::class)->find($id);
        $category = $entityManager->getRepository(CategoryEntity::class)->find($data->categoryId);

        if (!$product || !$category) {
            return new JsonResponse(['error' => 'Product or Category not found.'], 404);
        }

        $product->addCategory($category);
        $entityManager->flush();

        $logger->info('Compleated assign category: ' . $category->getCode() . ' to product: ' . $product->getName() . '.');

        //TODO: add mail notification

        return new JsonResponse(['success' => true]);
    }
}
