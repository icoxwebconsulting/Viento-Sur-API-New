<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Picture;
use VientoSur\App\AppBundle\Form\PictureType;
use VientoSur\App\AppBundle\Entity\Room;

/**
 * @Route("dashboard-picture")
 */
class PictureController extends Controller
{
    /**
     * @param Hotel $hotel
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/hotel/{id}", name="hotel_picture_list")
     * @Method("GET")
     * @return response
     */
    public function hotelPictureListAction(Hotel $hotel)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findBy(array('hotel' => $hotel));
        return $this->render(':admin/Picture:hotel_image_list.html.twig', array(
            'entities' => $entities,
            'hotel' => $hotel
        ));
    }

    /**
     * @param Hotel $hotel
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/hotel-picture/new/{id}", name="hotel_picture_new")
     * @return response
     */
    public function hotelPictureNewAcion(Hotel $hotel)
    {
        $em = $this->getDoctrine()->getManager();
        $image = new Picture();
        $_FILES['file']['name'] = time()."_".$_FILES['file']['name'];
        $image->setImageName($_FILES['file']['name']);
        $image->setHotel($hotel);
        $em->persist($image);
        $em->flush();

        if (!empty($_FILES)) {
            $appPath = $this->container->getParameter('kernel.root_dir');
            $webPath = $appPath.'/../web/uploads/gallery_image/';

            $tempFile = $_FILES['file']['tmp_name'];
            move_uploaded_file($tempFile,$webPath. $_FILES['file']['name']);
        }
        return new JsonResponse(array('success' => true));
    }

    /**
     * @param Hotel $hotel Picture $picture
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/hotel-picture/{hotel}/delete/{picture}", name="hotel_picture_delete")
     * @return response
     */
    public function hotelPictureDeleteAcion(Hotel $hotel,Picture $picture)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($picture);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('hotel_picture_list', array('id' => $hotel->getId()));
    }

    /**
     * @param Room $room
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/room/{id}", name="room_picture_list")
     * @Method("GET")
     * @return response
     */
    public function roomPictureListAction(Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findBy(array('room' => $room));
        return $this->render(':admin/Picture:room_image_list.html.twig', array(
            'entities' => $entities,
            'room' => $room
        ));
    }

    /**
     * @param Room $room
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/room-picture/new/{id}", name="room_picture_new")
     * @return response
     */
    public function roomPictureNewAcion(Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $image = new Picture();
        $_FILES['file']['name'] = time()."_".$_FILES['file']['name'];
        $image->setImageName($_FILES['file']['name']);
        $image->setRoom($room);
        $em->persist($image);
        $em->flush();

        if (!empty($_FILES)) {
            $appPath = $this->container->getParameter('kernel.root_dir');
            $webPath = $appPath.'/../web/uploads/gallery_image/';

            $tempFile = $_FILES['file']['tmp_name'];
            move_uploaded_file($tempFile,$webPath. $_FILES['file']['name']);
        }
        return new JsonResponse(array('success' => true));
    }

    /**
     * @param Room $room Picture $picture
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/room-picture/{room}/delete/{picture}", name="room_picture_delete")
     * @return response
     */
    public function roomPictureDeleteAcion(Room $room,Picture $picture)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($picture);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('room_picture_list', array('id' => $room->getId()));
    }
//    /**
//     *
//     * @Method({"GET", "POST"})
//     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
//     */
//    public function ajaxSnippetImageSendAction(Request $request)
//    {
//        $em = $this->container->get("doctrine.orm.default_entity_manager");
//
//        $photo = new Photo();
//        $media = $request->files->get('file');
//
//        $photo->setFile($media);
//        $photo->setPath($media->getPathName());
//        $photo->setName($media->getClientOriginalName());
//        $photo->upload();
//        $em->persist($photo);
//        $em->flush();
//
//        return new JsonResponse(array('success' => true));
//    }

//    /**
//     * @Security("has_role('ROLE_HOTELIER')")
//     * @Route("/photo/", name="photo_list")
//     * @Method("GET")
//     * @return response
//     */
//    public function photoAction()
//    {
//        return $this->render('uploader.html.twig');
//    }
}