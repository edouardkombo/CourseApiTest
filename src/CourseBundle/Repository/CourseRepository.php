<?php

namespace CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CourseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CourseRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Returns all courses ordered by title ascendant.
     *


     * @return \CourseBundle\Entity\Course
     */
    public function getCourses()
    {
        return $this->findBy([], [ 'title' => 'ASC' ]);
    }

}
