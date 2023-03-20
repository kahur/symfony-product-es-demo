<?php

namespace KH\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use KH\Interfaces\EntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * File
 *
 * @ORM\Table(name="file", indexes={@ORM\Index(name="type_index", columns={"type"})})
 * @ORM\Entity
 */
class File extends AbstractEntity implements EntityInterface
{
    /**
     * @var int
     * @Groups("file")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Groups("file")
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @Groups("file")
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    protected $type;

    /**
     * @var string
     * @Groups("file")
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    protected $path;

    /**
     * @var array|null
     * @Groups("file")
     * @ORM\Column(name="meta_data", type="json", nullable=true)
     */
    protected $metaData;

    /**
     * @var Product[]
     * @Groups("file_detail")
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="files")
     * @ORM\JoinTable(name="product_gallery")
     */
    protected $products;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getMetaData():? array
    {
        return $this->metaData;
    }

    public function setMetaData(?array $metaData): self
    {
        $this->metaData = $metaData;

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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addFile($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeFile($this);
        }

        return $this;
    }

    public function setUpdatedAt(?\DateTimeInterface $dateTime): self
    {
        return $this;
    }
}
