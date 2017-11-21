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
use VientoSur\App\AppBundle\Entity\Promotions;
use BackendBundle\Form\PromotionsType;

/**
 * @Route("/{_locale}/promotions", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class PromotionsController extends Controller
{
    /**
     * @param $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="promotion_list")
     * @return response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $querySearch = $request->get('query');

        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.promotion');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
            $dql = "SELECT p 
                FROM VientoSurAppAppBundle:Promotions p 
                ORDER BY p.id ASC";
            $query = $em->createQuery($dql);
        }
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);

        return $this->render(':admin/promotions:list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/new", name="promotion_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $entity = new Promotions();
        $form = $this->createForm(new PromotionsType(), $entity, array(
            'id' => $this->getUser()->getId()
        ));
        
        if($form->handleRequest($request)->isValid())
        {
            $namePt = $form->get('namePt')->getData();
            $descriptionPt = $form->get('descriptionPt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();

            $em = $this->getDoctrine()->getManager();

            $image = $form->get('image')->getData();

            if($image != NULL)
            {
                $entity->setImageName($image);
            }

            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();

            $entity->setName($namePt);
            $entity->setDescription($descriptionPt);
            $entity->setTranslatableLocale('pt');
            $em->persist($entity);
            $em->flush();

            $entity->setName($nameEn);
            $entity->setDescription($descriptionEn);
            $entity->setTranslatableLocale('en');
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.promotion_added')
            );
            return $this->redirectToRoute('promotion_list');
        }
        return $this->render(':admin/promotions:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Promotions $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="promotion_edit")
     * @return response
     */
    public function putAction(Request $request, Promotions $entity)
    {
        $request->setMethod('PATCH');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PromotionsType(), $entity, [
            "method" => $request->getMethod(),
            "id" => $this->getUser()->getId()
        ]);
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);

        if ($form->handleRequest($request)->isValid())
        {
            $namePt = $form->get('namePt')->getData();
            $descriptionPt = $form->get('descriptionPt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();

            $image = $form->get('image')->getData();

            if($image != NULL)
            {
                $entity->setImageName($image);
            }
            $em->persist($entity);
            $em->flush();

            $entity->setName($namePt);
            $entity->setDescription($descriptionPt);
            $entity->setTranslatableLocale('pt');
            $em->persist($entity);
            $em->flush();

            $entity->setName($nameEn);
            $entity->setDescription($descriptionEn);
            $entity->setTranslatableLocale('en');
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.promotion_updated')
            );
            return $this->redirectToRoute('promotion_list');
        }
        return $this->render(':admin/promotions:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'translations' => $translations
        ));
    }

    /**
     * @param Promotions $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="promotion_delete")
     * @return route
     */
    public function deleteAction(Promotions $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.promotion_deleted')
        );
        return $this->redirectToRoute('promotion_list');
    }
}