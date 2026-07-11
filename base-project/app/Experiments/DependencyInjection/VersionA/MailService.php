<?php
namespace App\Experiments\DependencyInjection\VersionA;

class MailService
{
   public function send()
   {
       return "Sending mail from Version A";
   }
}
