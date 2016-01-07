<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\CategoryPhoto;
use Doctrine\ORM\EntityManager;

/**
 * BugglCategoryPhotoService
 *
 * @author Vincent Farly G. Tabaoda <farly.taboada@goabroad.com>
 */
class BugglCategoryPhotoService
{
    private $entityManager;


    /**
     * Constructor
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * saves the photo info upon uploading to servcer
     * @param string $filename []
     * @param string $path     []
     *
     * @return Buggl\MainBundle\Entity\CategoryPhoto
     */
    public function saveCategoryPhoto($filename,$path)
    {
        $object = new CategoryPhoto();
        $object->setFilename($filename);
        $object->setTags(json_encode(array()));
        $object->setDateAdded(new \DateTime(date('Y-m-d H:i:s', time())));
        $object->setPath($path);

        $this->entityManager->persist($object);
        $this->entityManager->flush();


        return $object;
    }
}