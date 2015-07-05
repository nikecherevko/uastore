<?php

namespace Admin\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="category")
 * @UniqueEntity(
 *     fields={"title"},
 *     errorPath="title",
 *     message="Категория с таким названием уже существует."
 * )
 */
class Category
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $parent_id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     minMessage = "Минимальное количество 2 символа",
     * )
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $count;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $countchild;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;
    
    /**
    * @ORM\Column(type="datetime")
    */
    protected $created;
    
    
    
    /**
     * @Assert\File(maxSize="6000000")
     * 
     * @Assert\Image(
     *     minWidth = 15,
     *     maxWidth = 700,
     *     minHeight = 15,
     *     maxHeight = 700,
     *     maxWidthMessage = "Максимальная ширина логотипа 700px",
     *     minWidthMessage = "Минимальная ширина логотипа 15px",
     *     maxHeightMessage = "Максимальная высота логотипа 700px",
     *     minHeightMessage = "Минимальная высота логотипа 15px",
     *     mimeTypesMessage = "Этот формат изображения не поддерживается",
     * )
     */
    public $file;

    
    public function getFile()
    {
        return $this->file;
    }

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->image)) {
            // store the old name to delete after the update
            $this->temp = $this->image;
            $this->image = null;
        } else {
            $this->image = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->image);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
     
    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../public_html/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/admincategory';
    }
    
    /**
    * constructor
    */
    public function __construct()
    {
      $this->created = new \DateTime();
      $this->parent_id = 0;
      $this->position = 0;
      $this->count = 0;
      $this->countchild = 0;
      $this->visible = false;
    }
 
 

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent_id
     *
     * @param integer $parentId
     * @return Category
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * Get parent_id
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Category
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set countchild
     *
     * @param integer $countchild
     * @return Category
     */
    public function setCountchild($countchild)
    {
        $this->countchild = $countchild;

        return $this;
    }

    /**
     * Get countchild
     *
     * @return integer 
     */
    public function getCountchild()
    {
        return $this->countchild;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Category
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    public function __toString()
    {
        return $this->getTitle();
    }
}
