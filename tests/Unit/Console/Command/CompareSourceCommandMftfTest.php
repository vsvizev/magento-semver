<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Tools\SemanticVersionChecker\Test\Unit\Console\Command;

use Magento\SemanticVersionChecker\Test\Unit\Console\Command\CompareSourceCommandTest\AbstractTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test semantic version checker CLI command.
 */
class CompareSourceCommandMftfTest extends AbstractTestCase
{
    /**
     * Test semantic version checker CLI command.
     *
     * 1. Run semantic version checker command to compare 2 source code directories
     * 2. Assert that SVC log contains expected entries
     * 3. Assert console output
     * 4. Assert return code
     *
     * @param string $pathToSourceCodeBefore
     * @param string $pathToSourceCodeAfter
     * @param string[] $expectedLogEntries
     * @param string $expectedOutput
     * @param string[] $unexpectedLogEntries
     * @dataProvider changesDataProvider
     * @return void
     * @throws \Exception
     */
    public function testExecute(
        $pathToSourceCodeBefore,
        $pathToSourceCodeAfter,
        $expectedLogEntries,
        $expectedOutput,
        $unexpectedLogEntries = []
    ) {
        $this->doTestExecute(
            $pathToSourceCodeBefore,
            $pathToSourceCodeAfter,
            $expectedLogEntries,
            $expectedOutput,
            $unexpectedLogEntries
        );
    }

