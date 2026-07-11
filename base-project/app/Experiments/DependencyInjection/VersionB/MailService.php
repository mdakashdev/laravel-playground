<?php
namespace App\Experiments\DependencyInjection\VersionB;

class MailService
{
    public function send()
    {
        return "Sending mail from Version B";
    }
}
