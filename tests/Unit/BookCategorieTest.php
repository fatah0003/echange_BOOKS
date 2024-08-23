<?php

namespace App\Tests\Unit;

use App\Entity\BookCategorie;
use App\Entity\Books;
use PHPUnit\Framework\TestCase;

class BookCategorieTest extends TestCase
{
    public function testAddAndRemoveBooksId(): void
    {
        $categorie = new BookCategorie();

        // Créer un mock de l'entité Books
        $book = new Books();

        $book->setBookCategorie($categorie);

        $categorie->addBooksId($book);

        $this->assertCount(1, $categorie->getBooksId());
        $this->assertTrue($categorie->getBooksId()->contains($book));


        $categorie->removeBooksId($book);

        $this->assertCount(0, $categorie->getBooksId());
        $this->assertFalse($categorie->getBooksId()->contains($book));
    }
}
