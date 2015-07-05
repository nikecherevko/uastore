<?php

namespace Admin\CategoryBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Admin\CategoryBundle\Entity\Category;

class CategoryToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (category) to a string (id).
     *
     * @param  Category|null $category
     * @return string
     */
    public function transform($category)
    {
        if (null === $category) {
            return "";
        }
         
        return $category->getId();
    }

    /**
     * Transforms a string (id) to an object (category).
     *
     * @param  string $id
     *
     * @return Category|null
     *
     * @throws TransformationFailedException if object (category) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }
        
        $category = $this->om
            ->getRepository('AdminCategoryBundle:Category')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $category) {
            throw new TransformationFailedException(sprintf(
                'An category with id "%s" does not exist!',
                $id
            ));
        }

        return $category;
    }
}