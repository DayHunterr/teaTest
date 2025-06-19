<?php

namespace App\ApiClient;

interface NewsClientInterface
{
    public function getName(): string;
    public function import(): int;
}