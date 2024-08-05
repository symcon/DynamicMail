<?php

declare(strict_types=1);

include_once __DIR__ . '/stubs/Validator.php';

class DynamicMailValidationTest extends TestCaseSymconValidation
{
    public function testValidateDynamicMail(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }

    public function testValidateDynamicMailModule(): void
    {
        $this->validateModule(__DIR__ . '/../Dynamic Mail');
    }
}