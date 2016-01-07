<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryPhoto
 *
 * @ORM\Table(name="category_photo")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\CategoryPhotoRepository")
 */
class CategoryPhoto extends \Buggl\PhotoBundle\Entity\Photos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    // /**
    //  * Get id
    //  *
    //  * @return integer
    //  */
    // public function getId()
    // {
    //     return $this->id;
    // }
}