<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[UploadableField(mapping: 'image', fileNameProperty: 'imageName', size: 'imageSize')]
    private $imageFile;

    private $imageName;

    private $imageSize;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(string $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function getImageSize()
    {
        return $this->imageSize;
    }


}
