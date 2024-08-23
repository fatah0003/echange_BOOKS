<?php

namespace App\Tests\Unit;

use App\Entity\Books;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser(): void
    {
        $book = new Books();
        $book2 = new Books();
        $book3 = new Books();
        $user = new User();
        $user
            ->setEmail('fatah_ain@live.fr')
            ->setPassword('0123456')
            ->setRoles(['ROLE_USER'])
            ->addBook($book)
            ->addBook($book2)
            ->addBook($book3)


            ;
        $this->assertEquals('fatah_ain@live.fr', $user->getEmail());
        $this->assertEquals('0123456', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertCount(3, $user->getBooks());
        $user->removeBook($book3);
        $this->assertCount(2, $user->getBooks());
        $this->assertContains($book, $user->getBooks());
        $this->assertContains($book2, $user->getBooks());
    }
}
