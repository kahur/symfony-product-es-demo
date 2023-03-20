<?php

namespace KH\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use KH\Interfaces\EntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product extends AbstractEntity implements EntityInterface
{
    /**
     * @var int
     * @Groups("product")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Groups("product")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     * @Groups("product")
     * @ORM\Column(name="short_desc", type="string", length=255, nullable=false)
     */
    protected $shortDesc;

    /**
     * @var Category[]
     * @Groups("product_detail")
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"})
     * @ORM\JoinTable(name="product_category")
     */
    protected $categories;

    /**
     * @var File[]
     * @Groups("product_detail")
     * @ORM\ManyToMany(targetEntity="File", cascade={"persist"})
     * @ORM\JoinTable(name="product_gallery")
     */
    protected $files;

    /**
     * @var ProductDetail[]
     * @Groups("product_detail")
     * @ORM\OneToMany(targetEntity="ProductDetail", mappedBy="product", cascade={"persist"})
     */
    protected $details;

    /**
     * @var \DateTime|null
     * @Groups("product")
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @Groups("product")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDesc(): ?string
    {
        return $this->shortDesc;
    }

    public function setShortDesc(string $shortDesc): self
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function setCategories(array $categories)
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }

    public function setImages(array $files)
    {
        foreach ($files as $file) {
            $this->addFile($file);
        }
    }

    public function setFiles(array $files)
    {
        $this->setImages($files);
    }

    public function removeFile(File $file): self
    {
        $this->files->removeElement($file);

        return $this;
    }

    /**
     * @return ProductDetail[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(?ProductDetail $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
        }

        return $this;
    }

    public function setDetails(array $details)
    {
        foreach ($details as $detail) {
            $this->addDetail($detail);
        }
    }

    public function clearImages()
    {
        $this->files = new ArrayCollection();
    }

    public function clearCategories()
    {
        $this->categories = new ArrayCollection();
    }

    public function clearDetails()
    {
        $this->details = new ArrayCollection();
    }
}
