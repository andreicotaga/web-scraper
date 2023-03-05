<?php declare(strict_types=1);

namespace App\Service;

use App\Contracts\WebsiteInterface;
use App\Entity\ProductOption;
use App\Repository\ProductOptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class ScrapeService
{
    private EntityManagerInterface $entityManager;

    private ProductOptionRepository $productOptionRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductOptionRepository $productOptionRepository)
    {
        $this->entityManager = $entityManager;
        $this->productOptionRepository = $productOptionRepository;
    }

    public function handle(WebsiteInterface $website): void
    {
        $this->truncateTable();

        $client = Client::createChromeClient(null, [
            '--remote-debugging-port=9222',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--headless'
        ]);

        $crawler = $client->request('GET', $website->getWebsiteUrl());

        $crawler->filter($website->getWrapperSelector())->each(function (Crawler $crawler) use ($website) {
            $productOption = new ProductOption();

            $productOption->setTitle(
                $crawler->filter($website->getProductOptionTitleSelector())->text()
            );

            $productOption->setDescription(
                $crawler->filter($website->getProductOptionDescriptionSelector())->text()
            );

            $productOption->setPrice(
                (float) filter_var(
                    $crawler->filter($website->getProductOptionPriceSelector())->text(),
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                )
            );

            if ($crawler->filter($website->getProductOptionDiscountSelector())->count()) {
                $productOption->setDiscount(
                    (float) filter_var(
                        $crawler->filter($website->getProductOptionDiscountSelector())->text(),
                        FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION
                    )
                );
            }

            $this->entityManager->persist($productOption);
        });

        $this->entityManager->flush();
    }

    public function getProductOptions(): array
    {
        $productOptions = $this->productOptionRepository->findBy([], ['price' => 'DESC']);

        return \array_map(function($productOption) {
            return [
                'title' => $productOption->getTitle(),
                'description' => $productOption->getDescription(),
                'price' => $productOption->getDiscount() === 0 ? $productOption->getPrice() * 12 : $productOption->getPrice(),
                'discount' => $productOption->getDiscount(),
            ];
        }, $productOptions);
    }

    private function truncateTable(): void
    {
        $productOptions = $this->productOptionRepository->findAll();

        foreach ($productOptions as $option) {
            $this->entityManager->remove($option);
        }

        $this->entityManager->flush();
    }
}