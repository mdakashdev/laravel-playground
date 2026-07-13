<?php
namespace App\Experiments\DependencyInjection;

class DependencyController
{
    public function __construct(
        protected DependencyService $service
    ) {

    }

    public function dependency()
    {
        $mail = new DependencyService();
        return "tightly coupled dependency - ". $mail->send();
    }
    /**
     * jodi eivabe tight couple dependency solve kora hoi, tobe MailService a Constructor change korlei code venge jabe.
     * bec. tokhon code jeye sei constructor er dependecny pass korte hobe; so eivabe jodi
     * TestController->TestService->Logger->Cache->Config->Db->etc, onke dependency thake ta `new` diye manage kora khub tough
     *
     * solution is - DEPENDENCY INJECTION (DI)
     */

    public function dependencyInjection()
    {
        return $this->service->send()." using dependency injection";
    }

    /**
     * eivabe use korle, kono code change hobe na, ekhon object create korar responsibility controller er kache nai
     * object create na kore , object cai - aar sei object provide kore container
     * that means control ta ekhon container er kache, tar mane ulta hoyeche - tai eitai hocche Inverse of Control (IoC)
     */
}
