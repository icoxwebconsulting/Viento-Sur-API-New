<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\GeneralInformation;
use BackendBundle\Form\GeneralInformationType;

/**
 * @Route("/{_locale}/general_information", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class GeneralInformationController extends Controller
{
     /**
     * @param $request
     * @param $user
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/list", name="general_information_list")
     * @return array
     */
    public function indexAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $querySearch = $request->get('query');
        
        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.bed');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
        $dql = "SELECT gi
                FROM VientoSurAppAppBundle:GeneralInformation gi
                ORDER BY gi.id ASC";
        $query = $em->createQuery($dql);
        }
        
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);
        
        return $this->render(':admin/general_information:list.html.twig', array(
            'pagination' => $pagination
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/new", name="general_information_new")
     * @return response
     */
    public function newAction(Request $request)
    {
        $entity = new GeneralInformation();
        $form = $this->createForm(new GeneralInformationType(), $entity);
        $em = $this->getDoctrine()->getManager();
        
        $this->formAction($form, $request, $em, $entity, 'agregado', 'new');
        
        return $this->render(':admin/general_information:form.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @param Request $request
     * @param GeneralInformation $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="general_information_edit")
     * @return response
     */
    public function editAction(Request $request, GeneralInformation $entity) {
        
        $request->setMethod('PATCH');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new GeneralInformationType(), $entity, [
            "method" => $request->getMethod(),
        ]);
        
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);
        
        $this->formAction($form, $request, $em, $entity, 'editado', 'edit');
        
        return $this->render(':admin/general_information:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'translations' => $translations
        ));
        
    }
    
    /**
     * @param GeneralInformation $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="general_information_delete")
     * @return route
     */
    public function deleteAction(GeneralInformation $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            'La Información general de la actividad se ha eliminado correctamente!'
        );
        return $this->redirectToRoute('general_information_list');
    }
    
    /**
     * formAction
     * @param GeneralInformation $form
     * @param Request $request
     * @param DoctrineManager $em
     * @param GeneralInformation $entity entity
     * @param String $textMsj msj
     * @param String $action action
     * @return response
     */
    protected function formAction($form, $request, $em, $entity, $textMsj, $action){
        
        if($form->handleRequest($request)->isValid())
        {
            
            $namePt = $form->get('namePt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();

            $entity->setName($namePt);
            $entity->setTranslatableLocale('pt');
            $em->persist($entity);
            $em->flush();

            $entity->setName($nameEn);
            $entity->setTranslatableLocale('en');
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('Se ha '.$textMsj.' correctamente la Información general de la actividad!')
            );
            return $this->redirectToRoute('general_information_list');
        }
    }    
}
