<?php declare(strict_types=1);

namespace App\Websites;

use App\Contracts\WebsiteInterface;

class DnsSystemsWebsite implements WebsiteInterface
{
    public function getWebsiteUrl(): string
    {
        return 'https://wltest.dns-systems.net/';
    }

    public function getWebsiteName(): string
    {
        return 'DnsSystems';
    }

    public function getWrapperSelector(): string
    {
        return '.row-subscriptions > div';
    }

    public function getProductOptionTitleSelector(): string
    {
        return '.header h3';
    }

    public function getProductOptionDescriptionSelector(): string
    {
        return '.package-features ul li .package-name';
    }

    public function getProductOptionPriceSelector(): string
    {
        return '.package-features ul li .package-price .price-big';
    }

    public function getProductOptionDiscountSelector(): ?string
    {
        return '.package-features ul li .package-price p';
    }
}