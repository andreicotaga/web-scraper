<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\ProductOptionRepository;
use App\Service\ScrapeService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:web:fetch',
    description: 'Return product options ordered by annual product price.',
)]
class WebsiteDataCommand extends Command
{
    private ScrapeService $scrapeService;

    public function __construct(ScrapeService $scrapeService)
    {
        parent::__construct();

        $this->scrapeService = $scrapeService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productOptions = $this->scrapeService->getProductOptions();

        if (empty($productOptions)) {
            return Command::SUCCESS;
        }

        var_export(\json_encode($productOptions));

        return Command::SUCCESS;
    }
}
