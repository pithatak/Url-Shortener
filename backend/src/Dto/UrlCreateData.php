<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UrlCreateData
{
    #[Assert\NotBlank(message: 'URL is required')]
    #[Assert\Url(message: 'This is not a valid URL')]
    public string $url;

    #[Assert\Length(max: 50, maxMessage: 'Alias cannot be longer than {{ limit }} characters')]
    public ?string $alias = null;

    public ?string $expire = '1h';

    public bool $isPublic = false;

    public $session;
}

