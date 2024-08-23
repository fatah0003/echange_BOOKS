<?php

namespace App\Tests\Unit;

use App\Entity\BookCategorie;
use App\Entity\Books;
use App\Entity\User;
use App\Enum\ExchangeTypeEnum;
use App\Enum\StateEnum;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BooksTest extends TestCase
{
    public function testBooks(): void
    {
        $bookCategorie = new BookCategorie();
        $bookCategorie->setName('Science');
        $user = new User();
        $date = new DateTimeImmutable();
        $datUpdate = new DateTimeImmutable();

        $book = new Books();
        $book
        ->setTitle('Inception')
        ->setAuthor('Christopher NOLAN')
        ->setIsbn('123456')
        ->setDescription('Le livre de NOLAN')
        ->setEdition('1 er')
        ->setLocation('Paris')
        ->setBookCategorie($bookCategorie)
        ->setPages(111)
        ->setState(StateEnum::GOOD)
        ->setExchangeType([ExchangeTypeEnum::PERMANENT])
        ->setUser($user)
        ->setCreatedAt($date)
        ->setUpdatedAt($datUpdate)
        ;

        $this->assertEquals('Inception', $book->getTitle());
        $this->assertEquals('Christopher NOLAN', $book->getAuthor());
        $this->assertEquals('123456', $book->getIsbn());
        $this->assertEquals('Le livre de NOLAN', $book->getDescription());
        $this->assertEquals('1 er', $book->getEdition());
        $this->assertEquals('Paris', $book->getLocation());
        $this->assertEquals($bookCategorie, $book->getBookCategorie());
        $this->assertEquals(111, $book->getPages());
        $this->assertEquals(StateEnum::GOOD, $book->getState());
        $this->assertEquals([ExchangeTypeEnum::PERMANENT], $book->getExchangeType());
        $this->assertEquals($user, $book->getUser());
        $this->assertEquals($date, $book->getCreatedAt());
        $this->assertEquals($datUpdate, $book->getUpdatedAt());
    }
}
