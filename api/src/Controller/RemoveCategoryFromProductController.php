<?php

namespace App\Controller;

use App\Dto\RemoveCategoryFromProductDto;
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
class RemoveCategoryFromProductController
{
    #[Route('/api/products/{id}/remove-category', name: 'remove_category_from_product', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): JsonResponse {
        /** @var RemoveCategoryFromProductDto $data */
        $data = $serializer->deserialize($request->getContent(), RemoveCategoryFromProductDto::class, 'json');

        $product = $entityManager->getRepository(ProductEntity::class)->find($id);
        $category = $entityManager->getRepository(CategoryEntity::class)->find($data->categoryId);

        if (!$product || !$category) {
            return new JsonResponse(['error' => 'Product or Category not found.'], 404);
        }

        $product->removeCategory($category);
        $entityManager->flush();

        $logger->info('Compleated remove category: ' . $category->getCode() . ' from product: ' . $product->getName() . '.');

        //TODO: add mail notification

        return new JsonResponse(['success' => true]);
    }
}
