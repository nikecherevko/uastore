<?php

namespace Admin\NotebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="notebook_properties")
 */
class Properties
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * 
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Value", mappedBy="property")
     */
    protected $values;
    
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
        return __DIR__.'/../../../../public_html/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/notebook/properties';
    }
    
    protected function createSlug($name) {

        $match = [
            "/[[:punct:]]/",
            "/&/",
            "/ +/",
            "/-+/",
            "/^-/",
            "/-$/"
        ];
        
        $replace = [
            "-",
            "-",
            "-",
            "-",
            "",
            ""
        ];

        $testTitle =  preg_replace($match, $replace, strtolower($name));

        $russian = ["а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я",
             "А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я"
        ];

        $english =  ["a","b","v","g","d","e","jo","zh","z","i","y","k","l","m","n","o","p","r","s","t","u","f","h","c","ch","sh","sch","","y","","e","ju","ja",
                                         "a","b","v","g","d","e","jo","zh","z","i","y","k","l","m","n","o","p","r","s","t","u","f","h","c","ch","sh","sch","","y","","e","ju","ja"	
        ];

        $endt = str_replace($russian,$english,$testTitle);
        
        return  $endt;
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
     * Set name
     *
     * @param string $name
     * @return Properties
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Properties
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
     * @return Properties
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
     * @return Properties
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
     * Add values
     *
     * @param \Admin\NotebookBundle\Entity\Value $values
     * @return Properties
     */
    public function addValue(\Admin\NotebookBundle\Entity\Value $values)
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Remove values
     *
     * @param \Admin\NotebookBundle\Entity\Value $values
     */
    public function removeValue(\Admin\NotebookBundle\Entity\Value $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValues()
    {
        return $this->values;
    }
    
    
    public function __toString()
    {
        return $this->getName();
    }
}
