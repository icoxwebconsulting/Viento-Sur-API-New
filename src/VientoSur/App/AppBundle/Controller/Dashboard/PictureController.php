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
use VientoSur\App\AppBundle\Form\PictureType;

/**
 * @Route("dashboard-picture")
 */
class PictureController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/hotel", name="hotel_picture_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findAll();
        return $this->render(':admin/Picture:list.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @Route("/hotel-picture/new", name="hotel_picture_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
//        $ds          = DIRECTORY_SEPARATOR;  //1
//
//        $storeFolder = 'uploads';   //2
        $em = $this->getDoctrine()->getManager();
        $image = new Picture();
//        $this->container->get('vich_uploader.storage')->upload($_REQUEST);

        $image->setImageName($_FILES['file']['name']);
        $image->setPosition(1);
        $image->setMainPicture(1);
//        echo "<pre>".print_r($_FILES, true)."</pre>";
        $em->persist($image);
        $em->flush();
        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/gallery_image');
        echo $webPath; die();
        if (!empty($_FILES)) {

//            $tempFile = $_FILES['file']['tmp_name'];          //3

//            $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
//
//            $targetFile =  $targetPath. $_FILES['file']['name'];  //5
//
//            move_uploaded_file($tempFile,$targetFile); //6
            $appPath = $this->container->getParameter('kernel.root_dir');
            $webPath = realpath($appPath . '/../web/gallery_image');
            echo $webPath;
        }
        return new Response('work');
    }
//        $entity = new Hotel();
//        $picture = new Picture();
//        $formb= $this->createForm(new PictureType(), $picture);
//        $form = $this->createForm(new HotelFormType(), $entity);
//
//        if($form->handleRequest($request)->isValid())
//        {
//            $em = $this->getDoctrine()->getManager();
//
////            $image = $form->get('image')->getData();
////
////            if($image != NULL)
////            {
////                $entity->setImageName($image);
////            }
////            echo"<pre>".print_r($form->get('image')->getData(),true)."</pre>";die();
//            $entity->setOrigen('VS');
//            $entity->setCreatedBy($this->getUser());
//            $em->persist($entity);
//            $em->flush();
//            $this->addFlash(
//                'success',
//                $this->get('translator')->trans('admin.messages.added')
//            );
//            return $this->redirectToRoute('hotel_list');
//        }
//        return $this->render(':admin/hotel:form.html.twig', array(
//            'form' => $form->createView(),
//            'formb' => $formb->createView()
//        ));

//    }
}