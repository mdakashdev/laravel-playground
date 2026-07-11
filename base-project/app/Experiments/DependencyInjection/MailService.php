<?php
namespace App\Experiments\DependencyInjection;

class MailService
{
    //if i add a constructor here, it will break the code in VersionB\UserController because it expects a MailService instance to be injected

   public function send()
   {
       return "Sending mail from Version A";
   }
}
