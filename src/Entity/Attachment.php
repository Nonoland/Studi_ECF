<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
#[Vich\Uploadable]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[Vich\UploadableField(mapping: 'suite_images', fileNameProperty: 'image')]
    private $imageFile;

    #[ORM\ManyToOne(targetEntity: Suite::class, inversedBy: 'attachments')]
    private $suite;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
        $this->image = $imageFile->getBasename();
    }

    /**
     * @return mixed
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * @param mixed $suite
     */
    public function setSuite($suite): void
    {
        $this->suite = $suite;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        return $this->image;
    }

}
