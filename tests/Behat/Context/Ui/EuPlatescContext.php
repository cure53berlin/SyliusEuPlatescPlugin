<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

declare(strict_types=1);

namespace Tests\Infifni\SyliusEuPlatescPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;

final class EuPlatescContext implements Context
{
    /**
     * @When I confirm my order with EuPlatesc payment
     */
    public function iConfirmMyOrderWithEuplatescPayment()
    {
        throw new PendingException();
    }

    /**
     * @When I sign in to EuPlatesc and pay successfully
     */
    public function iSignInToEuplatescAndPaySuccessfully()
    {
        throw new PendingException();
    }

    /**
     * @When I cancel my EuPlatesc payment
     */
    public function iCancelMyEuplatescPayment()
    {
        throw new PendingException();
    }

    /**
     * @Given I have confirmed my order with EuPlatesc payment
     */
    public function iHaveConfirmedMyOrderWithEuplatescPayment()
    {
        throw new PendingException();
    }

    /**
     * @Given I have cancelled EuPlatesc payment
     */
    public function iHaveCancelledEuplatescPayment()
    {
        throw new PendingException();
    }

    /**
     * @When I try to pay again with EuPlatesc payment
     */
    public function iTryToPayAgainWithEuplatescPayment()
    {
        throw new PendingException();
    }
}