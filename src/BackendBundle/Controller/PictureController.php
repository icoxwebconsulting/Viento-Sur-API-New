<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Activity;
use VientoSur\App\AppBundle\Entity\Picture;
use BackendBundle\PictureType;
use VientoSur\App\AppBundle\Entity\Room;

/**
 * @Route("/{_locale}/dashboard-picture", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
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
    
    /**
     * @param Activity $activity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/activity/{id}", name="activity_picture_list")
     * @Method("GET")
     * @return response
     */
    public function activityPictureListAction(Activity $activity)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findBy(array('activity' => $activity));
        return $this->render(':admin/Picture:activity_image_list.html.twig', array(
            'entities' => $entities,
            'activity' => $activity
        ));
    }

    /**
     * @param Activity $activity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/activity-picture/new/{id}", name="activity_picture_new")
     * @return response
     */
    public function activityPictureNewAcion(Activity $activity)
    {
        $em = $this->getDoctrine()->getManager();
        $image = new Picture();
        $_FILES['file']['name'] = time()."_".$_FILES['file']['name'];
        $image->setImageName($_FILES['file']['name']);
        $image->setActivity($activity);
        $em->persist($image);
        $em->flush();

        if (!empty($_FILES)) {
            $appPath = $this->container->getParameter('kernel.root_dir');
            $webPath = $appPath.'/../web/uploads/activity/image/'.$activity->getId().'/';
            
            if(!is_dir($webPath)){
                if (!mkdir($webPath, 0777, true)) {
                    die('Failed to create folders...');
                }
            }     

            $tempFile = $_FILES['file']['tmp_name'];
            move_uploaded_file($tempFile,$webPath. $_FILES['file']['name']);
        }
        return new JsonResponse(array('success' => true));
    }

    /**
     * @param Activity $activity Picture $picture
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/activity-picture/{activity}/delete/{picture}", name="activity_picture_delete")
     * @return response
     */
    public function activityPictureDeleteAcion(Activity $activity,Picture $picture)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($picture);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('activity_picture_list', array('id' => $activity->getId()));
    }
}