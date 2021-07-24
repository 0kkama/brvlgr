<?php


    namespace App\classes\utility;


    use App\interfaces\CanSendMessage;
    use Swift_Mailer;
    use Swift_Message;
    use Swift_SmtpTransport;
    use App\classes\Config;

    /**
     * Class SendMail
     * Primitive class for send alerts to admin e-mail
     * @package App\classes\utility
     */
    class SendMail implements CanSendMessage
    {
        public static function sendMessage(string $subject, string $body) : int
        {
            $conf = Config::getInstance();
            $transport = (new Swift_SmtpTransport(
                $conf->getSwift('host'),
                $conf->getSwift('port'),
                $conf->getSwift('encryption')))
            ->setUsername($conf->getSwift('username'))->setPassword($conf->getSwift('password'));

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message($subject))
            ->setFrom([$conf->getSwift('username') => 'TestProject'])
            ->setTo(['mydeadtime@gmail.com'])
            ->setBody($body);

            // Send the message
            return $mailer->send($message);
        }
    }
