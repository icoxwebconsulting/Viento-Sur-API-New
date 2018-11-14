<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\ActivityAgency;
use BackendBundle\Form\ActivityAgencyType;
use VientoSur\App\AppBundle\Repository\ActivityAgencyRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/{_locale}/activity_agency", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ActivityAgencyController extends Controller
{
    /**
     * @param $request
     * @param $user
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/list", name="activity_agency_list")
     * @return array
     */
    public function indexAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $querySearch = $request->get('query');
        
        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.bed');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
        $dql = "SELECT ag
                FROM VientoSurAppAppBundle:ActivityAgency ag 
                ORDER BY ag.id ASC";
        $query = $em->createQuery($dql);
        }
        
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);
        
        return $this->render(':admin/activity_agency:list.html.twig', array(
            'pagination' => $pagination
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/new", name="activity_agency_new")
     * @return response
     */
    public function newAction(Request $request)
    {
        $entity = new ActivityAgency();
        $form = $this->createForm(new ActivityAgencyType(), $entity);
        $em = $this->getDoctrine()->getManager();
        
        $this->formAction($form, $request, $em, $entity, 'agregado', 'new');
        
        return $this->render(':admin/activity_agency:form.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @param Request $request
     * @param ActivityAgency $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="activity_agency_edit")
     * @return response
     */
    public function editAction(Request $request, ActivityAgency $entity) {
        
        $request->setMethod('PATCH');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ActivityAgencyType(), $entity, [
            "method" => $request->getMethod(),
        ]);
        
        $this->formAction($form, $request, $em, $entity, 'editado', 'edit');
        
        return $this->render(':admin/activity_agency:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
        
    }
    
    /**
     * @param ActivityAgency $entity entity
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="activity_agency_delete")
     * @return route
     */
    public function deleteAction(ActivityAgency $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            'La agencia se ha eliminado correctamente!'
        );
        return $this->redirectToRoute('activity_agency_list');
    }
    
    /**
     * formAction
     * @param ActivityAgencyType $form
     * @param Request $request
     * @param DoctrineManager $em
     * @param ActivityAgency $entity entity
     * @param String $textMsj msj
     * @param String $action action
     * @return response
     */
    protected function formAction($form, $request, $em, $entity, $textMsj, $action)
    {
        $managerEntity = $this->getDoctrine()->getManager();
        if($form->handleRequest($request)->isValid())
        {
            $image = $form->get('image')->getData();

            if($image != NULL)
            {
                $entity->setImageName($image);
            }

            $file_pdf = $form->get('file_pdf')->getData();

            if($file_pdf != NULL)
            {
                $entity->setFileNamePdf($file_pdf);
            }
            
            $entity->setCreatedBy($this->getUser());
            
            if($action == 'new'){
                /* cambiar la clave*/
                //$plainPassword = substr( md5(microtime()), 1, 8);
                $plainPassword = '123456';

                $entity->getUser()->setPlainPassword($plainPassword);
                
                $entity->getUser()->setRoles(['ROLE_ACTIVITY']);
                
                $entity->getUser()->setEnabled(true);
            }
            
            $em->persist($entity);
            $em->flush();
            
            /* si la agencia es nueva se debe enviar un email con el usuario y la clave de acceso*/

            $dql = 'SELECT u.email
                FROM VientoSurAppAppBundle:User u
                WHERE u.email LIKE :email';
            $query = $managerEntity->createQuery($dql)->setParameter('email', $entity->getUser()->getEmail());
            $result = $query->getResult();

            if($action == 'new'){
                if(!empty($result)){
                    $template = 'registerAgency';
                    $nameAgency = $entity->getName();
                    $fullName = $entity->getUser()->getFirstName(). ' ' .$entity->getUser()->getLastName();
                    $email = $entity->getUser()->getEmail();
                    $mail_params = array(
                        'fullName' => $fullName,
                        'nameAgency' => $nameAgency,
                        'email' => $email,
                        'password' => $plainPassword
                    );
                    $message = $this->container->get('mail_manager');

                    $message->sendEmail($template, $mail_params);

                }
            }


            switch ($textMsj){
                case 'agregado':
                    $message = 'admin.messages.add_agency';
                    break;
                case 'editado':
                    $message = 'admin.messages.edit_agency';
                    break;
            }

            $this->addFlash(
                'success',
                $this->get('translator')->trans($message)
            );
            return $this->redirectToRoute('activity_agency_list');
        }
        
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/button-activity", name="button_actyvity")
     * @Method("GET")
     * @return array
     */
    public function getButtonActivityAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        
        $activityAgency = $em->getRepository("VientoSurAppAppBundle:ActivityAgency")->findOneByUser($user);
        
        return $this->render(':admin/activity_agency:button_activity.html.twig', array(
            'entity' => $activityAgency
        ));
        
    }
}