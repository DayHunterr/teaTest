<?php

namespace App\ApiNewsClient;

interface NewsClientInterface
{
    public function getName(): string;
    public function import(): int;
}