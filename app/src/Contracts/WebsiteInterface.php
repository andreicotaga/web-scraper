<?php declare(strict_types=1);

namespace App\Contracts;

interface WebsiteInterface
{
    public function getWebsiteUrl(): string;

    public function getWebsiteName(): string;

    public function getWrapperSelector(): string;

    public function getProductOptionTitleSelector(): string;

    public function getProductOptionDescriptionSelector(): string;

    public function getProductOptionPriceSelector(): string;

    public function getProductOptionDiscountSelector(): ?string;
}