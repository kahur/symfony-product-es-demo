<?php

namespace KH\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use KH\Interfaces\EntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ProductDetail
 *
 * @ORM\Table(name="product_detail", indexes={@ORM\Index(name="fk_product_detail_product_idx", columns={"product_id"})})
 * @ORM\Entity
 */
class ProductDetail extends AbstractEntity implements EntityInterface
{
    /**
     * @var int
     * @Groups("product_detail")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Groups("product_detail")
     * @ORM\Column(name="detail_name", type="string", length=255, nullable=false)
     */
    protected $detailName;

    /**
     * @var string
     * @Groups("product_detail")
     * @ORM\Column(name="detail_value", type="text", length=0, nullable=false)
     */
    protected $detailValue;

    /**
     * @var \DateTime|null
     * @Groups("product_detail")
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @Groups("product_detail")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $updatedAt;

    /**
     * @var \Product
     * @Groups("full_product_detail")
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="details")
     */
    protected $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetailName(): ?string
    {
        return $this->detailName;
    }

    public function setDetailName(string $detailName): self
    {
        $this->detailName = $detailName;

        return $this;
    }

    public function getDetailValue(): ?string
    {
        return $this->detailValue;
    }

    public function setDetailValue(string $detailValue): self
    {
        $this->detailValue = $detailValue;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }


}
