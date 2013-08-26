<?php

namespace Mremi\ContactBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Contact controller test class
 *
 * @author Rémi Marseille <marseille.remi@gmail.com>
 */
class ContactControllerTest extends WebTestCase
{
    /**
     * Tests the index action
     */
    public function testIndex()
    {
        if (!isset($_SERVER['KERNEL_DIR'])) {
            $this->markTestSkipped('KERNEL_DIR is not set in phpunit.xml, considers not in a Symfony project (no app directory, src, etc.).');
        }

        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('contact_form_save')->form();

        $client->submit($form, array(
            'contact_form[title]'     => 'mr',
            'contact_form[firstName]' => 'Rémi',
            'contact_form[lastName]'  => 'Marseille',
            'contact_form[email]'     => 'marseille.remi@gmail.com',
            'contact_form[subject]'   => 'Subject',
            'contact_form[message]'   => '',  // do not set value to cause a validation error
            'contact_form[captcha]'   => '1234',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $submittedValues = $form->getPhpValues();

        $this->assertArrayHasKey('contact_form', $submittedValues);

        $submittedValues = $submittedValues['contact_form'];

        $this->assertCount(9, $submittedValues);

        $this->assertArrayHasKey('title', $submittedValues);
        $this->assertEquals('mr', $submittedValues['title']);

        $this->assertArrayHasKey('firstName', $submittedValues);
        $this->assertEquals('Rémi', $submittedValues['firstName']);

        $this->assertArrayHasKey('lastName', $submittedValues);
        $this->assertEquals('Marseille', $submittedValues['lastName']);

        $this->assertArrayHasKey('email', $submittedValues);
        $this->assertEquals('marseille.remi@gmail.com', $submittedValues['email']);

        $this->assertArrayHasKey('subject', $submittedValues);
        $this->assertEquals('Subject', $submittedValues['subject']);

        $this->assertArrayHasKey('message', $submittedValues);
        $this->assertEquals('', $submittedValues['message']);

        $this->assertArrayHasKey('captcha', $submittedValues);
        $this->assertEquals(1234, $submittedValues['captcha']);

        $this->assertArrayHasKey('_token', $submittedValues);

        $this->assertArrayHasKey('save', $submittedValues);
    }

    /**
     * Tests the confirm action
     */
    public function testConfirm()
    {
        if (!isset($_SERVER['KERNEL_DIR'])) {
            $this->markTestSkipped('KERNEL_DIR is not set in phpunit.xml, considers not in a Symfony project (no app directory, src, etc.).');
        }

        $client = static::createClient();

        $crawler = $client->request('GET', '/contact/confirmation');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }
}
