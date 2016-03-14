<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends FOSRestController
{
    /**
     * Lists all User entities.
     *
     * @Rest\Get("/", name="app_api_users")
     *
     * @Rest\QueryParam(
     *     name="firstname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="user first name."
     * )
     * @Rest\QueryParam(
     *     name="lastname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User last name."
     * )
     * @Rest\QueryParam(
     *     name="gender",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User gender."
     * )
     *
     * @Doc\ApiDoc(
     *     section="Users",
     *     resource=true,
     *     description="Get all Users",
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

        $Users = $em->getRepository('UserBundle:User')->findAll();

        if (!empty($Users) && is_array($Users)) {

            $serializedEntity = $this->container->get('serializer')->serialize($Users, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Create a new User entity.
     *
     * @Rest\Post("/", name="app_api_new_user")
     * @Rest\View(statusCode=201)
     *
     * @Rest\QueryParam(
     *     name="firstname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="user first name."
     * )
     * @Rest\QueryParam(
     *     name="lastname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User last name."
     * )
     * @Rest\QueryParam(
     *     name="gender",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User gender."
     * )
     *
     * @Doc\ApiDoc(
     *      section="Users",
     *      description="Create a new User.",
     *      statusCodes={
     *          201="Returned if User has been successfully created",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      }
     * )
     *
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $gender = $request->get('gender');

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setGender($gender);
        $user->setUsername($firstname . "." . $lastname);
        $user->setEmail($firstname . "@" . $lastname . ".com");
        $user->setPassword(md5($firstname . "@" . $lastname . ".com")); //Purpose only, do not use in production mode as base algorythm is bcrypt

        if (!empty($firstname) && !empty($lastname) && !empty($gender)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Finds and displays a User entity.
     *
     * @Rest\Get("/{id}", name="app_api_user", requirements={"id"="\d+"})
     *
     * @Doc\ApiDoc(
     *     section="Users",
     *     resource=true,
     *     description="Get User by id",
     *     requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The User unique identifier."
     *          }
     *      },
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        if (!empty($user) && is_object($user)) {

            $serializedEntity = $this->container->get('serializer')->serialize($user, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }


    /**
     * Displays a form to edit an existing User entity.
     *
     * @Rest\Put(
     *     path = "/{id}",
     *     name = " app_api_edit_user",
     *     requirements = {"id"="\d+"}
     * )
     *
     *
     * @Rest\QueryParam(
     *     name="firstname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="user first name."
     * )
     * @Rest\QueryParam(
     *     name="lastname",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User last name."
     * )
     * @Rest\QueryParam(
     *     name="gender",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=false,
     *     description="User gender."
     * )
     *
     * @Doc\ApiDoc(
     *      section="Users",
     *      description="Edit an existing User.",
     *      statusCodes={
     *          201="Returned if User has been successfully edited",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The User unique identifier."
     *          }
     *      },
     * )
     * @Method("PUT")
     */
    public function editAction(Request $request, User $user)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $gender = $request->get('gender');

        if ((!empty($firstname) || !empty($lastname) || !empty($gender)) && (!empty($user) && is_object($user))) {
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setGender($gender);
            $user->setUsername($firstname . "." . $lastname);
            $user->setEmail($firstname . "@" . $lastname . ".com");
            $user->setPassword(md5($firstname . "@" . $lastname . ".com")); //Purpose only, do not use in production mode as base algorythm is bcrypt

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }

    /**
     * Deletes a User entity.
     *
     * @Rest\Delete(
     *     path = "/{id}",
     *     name = "app_api_delete_user",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Rest\View(statusCode=204)
     *
     * @Doc\ApiDoc(
     *      section="Users",
     *      description="Delete an existing User.",
     *      statusCodes={
     *          201="Returned if User has been successfully deleted",
     *          400="Returned if User does not exist",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The User unique identifier."
     *          }
     *      },
     * )
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        if (!empty($user) && is_object($user)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_CREATED, 'json');

        } else {
            $serializedEntity = $this->container->get('serializer')->serialize(Response::HTTP_BAD_REQUEST, 'json');
        }

        return new Response($serializedEntity);
    }
}
