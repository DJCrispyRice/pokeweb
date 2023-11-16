<?php

declare(strict_types=1);

namespace App\Command\Maker;

use App\Exception\AutowiringException;
use App\Util\String\StringUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Service\Attribute\Required;
use Twig\Environment;

#[AsCommand(name: 'make:autowire', description: 'Adds the AutowireTrait')]
final class MakeAutowireCommand extends Command
{
    private const ARGUMENT_FQCN = 'fqcn';
    private const OPTION_ACCESSOR = 'getter-name';

    private string $projectDir;
    private Environment $twigEnvironement;

    #[Required]
    public function autowireProjectDir(string $projectDir = '/'): static
    {
        $this->projectDir = $projectDir;

        return $this;
    }

    #[Required]
    public function autowireEnvironement(Environment $environment): static
    {
        $this->twigEnvironement = $environment;

        return $this;
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This maker creates the AutowireTrait for the specified FQCN.')
            ->addArgument(
                static::ARGUMENT_FQCN,
                InputArgument::REQUIRED,
                'FQCN of the class to autowire.'
            )
            ->addOption(
                static::OPTION_ACCESSOR,
                'g',
                InputOption::VALUE_OPTIONAL,
                'Set the getter name instead of the generated one.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reflectionClass = new \ReflectionClass(
            static::sanitizeFqcn(
                (string) $input->getArgument(static::ARGUMENT_FQCN)
            )
        );
        $this->assertServiceIsAutowirable($reflectionClass);

        $namespace = $this->buildNamespace($reflectionClass);
        $absoluteFilePath = $this->projectDir
            . '/src/'
            . StringUtil::removeFirstPart(
                StringUtil::replaceSingle(
                    '\\',
                    '/',
                    $namespace
                ),
                'App/'
            );

        $classNameComponent = lcfirst($this->extractClassComponent($reflectionClass->getShortName()));
        $this->makeFileFromTemplate(
            $absoluteFilePath,
            createdFileName: "Autowire{$reflectionClass->getShortName()}Trait.php",
            templateParameters: [
                'namespace' => $namespace,
                'fqcn' => $reflectionClass->getName(),
                'class_name' => $reflectionClass->getShortName(),
                'property_name' => $classNameComponent,
                'autowire_method_name' => 'autowire' . ucfirst($classNameComponent),
                'parameter_name' => $classNameComponent,
                'getter_method_name' => $this->getAccessorName(
                    $reflectionClass->getShortName(),
                    $input->getOption(self::OPTION_ACCESSOR)
                ),
            ]
        );

        $output->writeln(
            'Service "' .
            $reflectionClass->getShortName()
            . '" was autowired at "'
            . $namespace
            . '" (in "' . $absoluteFilePath . '").'
        );

        return static::SUCCESS;
    }

    private function assertServiceIsAutowirable(\ReflectionClass $reflectionClass): void
    {
        if ($reflectionClass->isAbstract() && $reflectionClass->isInterface() === false) {
            throw new AutowiringException(
                'No Abstract class should be autowired; "'
                . $reflectionClass->getShortName() . '" is not autowirable.'
            );
        }
    }

    private function buildNamespace(\ReflectionClass $reflectionClass): string
    {
        return 'App\\Autowire\\'
            . (
                str_starts_with($reflectionClass->getNamespaceName(), 'App\\')
                ? StringUtil::removeFirstPart($reflectionClass->getNamespaceName(), 'App\\')
                : ('Dependencies\\' . $reflectionClass->getNamespaceName())
            );
    }

    private function extractClassComponent(string $className): string
    {
        return StringUtil::removeLastPart($className, 'Interface');
    }

    private function getAccessorName(string $className, mixed $getterName): string
    {
        return \is_string($getterName)
            ? $getterName
            : ('get' . $this->extractClassComponent($className));
    }

    private function makeFileFromTemplate(
        string $absoluteFilePath,
        string $createdFileName,
        array $templateParameters
    ): void {
        $this->createDirectory($absoluteFilePath);

        $absoluteFileName = StringUtil::removeLastPart($absoluteFilePath, '/') . '/' . $createdFileName;
        if (file_exists($absoluteFileName)) {
            throw new AutowiringException('File "' . $absoluteFileName . '" already exists');
        }

        if (
            file_put_contents(
                $absoluteFileName,
                $this
                    ->twigEnvironement
                    ->render('_dev/maker/autowire-trait.php.twig', $templateParameters)
            ) === false
        ) {
            throw new AutowiringException('File "' . $absoluteFileName . '" could not be created.');
        }
    }

    private function createDirectory(string $absoluteFilePath): void
    {
        if (is_dir($absoluteFilePath)) {
            return;
        }

        (new Filesystem())->mkdir($absoluteFilePath);
    }

    private static function sanitizeFqcn(string $fqcn): string
    {
        return StringUtil::removeFirstPart($fqcn, '\\');
    }
}
