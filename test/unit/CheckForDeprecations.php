<?php

namespace Lcobucci\JWT;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Error\Deprecated;
use function restore_error_handler;
use function set_error_handler;
use const E_USER_DEPRECATED;

trait CheckForDeprecations
{
    /** @var string[]|null */
    private $expectedDeprecationMessages;

    /** @var string[]|null */
    private $actualDeprecationMessages = [];

    /** @after */
    public function verifyDeprecationWasTrigger()
    {
        if ($this->expectedDeprecationMessages === null) {
            return;
        }

        restore_error_handler();

        Assert::assertSame($this->expectedDeprecationMessages, $this->actualDeprecationMessages);

        $this->expectedDeprecationMessages = null;
        $this->actualDeprecationMessages   = [];
    }

    public function expectDeprecation(): void
    {
        $message = func_get_arg(0);

        if ($this->expectedDeprecationMessages !== null) {
            $this->expectedDeprecationMessages[] = $message;

            return;
        }

        $this->expectedDeprecationMessages = [$message];

        set_error_handler(
            function ($errorNumber, $message) {
                $this->actualDeprecationMessages[] = $message;
            },
            E_USER_DEPRECATED
        );
    }
}
