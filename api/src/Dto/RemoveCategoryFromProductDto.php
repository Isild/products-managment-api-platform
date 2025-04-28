<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RemoveCategoryFromProductDto
{
    #[Assert\NotBlank]
    public int $categoryId;
}
