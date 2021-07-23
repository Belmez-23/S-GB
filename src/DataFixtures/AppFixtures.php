<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $canterlot = new Conference();
        $canterlot->setCity('Canterlot');
        $canterlot->setYear('2019');
        $canterlot->setIsInternational(true);
        $manager->persist($canterlot);
    
        $laspegasus = new Conference();
        $laspegasus->setCity('Las-Pegasus');
        $laspegasus->setYear('2020');
        $laspegasus->setIsInternational(false);
        $manager->persist($laspegasus);
    
        $comment1 = new Comment();
        $comment1->setConference($canterlot);
        $comment1->setAuthor('Sunburst');
        $comment1->setEmail('sun@example.com');
        $comment1->setText('This was a great conference.');
        $comment1->setStatus('published');
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setConference($canterlot);
        $comment2->setAuthor('Sunburst');
        $comment2->setEmail('sun@example.com');
        $comment2->setText('This was a great conference.');
       // $comment1->setStatus('published');
        $manager->persist($comment2);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->encoderFactory->getEncoder(Admin::class)->encodePassword('admin', null));
        $manager->persist($admin);

        $manager->flush();
    }
}
