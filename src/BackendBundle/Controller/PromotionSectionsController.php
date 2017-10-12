<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use VientoSur\App\AppBundle\Entity\PromotionSections;
use BackendBundle\Form\PromotionSectionsType;

/**
 * @Route("/{_locale}/promotion-sections", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class PromotionSectionsController extends Controller
{
    /**
     * @param $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="promotion_sections_list")
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $querySearch = $request->get('query');

        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.promotionsections');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
            $dql = "SELECT ps 
                FROM VientoSurAppAppBundle:PromotionSections ps 
                ORDER BY ps.id ASC";
            $query = $em->createQuery($dql);
        }
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);


        return $this->render(':admin/promotionSections:list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/new", name="promotion_sections_new")
     * @return array
     */
    public function newAcion(Request $request)
    {
        $entity = new PromotionSections();
        $form = $this->createForm(new PromotionSectionsType(), $entity);

        if($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.promotion_sections_added')
            );
            return $this->redirectToRoute('promotion_sections_list');
        }
        return $this->render(':admin/promotionSections:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param PromotionSections $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="promotion_sections_edit")
     * @return array
     */
    public function putAction(Request $request, PromotionSections $entity)
    {
        $request->setMethod('PATCH');

        $form = $this->createForm(new PromotionSectionsType(), $entity, ["method" => $request->getMethod()]);
        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.promotion_sections_updated')
            );
            return $this->redirectToRoute('promotion_sections_list');
        }
        return $this->render(':admin/promotionSections:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    /**
     * @param PromotionSections $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="promotion_sections_delete")
     * @return route
     */
    public function deleteAction(PromotionSections $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.promotion_sections_deleted')
        );
        return $this->redirectToRoute('promotion_sections_list');
    }
}