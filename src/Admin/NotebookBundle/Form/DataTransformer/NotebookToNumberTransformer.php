<?php

namespace Admin\NotebookBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Admin\NotebookBundle\Entity\Notebook;

class NotebookToNumberTransformer implements DataTransformerInterface
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
     * Transforms an object (notebook) to a string (number).
     *
     * @param  Notebook|null $notebook
     * @return string
     */
    public function transform($notebook)
    {
        if (null === $notebook) {
            return "";
        }
         
        return $notebook->getId();
    }

    /**
     * Transforms a string (id) to an object (notebook).
     *
     * @param  string $id
     *
     * @return Notebook|null
     *
     * @throws TransformationFailedException if object (notebook) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }
        
        $notebook = $this->om
            ->getRepository('AdminNotebookBundle:Notebook')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $notebook) {
            throw new TransformationFailedException(sprintf(
                'An notebook with id "%s" does not exist!',
                $id
            ));
        }

        return $notebook;
    }
}