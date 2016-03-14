<?php

namespace CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Annotations;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="CourseBundle\Repository\CourseRepository")
 */
class Course
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin", type="datetime")
     */
    private $begin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="candidate_limit", type="integer")
     */
    private $candidateLimit;

    /**
     *
     * @ORM\ManyToMany(targetEntity="\UserBundle\Entity\User", mappedBy="courses")
     */
    protected $candidates;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set begin
     *
     * @param \DateTime $begin
     *
     * @return Course
     */
    public function setBegin($begin)
    {
        $this->begin = (!empty($begin)) ? new \DateTime($begin) : new \DateTime('now');

        return $this;
    }

    /**
     * Get begin
     *
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Course
     */
    public function setEnd($end)
    {
        $this->end = (!empty($end)) ? new \DateTime($end) : new \DateTime('now');

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Course
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
     * Set candidateLimit
     *
     * @param string $candidateLimit
     *
     * @return Course
     */
    public function setCandidateLimit($candidateLimit)
    {
        $this->candidateLimit = (integer) $candidateLimit;

        return $this;
    }

    /**
     * Get candidateLimit
     *
     * @return int
     */
    public function getCandidateLimit()
    {
        return $this->candidateLimit;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Course
     */
    public function setCandidate(\UserBundle\Entity\User $user)
    {
        $this->candidates = $user;

        return $this;
    }


    public function addCandidate(\UserBundle\Entity\User $user)
    {
        $user->addCourse($this);
        $this->candidates[] = $user;
    }


    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getCandidate()
    {
        return $this->candidates;
    }

    /**
     * Remove candidate
     *
     * @param \UserBundle\Entity\User $user
     * @return \stdClass
     */
    public function removeCandidate(\UserBundle\Entity\User $user)
    {
        return $this->candidates->removeElement($user);
    }
}