    /**
     * Executes {@link CompareSourceCommandTest::$command} via {@link CommandTester}, using the arguments as command
     * line parameters.
     *
     * The command line parameters are specified as follows:
     * <ul>
     *   <li><kbd>source-before</kbd>: The content of the argument <var>$pathToSourceCodeBefore</var></li>
     *   <li><kbd>source-after</kbd>: The content of the argument <var>$pathToSourceCodeAfter</var></li>
     *   <li><kbd>--log-output-location</kbd>: The content of {@link CompareSourceCommandTest::$svcLogPath}</li>
     *   <li><kbd>--include-patterns</kbd>: The path to the file <kbd>./_files/application_includes.txt</kbd></li>
     *   <li><kbd>--mftf</kbd>: The content of the argument <var>None</var></li>
     * </ul>
     *
     * @param $pathToSourceCodeBefore
     * @param $pathToSourceCodeAfter
     * @return CommandTester
     */
    protected function executeCommand($pathToSourceCodeBefore, $pathToSourceCodeAfter): CommandTester
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'source-before'         => $pathToSourceCodeBefore,
                'source-after'          => $pathToSourceCodeAfter,
                '--log-output-location' => $this->svcLogPath,
                '--include-patterns'    => __DIR__ . '/CompareSourceCommandTest/_files/application_includes.txt',
                '--mftf'                => true,
            ]
        );
        return $commandTester;
    }

    public function changesDataProvider()
    {
        $pathToFixtures = __DIR__ . '/CompareSourceCommandTest/_files/mftf';
        return [
            'actionGroup-removed' => [
                $pathToFixtures . '/actionGroup-removed/source-code-before',
                $pathToFixtures . '/actionGroup-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'ActionGroup/ActionGroup1 | <actionGroup> was removed | M200'
                ],
                'Major change is detected.'
            ],
            'actionGroup-added' => [
                $pathToFixtures . '/actionGroup-added/source-code-before',
                $pathToFixtures . '/actionGroup-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'ActionGroup/ActionGroup2 | <actionGroup> was added | M225'
                ],
                'Minor change is detected.'
            ],
            'actionGroup-argument-changed' => [
                $pathToFixtures . '/actionGroup-argument-changed/source-code-before',
                $pathToFixtures . '/actionGroup-argument-changed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'ActionGroup/ActionGroup1/arg1/type | <actionGroup> <argument> was changed | M203'
                ],
                'Major change is detected.'
            ],
            'actionGroup-argument-removed' => [
                $pathToFixtures . '/actionGroup-argument-removed/source-code-before',
                $pathToFixtures . '/actionGroup-argument-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'ActionGroup/ActionGroup1/Arguments/arg1 | <actionGroup> <argument> was removed | M201'
                ],
                'Major change is detected.'
            ],
            'actionGroup-argument-added' => [
                $pathToFixtures . '/actionGroup-argument-added/source-code-before',
                $pathToFixtures . '/actionGroup-argument-added/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'ActionGroup/ActionGroup1/arg2 | <actionGroup> <argument> was added | M227'
                ],
                'Major change is detected.'
            ],
            'actionGroup-action-changed' => [
                $pathToFixtures . '/actionGroup-action-changed/source-code-before',
                $pathToFixtures . '/actionGroup-action-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'ActionGroup/ActionGroup1/action1/userInput | <actionGroup> <action> was changed | M204'
                ],
                'Patch change is detected.'
            ],
            'actionGroup-action-type-changed' => [
                $pathToFixtures . '/actionGroup-action-type-changed/source-code-before',
                $pathToFixtures . '/actionGroup-action-type-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'ActionGroup/ActionGroup1/action1 | <actionGroup> <action> type was changed | M223'
                ],
                'Patch change is detected.'
            ],
            'actionGroup-action-removed' => [
                $pathToFixtures . '/actionGroup-action-removed/source-code-before',
                $pathToFixtures . '/actionGroup-action-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'ActionGroup/ActionGroup1/action2 | <actionGroup> <action> was removed | M202'
                ],
                'Major change is detected.'
            ],
            'actionGroup-action-added' => [
                $pathToFixtures . '/actionGroup-action-added/source-code-before',
                $pathToFixtures . '/actionGroup-action-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'ActionGroup/ActionGroup1/action3 | <actionGroup> <action> was added | M226'
                ],
                'Minor change is detected.'
            ],
            'data-removed' => [
                $pathToFixtures . '/data-removed/source-code-before',
                $pathToFixtures . '/data-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Data/DataEntity1 | Entity was removed | M205'
                ],
                'Major change is detected.'
            ],
            'data-added' => [
                $pathToFixtures . '/data-added/source-code-before',
                $pathToFixtures . '/data-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Data/DataEntity2 | <entity> was added | M228'
                ],
                'Minor change is detected.'
            ],
            'data-array-removed' => [
                $pathToFixtures . '/data-array-removed/source-code-before',
                $pathToFixtures . '/data-array-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Data/DataEntity1/arraykey | Entity <array> element was removed | M206'
                ],
                'Major change is detected.'
            ],
            'data-array-added' => [
                $pathToFixtures . '/data-array-added/source-code-before',
                $pathToFixtures . '/data-array-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Data/DataEntity1/arraykeynew | <entity> <array> was added | M229'
                ],
                'Minor change is detected.'
            ],
            'data-array-item-removed' => [
                $pathToFixtures . '/data-array-item-removed/source-code-before',
                $pathToFixtures . '/data-array-item-removed/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Data/DataEntity1/arraykey/(tre) | Entity <array> <item> element was removed | M207'
                ],
                'Minor change is detected.'
            ],
            'data-field-removed' => [
                $pathToFixtures . '/data-field-removed/source-code-before',
                $pathToFixtures . '/data-field-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Data/DataEntity1/datakey | Entity <data> element was removed | M208'
                ],
                'Major change is detected.'
            ],
            'data-field-added' => [
                $pathToFixtures . '/data-field-added/source-code-before',
                $pathToFixtures . '/data-field-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Data/DataEntity1/datakeynew | Entity <data> element was added | M230'
                ],
                'Minor change is detected.'
            ],
            'data-reqentity-removed' => [
                $pathToFixtures . '/data-reqentity-removed/source-code-before',
                $pathToFixtures . '/data-reqentity-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Data/DataEntity1/reqentity | Entity <required-entity> element was removed | M209'
                ],
                'Major change is detected.'
            ],
            'data-reqentity-added' => [
                $pathToFixtures . '/data-reqentity-added/source-code-before',
                $pathToFixtures . '/data-reqentity-added/source-code-after',
                [
                    'Mftf (PATCH)',
                    'Data/DataEntity1/reqnew | <entity> <required-entity> element was added | M231'
                ],
                'Patch change is detected.'
            ],
            'data-var-removed' => [
                $pathToFixtures . '/data-var-removed/source-code-before',
                $pathToFixtures . '/data-var-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Data/DataEntity1/var1 | Entity <var> element was removed | M210'
                ],
                'Major change is detected.'
            ],
            'data-var-added' => [
                $pathToFixtures . '/data-var-added/source-code-before',
                $pathToFixtures . '/data-var-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Data/DataEntity1/var2 | <entity> <var> element was added | M232'
                ],
                'Minor change is detected.'
            ],
            'metadata-removed' => [
                $pathToFixtures . '/metadata-removed/source-code-before',
                $pathToFixtures . '/metadata-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Metadata/createEntity | <operation> was removed | M211'
                ],
                'Major change is detected.'
            ],
            'metadata-added' => [
                $pathToFixtures . '/metadata-added/source-code-before',
                $pathToFixtures . '/metadata-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'ActionGroup/createEntity2 | <operation> was added | M240'
                ],
                'Minor change is detected.'
            ],
            'metadata-top-level-child-removed' => [
                $pathToFixtures . '/metadata-top-level-child-removed/source-code-before',
                $pathToFixtures . '/metadata-top-level-child-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Metadata/createEntity/toplevelField | <operation> child element was removed | M212'
                ],
                'Major change is detected.'
            ],
            'metadata-bottom-level-child-removed' => [
                $pathToFixtures . '/metadata-bottom-level-child-removed/source-code-before',
                $pathToFixtures . '/metadata-bottom-level-child-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Metadata/createEntity/toplevelObj/childField | <operation> child element was removed | M212'
                ],
                'Major change is detected.'
            ],
            'page-removed' => [
                $pathToFixtures . '/page-removed/source-code-before',
                $pathToFixtures . '/page-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Page/SamplePage | <page> was removed | M213'
                ],
                'Major change is detected.'
            ],
            'page-added' => [
                $pathToFixtures . '/page-added/source-code-before',
                $pathToFixtures . '/page-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Page/SamplePageNew | <page> was added | M233'
                ],
                'Minor change is detected.'
            ],
            'page-section-removed' => [
                $pathToFixtures . '/page-section-removed/source-code-before',
                $pathToFixtures . '/page-section-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Page/SamplePage/Section2 | <page> <section> was removed | M214'
                ],
                'Major change is detected.'
            ],
            'page-section-added' => [
                $pathToFixtures . '/page-section-added/source-code-before',
                $pathToFixtures . '/page-section-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Page/SamplePage/SectionNew | <page> <section> was added | M234'
                ],
                'Minor change is detected.'
            ],
            'section-removed' => [
                $pathToFixtures . '/section-removed/source-code-before',
                $pathToFixtures . '/section-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Section/SampleSection | <section> was removed | M215'
                ],
                'Major change is detected.'
            ],
            'section-added' => [
                $pathToFixtures . '/section-added/source-code-before',
                $pathToFixtures . '/section-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Section/NewSection | <section> was added | M235'
                ],
                'Minor change is detected.'
            ],
            'section-element-removed' => [
                $pathToFixtures . '/section-element-removed/source-code-before',
                $pathToFixtures . '/section-element-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Section/SampleSection/element2 | <section> <element> was removed | M216'
                ],
                'Major change is detected.'
            ],
            'section-element-changed' => [
                $pathToFixtures . '/section-element-changed/source-code-before',
                $pathToFixtures . '/section-element-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'Section/SampleSection/element1/selector | <section> <element> was changed | M217'
                ],
                'Patch change is detected.'
            ],
            'section-element-added' => [
                $pathToFixtures . '/section-element-added/source-code-before',
                $pathToFixtures . '/section-element-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Section/SampleSection/newElement | <section> <element> was added | M236'
                ],
                'Minor change is detected.'
            ],
            'test-removed' => [
                $pathToFixtures . '/test-removed/source-code-before',
                $pathToFixtures . '/test-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest | <test> was removed | M218'
                ],
                'Major change is detected.'
            ],
            'test-added' => [
                $pathToFixtures . '/test-added/source-code-before',
                $pathToFixtures . '/test-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Test/NewTest | <test> was added | M237'
                ],
                'Minor change is detected.'
            ],
            'test-action-changed' => [
                $pathToFixtures . '/test-action-changed/source-code-before',
                $pathToFixtures . '/test-action-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'Test/SampleTest/key1/userInput | <test> <action> was changed | M222'
                ],
                'Patch change is detected.'
            ],
            'test-action-type-changed' => [
                $pathToFixtures . '/test-action-type-changed/source-code-before',
                $pathToFixtures . '/test-action-type-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'Test/SampleTest/action1 | <test> <action> type was changed | M224'
                ],
                'Patch change is detected.'
            ],
            'test-action-removed' => [
                $pathToFixtures . '/test-action-removed/source-code-before',
                $pathToFixtures . '/test-action-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/key2 | <test> <action> was removed | M219'
                ],
                'Major change is detected.'
            ],
            'test-action-added' => [
                $pathToFixtures . '/test-action-added/source-code-before',
                $pathToFixtures . '/test-action-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Test/SampleTest/newAction | <test> <action> was added | M238'
                ],
                'Minor change is detected.'
            ],
            'test-before-action-removed' => [
                $pathToFixtures . '/test-before-action-removed/source-code-before',
                $pathToFixtures . '/test-before-action-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/before/key1 | <test> <action> was removed | M219'
                ],
                'Major change is detected.'
            ],
            'test-before-action-added' => [
                $pathToFixtures . '/test-before-action-added/source-code-before',
                $pathToFixtures . '/test-before-action-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Test/SampleTest/before/newAction | <test> <action> was added | M238'
                ],
                'Minor change is detected.'
            ],
            'test-after-action-removed' => [
                $pathToFixtures . '/test-after-action-removed/source-code-before',
                $pathToFixtures . '/test-after-action-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/after/key1 | <test> <action> was removed | M219'
                ],
                'Major change is detected.'
            ],
            'test-after-action-added' => [
                $pathToFixtures . '/test-after-action-added/source-code-before',
                $pathToFixtures . '/test-after-action-added/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Test/SampleTest/after/newAction | <test> <action> was added | M238'
                ],
                'Minor change is detected.'
            ],
            'test-annotation-changed' => [
                $pathToFixtures . '/test-annotation-changed/source-code-before',
                $pathToFixtures . '/test-annotation-changed/source-code-after',
                [
                    'Mftf (PATCH)',
                    'Test/SampleTest/annotations/{}description | <test> <annotation> was removed or changed | M221'
                ],
                'Patch change is detected.'
            ],
            'test-group-removed' => [
                $pathToFixtures . '/test-group-removed/source-code-before',
                $pathToFixtures . '/test-group-removed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/annotations/{}group(sampleGroup) | <test> <annotation> <group> was removed | M220'
                ],
                'Major change is detected.'
            ],
            'test-remove-action-added' => [
                $pathToFixtures . '/test-remove-action-added/source-code-before',
                $pathToFixtures . '/test-remove-action-added/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/newRemoveAction | <test> <remove action> was added | M401'
                ],
                'Major change is detected.'
            ],
            'test-remove-action-removed' => [
                $pathToFixtures . '/test-remove-action-removed/source-code-before',
                $pathToFixtures . '/test-remove-action-removed/source-code-after',
                [
                    'Mftf (MINOR)',
                    'Test/SampleTest/key2 | <test> <remove action> was removed | M402'
                ],
                'Minor change is detected.'
            ],
            'test-action-group-ref-changed' => [
                $pathToFixtures . '/test-action-group-ref-changed/source-code-before',
                $pathToFixtures . '/test-action-group-ref-changed/source-code-after',
                [
                    'Mftf (MAJOR)',
                    'Test/SampleTest/key2/ref | <test> <actionGroup> ref was changed | M241'
                ],
                'Major change is detected.'
            ],
        ];
    }
}
