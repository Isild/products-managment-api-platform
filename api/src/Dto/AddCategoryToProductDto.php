<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AddCategoryToProductDto
{
    #[Assert\NotBlank]
    public int $categoryId;
}
