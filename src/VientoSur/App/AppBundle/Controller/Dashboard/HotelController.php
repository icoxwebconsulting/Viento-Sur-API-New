<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Picture;
use VientoSur\App\AppBundle\Form\HotelFormType;
use VientoSur\App\AppBundle\Form\PictureType;

/**
 * @Route("dashboard-hotel")
 */
class HotelController extends Controller
{

    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="hotel_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $entity = new Hotel();
        $picture = new Picture();
        $formb= $this->createForm(new PictureType(), $picture);
        $form = $this->createForm(new HotelFormType(), $entity);

        if($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();

//            $image = $form->get('image')->getData();
//
//            if($image != NULL)
//            {
//                $entity->setImageName($image);
//            }
//            echo"<pre>".print_r($form->get('image')->getData(),true)."</pre>";die();
            $entity->setOrigen('VS');
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.added')
            );
            return $this->redirectToRoute('hotel_list');
        }
        return $this->render(':admin/hotel:form.html.twig', array(
            'form' => $form->createView(),
            'formb' => $formb->createView()
        ));
    }
}