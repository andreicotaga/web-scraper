<?php declare(strict_types=1);

namespace App\Command;

use App\Service\ScrapeService;
use App\Websites\DnsSystemsWebsite;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:web:scrape',
    description: 'Scrapes wltest.dns-systems.net website and stores product options in database.',
)]
class WebsiteScraperCommand extends Command
{
    private ScrapeService $scrapeService;

    public function __construct(ScrapeService $scrapeService)
    {
        parent::__construct();

        $this->scrapeService = $scrapeService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->scrapeService->handle(new DnsSystemsWebsite());

            $io->success(sprintf('%s was successfully scraped!', DnsSystemsWebsite::class));
        } catch (\Exception $exception) {
            $io->error(sprintf('Something went wrong. Check error message: %s', $exception->getMessage()));
        }

        return Command::SUCCESS;
    }
}
