<?php
declare(strict_types=1);

namespace Acme\CustomerValidate\Test\Unit\Model;

use Acme\CustomerValidate\Model\Validator;
use Magento\Framework\Validator\EmailAddress as EmailAddressValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /** @var EmailAddressValidator&MockObject */
    private EmailAddressValidator $emailAddressValidator;

    private Validator $validator;

    protected function setUp(): void
    {
        $this->emailAddressValidator = $this->createMock(EmailAddressValidator::class);
        $this->validator = new Validator($this->emailAddressValidator);
    }

    public function testIsValidEmailReturnsFalseForEmptyString(): void
    {
        $this->emailAddressValidator->expects($this->never())->method('isValid');

        $this->assertFalse($this->validator->isValidEmail(''));
    }

    public function testIsValidEmailReturnsFalseForWhitespaceOnly(): void
    {
        $this->emailAddressValidator->expects($this->never())->method('isValid');

        $this->assertFalse($this->validator->isValidEmail('   '));
    }

    public function testIsValidEmailDelegatesToEmailAddressValidator(): void
    {
        $email = 'customer@example.com';

        $this->emailAddressValidator
            ->expects($this->once())
            ->method('isValid')
            ->with($email)
            ->willReturn(true);

        $this->assertTrue($this->validator->isValidEmail($email));
    }

    public function testIsValidEmailTrimsInputBeforeValidation(): void
    {
        $this->emailAddressValidator
            ->expects($this->once())
            ->method('isValid')
            ->with('customer@example.com')
            ->willReturn(false);

        $this->assertFalse($this->validator->isValidEmail("  customer@example.com\n"));
    }
}
