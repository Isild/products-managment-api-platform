<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    mercure: true,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class CategoryEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 10,
        maxMessage: 'Name can not be more than {{ limit }} characters.'
    )]
    #[Groups(['read', 'write'])]
    private string $code;

    #[ORM\Column(type: 'datetime_immutable')]
    #[ApiProperty(writable: false)]
    #[Groups(['read'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[ApiProperty(writable: false)]
    #[Groups(['read'])]
    private DateTimeImmutable $updateAt;

    /** @var ProductEntity[] */
    #[ORM\ManyToMany(targetEntity: ProductEntity::class, mappedBy: 'categories')]
    #[ApiProperty(writable: false)]
    private iterable $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdateAt(): DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(ProductEntity $productEntity): self
    {
        if (!$this->products->contains($productEntity)) {
            $this->products[] = $productEntity;
            $productEntity->addCategory($this);
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
