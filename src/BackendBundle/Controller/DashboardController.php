<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Room;

/**
 * @Route("dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $hotel =  $em->getRepository("VientoSurAppAppBundle:Hotel")->find(2);
        $room =  $em->getRepository("VientoSurAppAppBundle:Room")->find(1);
//        $hotel->setName('portugues');
//        $hotel->setDescription('descirption portugues');
//        $hotel->setTranslatableLocale('pt');
//        $em->persist($hotel);
//        $em->flush();

//        $room = new Room();
//        $room->setName('espanol');
//        $room->setAvailability(4);
//        $room->setCancellationPolicity('cancelacion espanol');
//        $room->setCapacity(5);
//        $room->setHotel($hotel);
//        $room->setRoomCode(25);
//        $room->setNightlyPrice(40);
//        $em->persist($room);

//        $room->setName('ingles');
//        $room->setCancellationPolicity('cancelacion ingles');
//        $room->setTranslatableLocale('en');
//        $em->persist($room);

//        $room->setName('portugues');
//        $room->setCancellationPolicity('cancelacion portugues');
//        $room->setTranslatableLocale('pt');
//        $em->persist($room);
//
//        $em->flush();

       /* $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($room);

        $entity = $em->getRepository("VientoSurAppAppBundle:Hotel")->findAll();
        return $this->render('admin/index.html.twig', array(
            'hotel' => $translations
        ));*/
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/home/", name="dashboard_user")
     * @return array
     */
    public function homeAction()
    {
//        $user = $this->getUser();
//        $em = $this->getDoctrine()->getManager();
//        $medias = $em->getRepository('AppBundle:Media')->findBy(array('created_by' => $user->getId()),
//            array('modified' => 'DESC'),
//            10);
//        $news = $em->getRepository('AppBundle:News')->findBy(array('created_by' => $user->getId()),
//            array('modified' => 'DESC'),
//            10);
//
//        return $this->render('admin/home.html.twig', array(
//            'medias' => $medias,
//            'news' => $news,
//        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
//        $translations = $repository->findTranslations($room);

//        $entity = $em->getRepository("VientoSurAppAppBundle:Hotel")->findAll();
        return $this->render('admin/index.html.twig');
//        return $this->render('admin/index.html.twig', array(
//            'hotel' => $translations
//        ));
    }
}
