<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Dossiers source et destination
        $sourceDir = dirname(__DIR__, 2) . '/assets/images/fixtures';
        $destinationDir = dirname(__DIR__, 2) . '/public/uploads/books';

        $filesystem = new Filesystem();
        if (!$filesystem->exists($sourceDir)) {
            throw new \Exception("Le dossier source n'existe pas : " . $sourceDir);
        }
        if (!$filesystem->exists($destinationDir)) {
            $filesystem->mkdir($destinationDir, 0755);
        }
        try {
            $files = scandir($sourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $sourceFilePath = $sourceDir . '/' . $file;
                    $destinationFilePath = $destinationDir . '/' . $file;

                    if (is_file($sourceFilePath)) {
                        $filesystem->copy($sourceFilePath, $destinationFilePath, true);
                    }
                }
            }
        } catch (IOExceptionInterface $exception) {
            echo "Une erreur est survenue lors de la copie des fichiers : " . $exception->getMessage();
        }
    }
}