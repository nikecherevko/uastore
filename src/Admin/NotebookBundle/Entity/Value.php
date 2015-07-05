<?php

namespace Admin\NotebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="notebook_value")
 */
class Value
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="PropertiesValue", mappedBy="value")
     */
    protected $propvaluess;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Properties", inversedBy="values")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * 
     * @Assert\NotBlank()
     */
    protected $property;
      
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     minMessage = "Минимальное количество 2 символа",
     * )
     */
    protected $value;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;
    
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
            $filename = rand(1,1000000000);
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
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/notebook/value';
    }
    /**
    * constructor
    */
    public function __construct()
    {
      $this->created = new \DateTime();
      $this->position = 0;
      $this->visible = true;
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
     * Set value
     *
     * @param string $value
     * @return Value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Value
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
     * @return Value
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
     * Set visible
     *
     * @param boolean $visible
     * @return Value
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
     * Set property
     *
     * @param \Admin\NotebookBundle\Entity\Properties $property
     * @return Value
     */
    public function setProperty(\Admin\NotebookBundle\Entity\Properties $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \Admin\NotebookBundle\Entity\Properties 
     */
    public function getProperty()
    {
        return $this->property;
    }
}
