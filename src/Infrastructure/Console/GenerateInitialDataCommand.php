<?php
declare(strict_types=1);

namespace App\Infrastructure\Console;


use App\Domain\Product\ProductCreator;
use App\Infrastructure\ORM\Flusher;
use Faker\Factory;
use Money\Money;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function FMW\uid;

final class GenerateInitialDataCommand extends Command
{
    protected static               $defaultName = 'app:generate-data';
    private ProductCreator         $productCreator;
    private Flusher                $flusher;

    public function __construct(
        ProductCreator $productCreator,
        Flusher $flusher
    )
    {
        parent::__construct(null);
        $this->productCreator = $productCreator;
        $this->flusher        = $flusher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            /** @var string $title */
            $title = $faker->words(2, true);
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->productCreator->create(
                uid(),
                $title,
                Money::RUB(random_int(100, 5000) * 100)
            );
        }

        $this->flusher->flush();
        return 0;
    }
}
