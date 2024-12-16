<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Command;

use Illuminate\Filesystem\Filesystem;
use MCampbell508\CustomRectorRules\Config\RuleConfig;
use MCampbell508\CustomRectorRules\Config\RuleType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\RuleDocGenerator\MarkdownDiffer\MarkdownDiffer;

#[AsCommand('generate:docs', 'Generate Docs')]
final class GenerateDocs extends Command
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly MarkdownDiffer $markdownDiffer,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configFile = __DIR__ . '/../../config/rule_configuration.php';

        if (! file_exists($configFile)) {
            $output->writeln('Config file not found');

            return Command::FAILURE;
        }

        $rules = require_once $configFile;

        foreach ($rules as $ruleConfig) {
            $this->processRule($ruleConfig, $output);
        }

        $summaryOutputPath = __DIR__ . '/../../docs/rector_rules_overview.md';
        $this->generateSummaryMarkdown($rules, $summaryOutputPath, $output);

        $output->writeln('<info>Documentation generation completed successfully.</info>');

        return Command::SUCCESS;
    }

    private function processRule(RuleConfig $ruleConfig, OutputInterface $output): void
    {
        $exportPath = __DIR__ . '/../../docs/' . $ruleConfig->ruleDocsConfig->exportPath;
        $ruleType = $ruleConfig->ruleType->value;

        $output->writeln('<info>Processing rule: ' . $ruleConfig->className . '</info>');
        $output->writeln('Export Path: ' . $exportPath);
        $output->writeln('Rule Type: ' . $ruleType);

        foreach ($ruleConfig->fixtures as $fixture) {
            $output->writeln('Processing fixtures from: ' . $fixture->path);
        }

        $this->generateMarkdown($ruleConfig, $output);
    }

    private function generateMarkdown(RuleConfig $ruleConfig, OutputInterface $output): void
    {
        $ruleDocsConfigTags = array_map(fn($tag) => "`{$tag}`", $ruleConfig->ruleDocsConfig->tags);

        $tags = !empty($ruleDocsConfigTags) ? '**Tags:** ' . implode(', ', $ruleDocsConfigTags) . '.' : '';

        $markdown = <<<MARKDOWN
# {$ruleConfig->className}

{$tags}

## Description

{$ruleConfig->ruleDocsConfig->description}

MARKDOWN;

        if ($ruleConfig->ruleType->isConfigurable()) {
            $markdown .= "\n\n" . <<<MARKDOWN
> [!NOTE]
> This rule is configurable!
MARKDOWN;
        }

        $markdown .= "\n\n" . <<<MARKDOWN

## Examples

MARKDOWN;

        $fixtureCount = 1;

        foreach ($ruleConfig->fixtures as $fixture) {
            $markdown .= "\n### Example Set $fixtureCount";

            if ($fixture->exampleName) {
                $markdown .= ' - ' . $fixture->exampleName;
            }

            $fixtureCount++;

            if (!is_null($fixture->configPath) && $this->filesystem->exists($fixture->configPath)) {
                $markdown .= "\n#### Configuration\n\n";
                $markdown .= <<<MARKDOWN
```php
{$this->filesystem->get($fixture->configPath)}
```
MARKDOWN;
            }

            $files = $this->filesystem->files($fixture->path);

            $exampleCount = 1;

            foreach ($files as $file) {
                if (!str_ends_with('php.inc', $file->getExtension())) {
                    continue;
                }

                $content = $file->getContents();
                $markdown .= "\n\n#### Example $exampleCount: " . $file->getFilename() . "\n\n";

                $exampleCount++;

                if (str_contains($content, '-----')) {
                    [$before, $after] = explode('-----', $content);
                    $badCode = trim($before);
                    $goodCode = trim($after);

                    $markdown .= "\n\n" . $this->markdownDiffer->diff($badCode, $goodCode);
                } else {
                    $markdown .= <<<MARKDOWN
```php
{$content}
```
MARKDOWN;
                }
            }
        }

        $output->writeln("\nGenerated Markdown:\n");
        $output->writeln($markdown);

        $exportPath = __DIR__ . '/../../docs/' . $ruleConfig->ruleDocsConfig->exportPath;

        $this->filesystem->put($exportPath, $markdown);
        $output->writeln('<info>Markdown saved to ' . $exportPath . '</info>');
    }

    private function generateSummaryMarkdown(array $ruleConfigs, string $outputPath, OutputInterface $output): void
    {
        $markdown = <<<MARKDOWN
# Rector Rules Summary

MARKDOWN . "\n\n";

        $counter = 1;

        foreach ($ruleConfigs as $ruleConfig) {
            $className = $ruleConfig->className;
            $baseClass = basename(str_replace('\\', '/', $className));
            $tags = implode(', ', array_map(fn($tag) => "`{$tag}`", $ruleConfig->ruleDocsConfig->tags));
            $isConfigurable = $ruleConfig->ruleType === RuleType::WITH_CONFIG ? '✅' : '❌';

            $markdown .= <<<MARKDOWN
## {$counter}. {$baseClass}

- Docs: [{$className}](/docs/{$ruleConfig->ruleDocsConfig->exportPath})
- Configurable: {$isConfigurable}
- Tags: {$tags}
MARKDOWN . "\n\n";

            $counter++;
        }

        $this->filesystem->put($outputPath, $markdown);
        $output->writeln("<info>Summary markdown generated at: {$outputPath}</info>");
    }
}
