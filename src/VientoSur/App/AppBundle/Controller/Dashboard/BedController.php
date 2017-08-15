<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Room;
use VientoSur\App\AppBundle\Entity\Bed;
use VientoSur\App\AppBundle\Form\BedType;

/**
 * @Route("dashboard-bed")
 */
class BedController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="bed_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Bed")->findBy(array('created_by' => $user->getId()));

        return $this->render(':admin/bed:list.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="bed_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $entity = new Bed();
        $form = $this->createForm(new BedType(), $entity);

        if($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.added')
            );
            return $this->redirectToRoute('bed_list');
        }
        return $this->render(':admin/bed:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Bed $entity entity
     * @Security("has_role('ROLE_USER')")
     * @Route("/edit/{id}", name="bed_edit")
     * @return response
     */
    public function putAction(Request $request, Bed $entity)
    {
        $request->setMethod('PATCH');

        $form = $this->createForm(new BedType(), $entity, ["method" => $request->getMethod()]);
        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('bed_list');
        }
        return $this->render(':admin/bed:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    /**
     * @param Bed $entity entity
     * @Security("has_role('ROLE_USER')")
     * @Route("/delete/{id}", name="bed_delete")
     * @return response
     */
    public function deleteAction(Bed $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('bed_list');
    }
}