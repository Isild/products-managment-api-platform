<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\AddCategoryToProductController;
use App\Controller\RemoveCategoryFromProductController;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    mercure: true,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    shortName: 'Product',
    operations: [
        new Get(
            name: 'get_product',
        ),
        new GetCollection(
            name: 'get_products',
        ),
        new Post(
            name: 'create_product',
        ),
        new Put(
            name: 'update_product',
        ),
        new Delete(
            name: 'delete_product',
        ),
        // new Patch(
        //     uriTemplate: 'products/{id}/add-category',
        //     controller: AddCategoryToProductController::class,
        //     name: 'add_category_to_product',
        //     // TODO: add properties to get documentation
        //     // extraProperties: [
        //     //     'openapi' => [
        //     //         'summary' => 'Remove a category from a product',
        //     //         'description' => 'Removes a specific category from a product by its ID.',
        //     //         'requestBody' => [
        //     //             'content' => [
        //     //                 'application/json' => [
        //     //                     'schema' => [
        //     //                         'type' => 'object',
        //     //                         'properties' => [
        //     //                             'categoryId' => [
        //     //                                 'type' => 'integer',
        //     //                                 'example' => 5,
        //     //                             ],
        //     //                         ],
        //     //                         'required' => ['categoryId'],
        //     //                     ],
        //     //                 ],
        //     //             ],
        //     //         ],
        //     //         'responses' => [
        //     //             '200' => [
        //     //                 'description' => 'Category removed successfully',
        //     //                 'content' => [
        //     //                     'application/json' => [
        //     //                         'example' => ['success' => true],
        //     //                     ],
        //     //                 ],
        //     //             ],
        //     //             '404' => [
        //     //                 'description' => 'Product or Category not found',
        //     //                 'content' => [
        //     //                     'application/json' => [
        //     //                         'example' => ['error' => 'Product or Category not found.'],
        //     //                     ],
        //     //                 ],
        //     //             ],
        //     //         ],
        //     //     ],
        //     // ]
        // ),
        // new Delete(
        //     uriTemplate: 'products/{id}/remove-category',
        //     controller: RemoveCategoryFromProductController::class,
        //     read: false,
        //     name: 'remove_category_from_product',
        //     // TODO: add properties to get documentation
        // ),
    ],
)]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ProductEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 250,
        maxMessage: 'Name can not be more than {{ limit }} characters.'
    )]
    #[Groups(['read', 'write'])]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\GreaterThan(0)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d{1,2})?$/',
        message: 'Value must have a maximum of 2 decimal places.'
    )]
    #[Groups(['read', 'write'])]
    private string $price;

    #[ORM\Column(type: 'datetime_immutable')]
    #[ApiProperty(writable: false)]
    #[Groups(['read'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[ApiProperty(writable: false)]
    #[Groups(['read'])]
    private ?DateTimeImmutable $updateAt = null;

    /** @var CategoryEntity[] */
    #[ORM\ManyToMany(targetEntity: CategoryEntity::class, inversedBy: 'products')]
    #[ApiProperty(writable: false)]
    #[Groups(['read'])]
    private iterable $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getCategories(): iterable
    {
        return $this->categories;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdateAt(): DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function addCategory(CategoryEntity $categoryEntity): self
    {
        if (!$this->categories->contains($categoryEntity)) {
            $this->categories[] = $categoryEntity;
            $categoryEntity->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(CategoryEntity $categoryEntity): self
    {
        if ($this->categories->contains($categoryEntity)) {
            $code = $categoryEntity->getCode();

            foreach ($this->categories as $key => $category) {
                if ($category->getCode() === $code) {
                    unset($this->categories[$key]);
                    return $this;
                }
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function createTimestamp(): void
    {
        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updateAt = $date;
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updateAt = new DateTimeImmutable();
    }
}
