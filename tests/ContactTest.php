<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contactez-nous !');

        $submitButton = $crawler->selectButton('Envoyer');
        $form = $submitButton->form([
            'contact[fullName]' => 'Hamza Be Attia',
            'contact[email]' => 'hamzabenattiayt2@gmail.com',
            'contact[subject]' => 'Hello, this is a test message',
            'contact[message]' => 'Hello, this is a test message',
        ]);

            // submit the form
        $client->submit($form);

        // verifier le statu HTTP

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);


        // check if the email was sent

        $this->assertEmailCount(1);

        $client->followRedirect();

        // verifier le flush message

        $this->assertSelectorTextContains('div.alert.alert-success', 'Email envoyé avec succès !');



    }
}
