<?php

namespace UserBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="user")
*/
class User extends BaseUser
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @ORM\Column(type="string", length=30)
    */
    protected $firstname;

    /**
    * @ORM\Column(type="string", length=30)
    */
    protected $lastname;

    /**
    * @ORM\Column(type="string", length=1)
    */
    protected $gender;

    /**
     * @ORM\ManyToMany(targetEntity="\CourseBundle\Entity\Course", inversedBy="candidates")
     * @ORM\JoinTable(name="user_course",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id")}
     * )
     */
    protected $courses;


    public function __construct()
    {
       parent::__construct();
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }


    /**
     * Add Course
     *
     * @param \CourseBundle\Entity\Course $course
     */
    public function addCourse(\CourseBundle\Entity\Course $course)
    {
        $this->courses[] = $course;
    }

    /**
     * Get courses
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Remove Course
     *
     * @param \CourseBundle\Entity\Course $course
     * @return \stdClass
     */
    public function removeCourse(\CourseBundle\Entity\Course $course)
    {
        return $this->courses->removeElement($course);
    }
}
