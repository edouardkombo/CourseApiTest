<?php

namespace CourseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CourseBundle\Entity\Course;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Course controller.
 *
 * @Route("/api/course")
 */
class CourseController extends FOSRestController
{
    /**
     * Lists all Course entities.
     *
     * @Rest\Options("/", name="app_api_courses")
     *
     * @Rest\QueryParam(
     *     name="begin",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course start date."
     * )
     * @Rest\QueryParam(
     *     name="end",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course end date."
     * )
     * @Rest\QueryParam(
     *     name="title",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="Title of the course."
     * )
     * @Rest\QueryParam(
     *     name="candidate_limit",
     *     requirements="\d+",
     *     default="1",
     *     nullable=false,
     *     description="Limit of total candidates on this course."
     * )
     *
     * @Doc\ApiDoc(
     *     section="Courses",
     *     resource=true,
     *     description="Get all courses",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     *
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $courses = $em->getRepository('CourseBundle:Course')->findAll();

        if (!empty($courses) && is_array($courses)) {

            $serializedEntity = $this->container->get('serializer')->serialize($courses, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Create a new Course entity.
     *
     * @Rest\Post("/", name="app_api_new_course")
     * @Rest\View(statusCode=201)
     *
     * @Rest\QueryParam(
     *     name="begin",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course start date."
     * )
     * @Rest\QueryParam(
     *     name="end",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course end date."
     * )
     * @Rest\QueryParam(
     *     name="title",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="Title of the course."
     * )
     * @Rest\QueryParam(
     *     name="candidateLimit",
     *     requirements="\d+",
     *     default="1",
     *     nullable=false,
     *     description="Limit of total candidates on this course."
     * )
     *
     * @Doc\ApiDoc(
     *      section="Courses",
     *      description="Create a new course.",
     *      statusCodes={
     *          201="Returned if course has been successfully created",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      }
     * )
     *
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $course = new Course();

        $begin = $request->get('begin');
        $end = $request->get('end');
        $title = $request->get('title');
        $candidateLimit = $request->get('candidateLimit');

        $course->setBegin($begin);
        $course->setEnd($end);
        $course->setTitle($title);
        $course->setCandidateLimit($candidateLimit);

        if (!empty($begin) && !empty($end) && !empty($title) && !empty($candidateLimit)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Finds and displays a Course entity.
     *
     * @Rest\Get("/{id}", name="app_api_course", requirements={"id"="\d+"})
     *
     * @Doc\ApiDoc(
     *     section="Courses",
     *     resource=true,
     *     description="Get course by id",
     *     requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The course unique identifier."
     *          }
     *      },
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     * @Method("GET")
     */
    public function showAction(Course $course)
    {
        if (!empty($course) && is_object($course)) {

            $serializedEntity = $this->container->get('serializer')->serialize($course, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Rest\Put(
     *     path = "/{id}",
     *     name = " app_api_edit_course",
     *     requirements = {"id"="\d+"}
     * )
     *
     *
     * @Rest\QueryParam(
     *     name="begin",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course start date."
     * )
     * @Rest\QueryParam(
     *     name="end",
     *     requirements="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}",
     *     nullable=false,
     *     description="Course end date."
     * )
     * @Rest\QueryParam(
     *     name="title",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="Title of the course."
     * )
     * @Rest\QueryParam(
     *     name="candidateLimit",
     *     requirements="\d+",
     *     default="1",
     *     nullable=false,
     *     description="Limit of total candidates on this course."
     * )
     * 
     * @Doc\ApiDoc(
     *      section="Courses",
     *      description="Edit an existing course.",
     *      statusCodes={
     *          201="Returned if course has been successfully edited",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The course unique identifier."
     *          }
     *      },
     * )
     * @Method("PUT")
     */
    public function editAction(Request $request, Course $course)
    {
        $begin = $request->get('begin');
        $end = $request->get('end');
        $title = $request->get('title');
        $candidateLimit = $request->get('candidateLimit');

        if ((!empty($begin) || !empty($end) || !empty($title) || !empty($candidateLimit)) && (!empty($course) && is_object($course))) {
            $course->setBegin($begin);
            $course->setEnd($end);
            $course->setTitle($title);
            $course->setCandidateLimit($candidateLimit);

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }

    /**
     * Add a candidate to a course.
     *
     * @Rest\Put(
     *     path = "/{id}/register",
     *     name = " app_api_add_candidate",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Doc\ApiDoc(
     *      section="Courses",
     *      description="Edit an existing course.",
     *      statusCodes={
     *          201="Returned if course has been successfully edited",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The course unique identifier."
     *          },
     *          {
     *              "name"="user_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The user unique identifier."
     *          },
     *      },
     * )
     * @Method("PUT")
     */
    public function addCandidate(Request $request, Course $course, \UserBundle\Entity\User $user)
    {
        if ((!empty($course) && is_object($course)) && (!empty($user) && is_object($user))) {
            $limit = $course->getCandidateLimit();
            $candidates = $course->getCandidate();
            $currentCandidates = count($candidates[0]);


            //If number of candidates is less or equal to limit, we can't add more
            if ($currentCandidates <= $limit) {
                $course->addCandidate($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($course);
                $em->flush();

                $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');
            } else {
                $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
            }

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }

    /**
     * Remove a candidate from a course.
     *
     * @Rest\Delete(
     *     path = "/{id}/register",
     *     name = " app_api_remove_candidate",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Doc\ApiDoc(
     *      section="Courses",
     *      description="Edit an existing course.",
     *      statusCodes={
     *          201="Returned if course has been successfully edited",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The course unique identifier."
     *          },
     *          {
     *              "name"="user_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The user unique identifier."
     *          },
     *      },
     * )
     * @Method("DELETE")
     */
    public function removeCandidate(Request $request, Course $course, \UserBundle\Entity\User $user)
    {
        if($course->getCandidate()->contains($user)) {
            //Two way remove
            $course->removeCandidate($user);
            $user->removeCourse($course);

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }

    /**
     * Deletes a Course entity.
     *
     * @Rest\Delete(
     *     path = "/{id}",
     *     name = "app_api_delete_course",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(statusCode=204)
     *
     * @Doc\ApiDoc(
     *      section="Courses",
     *      description="Delete an existing course.",
     *      statusCodes={
     *          201="Returned if course has been successfully deleted",
     *          400="Returned if course does not exist",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The course unique identifier."
     *          }
     *      },
     * )
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Course $course)
    {
        if (!empty($course) && is_object($course)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($course);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }
}
