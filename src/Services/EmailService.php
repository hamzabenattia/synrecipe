<?php

    namespace App\Services;

    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;


    class EmailService
    {
        private MailerInterface $mailer;

        public function __construct(MailerInterface $mailer)
        {
            $this->mailer = $mailer;
        }

        public function sendEmail(string $from, string $to, string $subject, string $text, string $html): void
        {
            $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->text($text)
                ->html($html);

            $this->mailer->send($email);
        }
    }