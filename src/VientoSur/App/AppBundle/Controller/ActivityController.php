<?php
namespace VientoSur\App\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use VientoSur\App\AppBundle\Entity\Reservation;
use Symfony\Component\Validator\Constraints\DateTime;
use VientoSur\App\AppBundle\Entity\Passengers;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Activity controller.
 *
 * @Route("/{_locale}/activity", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ActivityController extends Controller
{
    /**
     *
     * @Route("/send/activity/process-search", name="viento_sur_process_search_activity")
     * @Method("POST")
     */
    public function sendActivityProcessSearchAction(Request $request)
    {
        $session = $request->getSession();
        $address = '';
        
        $session->remove('resevation_id');
        
        $destinationText = $request->get('autocomplete');
        $lat = $request->get('latitude');
        $lgn = $request->get('longitude');
        $locale      = $request->get('_locale');
        
        
        //form filter 
        $from_price = $request->get('from_price', 250);
        $to_price   = $request->get('to_price', 2560);
        
        $day_1      = $request->get('day_1', 0);
        $day_2      = $request->get('day_2', 0);
        $day_3      = $request->get('day_3', 0);
        $day_4      = $request->get('day_4', 0);
        $day_5      = $request->get('day_5', 0);
        $day_6      = $request->get('day_6', 0);
        $day_7      = $request->get('day_7', 0);
        
        $available  = $request->get('available');
        
        $duration   = $request->get('duration');
        
        $session->set('destination_activity', [
            'text' => $destinationText,
        ]);
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository("VientoSurAppAppBundle:Activity")->findByLatAndLgn($lat, $lgn, $from_price, $to_price, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $available, $duration, $locale);
        
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($entities, $page, $items_per_page,array('wrap-queries'=>true));
        
        foreach ($pagination as $value) {
            $address .= "'".$value['address_destination']."', ";
        }
        $address = trim($address, ',');
        
        return $this->render('VientoSurAppAppBundle:Activity:listActivityAvailabilities.html.twig', array(
            'pagination' => $pagination,
            'autocomplete' => $destinationText,
            'latitude' => $lat,
            'longitude' => $lgn,
            'from_price'=>$from_price,
            'to_price'=>$to_price,
            'day_1'=>$day_1,
            'day_2'=>$day_2,
            'day_3'=>$day_3,
            'day_4'=>$day_4,
            'day_5'=>$day_5,
            'day_6'=>$day_6,
            'day_7'=>$day_7,
            'available'=>$available,
            'duration'=>$duration,
            'address'=>$address
        ));
        
    }
    
    /**
     * @Route("/show/{id}/availabilities/{latitude}/{longitude}", name="viento_sur_app_app_homepage_show_activity_id", defaults={"latitude": -0.0, "longitude": -0.0})
     * @Method("GET")
     */
    public function showHotelIdAvailabilitiesAction(Request $request, $id, $latitude, $longitude)
    {
        $session     = $request->getSession();
        
        $id_activity = $request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        
        $activity = $entities = $em->getRepository("VientoSurAppAppBundle:Activity")->findOneById($id_activity);
        
        $pictures = $em->getRepository("VientoSurAppAppBundle:Picture")->findByActivity($activity);
        
        $array_week_disabled = $this->getArrayWeekDisabled($activity);
        
        $destinationText = "'".$activity->getAddressDestination()."'";
        
        $price       = $session->get('price')?$session->get('price'):$activity->getPrice();
        $can_adul    = $session->get('can_adul')?$session->get('can_adul'):0 ;
        $can_chil    = $session->get('can_chil')?$session->get('can_chil'):0;
        $date        = $session->get('date')?$session->get('date'):'';
        $schedule    = $session->get('schedule')?$session->get('schedule'):'';
        $several_day = '';
        
        if($activity->getSeveralDay()){
            $time_from = strtotime($activity->getFromSeveral());
            
            $newformatFrom = new \DateTime(date('Y-d-m',$time_from));
            
            $time_to = strtotime($activity->getToSeveral());
            
            $newformatTo = new \DateTime(date('Y-d-m',$time_to));
            
            $diff = $newformatFrom->diff($newformatTo);
            
            $several_day = $diff->days;
            
        }
        
        
        $date_disabled   = $em->getRepository("VientoSurAppAppBundle:DatesDisableActivity")->findByActivity($activity);
        
        $text_date_disabled = '["';
        
        foreach ($date_disabled as $value) {
            $text_date_disabled .= str_replace([',','-'], ['","','/'], $value->getDisableAt());
        }
        
        $text_date_disabled .= '"]';
        
        
        $date_new = new \DateTime();
        $hour_r = (int) $activity->getReservaHours();
        $date_new->modify("+$hour_r hours");
        $newdate = $date_new->format('d/m/Y');
        
        return $this->render('VientoSurAppAppBundle:Activity:showActivityIdAvailabilities.html.twig', array(
            'item'=>$activity,
            'pictures'=>$pictures,
            'autocomplete' => $destinationText,
            'latitude' => (float) $activity->getLatitudeDestination(),
            'longitude' => (float) $activity->getLongitudeDestination(),
            'address' => trim($destinationText, ','),
            'array_week_disabled'=>$array_week_disabled,
            'text_date_disabled' => $text_date_disabled,
            'price'=>$price,
            'schedule'=>$schedule,
            'date'=>$date,
            'can_adul'=>(Int) $can_adul,
            'can_chil'=>(Int) $can_chil,
            'several_day'=>(Int) $several_day,
            'from_severla'=> $activity->getFromSeveral(),
            'to_severla'=> $activity->getToSeveral(),
            'newdate'=> $newdate
        ));
    }
    
    /**
     * @Route("/booking/pay/", name="viento_sur_app_boking_action_pay")
     * @Method("POST")
     */
    public function bookingActionPayAction(Request $request)
    {
        $this->deleteFileAction();
        $session     = $request->getSession();
        
        $reservation_id = $session->get('resevation_id');
        
        if($reservation_id != ''){
            return $this->redirect($this->generateUrl('viento_sur_app_booking_activity_summary'));
        }
        
        
        $activity_id = $request->get('activity');
        $schedule    = $request->get('schedule');
        $symbol      = $request->get('symbol');
        $price       = $request->get('price');
        $can_adul    = $request->get('can-adul');
        $can_chil    = $request->get('can-chil');
        $date        = $request->get('date');
        
        $currencies_id = $session->get('targetCurrency')=='USD'?'USD':'ARS';
        
        $session->set('price', $price);
        $session->set('can_adul', $can_adul);
        $session->set('can_chil', $can_chil);
        $session->set('schedule', $schedule);
        $session->set('date', $date);
        $session->set('currencies_id', $currencies_id);
        $session->set('activity_id', $activity_id);
        $preference_arrival = null;
        $percentage_paid = null;
        
        
        $em = $this->getDoctrine()->getManager();
        
        $activity =  $em->getRepository("VientoSurAppAppBundle:Activity")->findOneById($activity_id);
        
        $pictures = $em->getRepository("VientoSurAppAppBundle:Picture")->findByActivity($activity);
        
        $destinationText = $activity->getAddressDestination();
        
        $destinationTextMap = "'".$activity->getAddressDestination()."'";
        
        $regreso = $this->getParameter('url_site').$this->generateUrl('viento_sur_app_boking_action_pay_mp_ok');
        $cancelado = '';
        
        $percentage_paid_enabled = $activity->getPercentagePaidEnabled();
        
        // Crea el objeto MP
        $mp = $this->get('grunch_mercadopago')->getMp();
        // Crea un token
        $token = $mp->get_access_token();
        
        
        // Configuramos los datos del cobro
        $preference_data = array(
            "items" => [
                    [
                        "title" => $activity->getId().'-'.$activity->getName().' - '.$activity->getActivityAgency()->getId().'-'.$activity->getActivityAgency()->getName() ,
                        "quantity" => 1,
                        "currency_id" => $currencies_id, // Si deseas saber con que tipos de monedas puedes cobrar visita https://api.mercadopago.com/currencies
                        "unit_price" => (double) $price
                    ],
                ],
                "default_payment_method_id" => "visa", // método de pago por default
                "installments" => 1,
                "external_reference"=> "Reference_1234",
                "back_urls" => [
                    "success" => $regreso,
                    "failure" => $cancelado
                ]      
        );
        
        // Enviar los datos al API de Mercado Pago para la generación del link
        $preference = $mp->create_preference($preference_data);
        
        // pago en destino mercado pago
        if($percentage_paid_enabled){
            
            $percentage_paid = $activity->getPercentagePaid();
            $price_percenteage = (double) ($price * ($percentage_paid/100));
            
            // Configuramos los datos del cobro en destino
            $preference_data_arrival = array(
                "items" => [
                        [
                            "title" => $activity->getId().'-'.$activity->getName().' - '.$activity->getActivityAgency()->getId().'-'.$activity->getActivityAgency()->getName() ,
                            "quantity" => 1,
                            "currency_id" => $currencies_id, // Si deseas saber con que tipos de monedas puedes cobrar visita https://api.mercadopago.com/currencies
                            "unit_price" => (double) $price_percenteage
                        ],
                    ],
                    "default_payment_method_id" => "visa", // método de pago por default
                    "installments" => 1,
                    "external_reference"=> "Reference_1234_67",
                    "back_urls" => [
                        "success" => $regreso,
                        "failure" => $cancelado
                    ]      
            );
            
            // Enviar los datos al API de Mercado Pago para la generación del link
            $preference_arrival = $mp->create_preference($preference_data_arrival);
        }
        
        return $this->render('VientoSurAppAppBundle:Activity:payActivityBooking.html.twig', array(
            'item'=>$activity,
            'pictures'=>$pictures,
            'autocomplete' => $destinationText,
            'latitude' => $activity->getLatitudeDestination(),
            'longitude' => $activity->getLongitudeDestination(),
            'address' => trim($destinationTextMap, ','),
            'address_map' => trim($destinationText, ','),
            'preference'=>$preference,
            'price' =>$price,
            'schedule'=>$schedule,
            'date'=>$date,
            'can_adul'=>$can_adul,
            'can_chil'=>$can_chil,
            'preference_arrival'=>$preference_arrival,
            'percentage_paid'=>$percentage_paid
        ));
    }
    
    /**
     * @Route("/booking/session/data/ok/", name="viento_sur_app_boking_session_ok")
     * @Method("GET")
     */
    public function bookingSessionOkAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('name_buyer', $request->get('first_name'));
        $session->set('lastname_buyer', $request->get('last_name'));
        $session->set('document_number', $request->get('document_number'));
        $session->set('country_code', $request->get('country_code'));
        $session->set('contact_type', $request->get('contact_type'));
        $session->set('contact_number', $request->get('contact_number'));
        $session->set('contact_email', $request->get('contact_email'));
        $session->set('pay_arrival', $request->get('pay_arrival'));
        
        echo 'ok';
        exit();
        
    }
    
    
    /**
     * @Route("/booking/mp/ok/", name="viento_sur_app_boking_action_pay_mp_ok")
     */
    public function bookingPayMPOkAction(Request $request)
    {
        $session        = $request->getSession();
        $em             = $this->getDoctrine()->getManager();
        $isIframe       = $session->get("iframe");
        $agencyPartner  = $session->get("agency");
        
        $reservation_id = $session->get('resevation_id');
        
        if($reservation_id != ''){
            return $this->redirect($this->generateUrl('viento_sur_app_booking_activity_summary'));
        }
        
        $collection_id      = $request->get('collection_id');
        $external_reference = $request->get('external_reference');
        $payment_type       = $request->get('payment_type');
        
        $price         = $session->get('price');
        $can_adul      = $session->get('can_adul');
        $can_chil      = $session->get('can_chil');
        $schedule      = $session->get('schedule');
        $date          = $session->get('date');
        $currencies_id = $session->get('currencies_id');
        $pay_arrival   = $session->get('pay_arrival');
        
        $name_buyer      = $session->get('name_buyer');
        $lastname_buyer  = $session->get('lastname_buyer');
        $document_number = $session->get('document_number');
        $country_code    = $session->get('country_code');
        $contact_type    = $session->get('contact_type');
        $contact_number  = $session->get('contact_number');
        $contact_email   = $session->get('contact_email');
        
        $activity_id     = $session->get('activity_id');
        
        $activity =  $em->getRepository("VientoSurAppAppBundle:Activity")->findOneById($activity_id);
        
        $price_percenteage = null;
        $rest_of_pay       = null;
        
        if($pay_arrival == 1 && $activity->getPercentagePaidEnabled()){
            $percentage_paid = $activity->getPercentagePaid();
            $price_percenteage = (double) ($price * ($percentage_paid/100));
            $rest_of_pay = (double) $price - $price_percenteage;
        }
        
        $reservation = new Reservation();
        
        // A Hack using pages.dateformat.default: 'm-d-Y'`
        $datetime = explode('/', $date);
        
        $cheking = new \DateTime($datetime[1].'/'.$datetime[0].'/'.$datetime[2]);
        
        $reservation->setCollectionId($collection_id);
        $reservation->setExternalReference($external_reference);
        $reservation->setPaymentType($payment_type);
        $reservation->setTotalPrice($price);
        $reservation->setCanAdul($can_adul);
        $reservation->setCanChil($can_chil);
        $reservation->setCheckin($cheking);
        $reservation->setCheckout($cheking);
        $reservation->setSchedule($schedule);
        $reservation->setCurrenciesId($currencies_id);
        $reservation->setHolderName($name_buyer.' '.$lastname_buyer);
        $reservation->setContactType($contact_type);
        $reservation->setPhoneNumber($country_code.' '.$contact_number);
        $reservation->setEmail($contact_email);
        $reservation->setDocumentNumber($document_number);
        $reservation->setActivity($activity);
        $reservation->setActivityAgency($activity->getActivityAgency());
        $reservation->setRefundable(0);
        $reservation->setPercentagePaid($price_percenteage);
        $reservation->setPercentagePaidEnabled($pay_arrival);
        $reservation->setRestOfPay($rest_of_pay);
        $origen = $isIframe == true?$session->get('name_agency'):'vientosur';
        $reservation->setOrigin($origen);
        
        if($agencyPartner){
            $activityAgencyPartner = $em->getRepository("VientoSurAppAppBundle:ActivityAgency")->findOneById($agencyPartner);
            $reservation->setActivityAgencyPartner($activityAgencyPartner);
        }
        
        $em->persist($reservation);
        
        $passenger = new Passengers();
        $passenger->setName($name_buyer);
        $passenger->setLastName($lastname_buyer);
        $passenger->setDocument($document_number);
        $passenger->setReservation($reservation);
        $em->persist($passenger);
        
        $em->flush();
        
        
        $session->set('resevation_id', $reservation->getId());
        
        if($isIframe){
            return $this->redirect($this->generateUrl('viento_sur_app_booking_activity_summary')); 
        }else{
            return $this->render('VientoSurAppAppBundle:Activity:bookingPayMPOk.html.twig');  
        }    
    }
    
    /**
     *
     * @Route("/booking/summary/", name="viento_sur_app_booking_activity_summary")
     */
    public function payActivityBookingAction(Request $request)
    {
        $session     = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $locale = $request->get('_locale');
        
        $reservation_id = $session->get('resevation_id');
                    
        if($reservation_id === ''){
            return $this->redirectToRoute('homepage', array('_locale'=> $locale));
        }
        
        $reservation =  $em->getRepository("VientoSurAppAppBundle:Reservation")->findOneById($reservation_id);
       
        
        if(!file_exists($request->server->get('DOCUMENT_ROOT').'/'.$reservation_id.'.pdf')){
            $this->setPdfActivity($reservation_id, $em, $request, $this->get('knp_snappy.pdf'));
        }
        
        $this->container->get('hotel_service')->sendBookingActivityEmail($reservation->getId(), $reservation->getEmail());
        
        return $this->render('VientoSurAppAppBundle:Activity:summaryActivityBooking.html.twig',
                array(
                    'reservation'=>$reservation,
                    'item'=>$reservation->getActivity(),
                    'latitude' => $reservation->getActivity()->getLatitudeDestination(),
                    'longitude' => $reservation->getActivity()->getLongitudeDestination(),
                    'address_map' => trim($reservation->getActivity()->getAddressDestination(), ','),
                    'activity_phone'=>$reservation->getActivityAgency()->getPhone(),
                    'checking_date'=>$reservation->getCheckin(),
                    'schedule'=>$reservation->getSchedule(),
                    'cant_adut'=>$reservation->getCanAdul(),
                    'cant_chil'=>$reservation->getCanChil(),
                    'price'=>$reservation->getTotalPrice(),
                    'restOfPay'=>$reservation->getRestOfPay(),
                    'pdf' => false
                ));
        
    }
    
    private function getArrayWeekDisabled($activity){
        $array_week_disabled = "[%d, %l, %ma, %mi, %j, %v, %s]";
        
        if(!$activity->getSunday()){
            $array_week_disabled = str_replace ("%d", "0", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%d,", "", $array_week_disabled);
        }    
        
        if(!$activity->getMonday()){
            $array_week_disabled = str_replace ("%l", "1", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%l,", "", $array_week_disabled);
        }    
        
        if(!$activity->getTuesday()){
            $array_week_disabled = str_replace ("%ma", "2", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%ma,", "", $array_week_disabled);
        }    
        
        if(!$activity->getWednesday()){
            $array_week_disabled = str_replace ("%mi", "3", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%mi,", "", $array_week_disabled);
        }    
        
        if(!$activity->getThursday()){
            $array_week_disabled = str_replace ("%j", "4", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%j,", "", $array_week_disabled);
        }    
        
        if(!$activity->getFriday()){
            $array_week_disabled = str_replace ("%v", "5", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%v,", "", $array_week_disabled);
        }    
            
        if(!$activity->getSaturday()){
            $array_week_disabled = str_replace ("%s", "6", $array_week_disabled);
        }else{
            $array_week_disabled = str_replace ("%s", "", $array_week_disabled);
        }
        
        return $array_week_disabled;            
    }
    
    /**
     *
     * @Route("/send/activity/get-picture", name="viento_sur_activity_picture")
     * @Method("GET")
     */
    public function getPictureByActivityAction(Request $request){
        $id_activity = $request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        
        $actvity = $entities = $em->getRepository("VientoSurAppAppBundle:Activity")->findOneById($id_activity);
        
        $picture = $em->getRepository("VientoSurAppAppBundle:Picture")->findOneByActivity($actvity);
        
        
        if($picture){
            return new Response('/uploads/activity/image/'.$actvity->getId().'/'.$picture->getImageName());
        }else{
            return new Response("/img/no-img.jpg");
        }
    }
    
    
    /**
     *
     * @Route("/send/activity/get-day", name="viento_sur_activity_day")
     * @Method("GET")
     */
    public function getDayByActivityAction(Request $request){
        
        $id_activity = $request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        
        $actvity = $entities = $em->getRepository("VientoSurAppAppBundle:Activity")->findOneById($id_activity);
        
        return new Response($actvity->getFirstDay());
    }
    
    /**
     *
     * @Route("/send/activity/get-convert-currency", name="viento_sur_activity_convert_currency")
     * @Method("GET")
     */
    public function getConvertCurrencyAction(Request $request){
        
        $symbol = $request->get('symbol');
        $from   = $symbol === 'US$'?'USD':'ARS';
        $from_Currency = urlencode($from);
        $to_Currency = urlencode('ARS');
        $query =  "{$from_Currency}_{$to_Currency}";
        $amount = $request->get('amount');
        
        $json = file_get_contents("http://free.currencyconverterapi.com/api/v5/convert?q={$query}&compact=y");
        $obj = json_decode($json, true);
        
        $val = floatval($obj["$query"]['val']);

        $total = $val * $amount;
        
        return new Response(number_format($total, 0, '.', ','));
        
    }
    
    /**
     * @Route("/booking/pdf/save/{reservationId}", name="viento_sur_app_save_activity_pdf")
     */
    public function savePdfToAttachAction(Request $request)
    {
        $reservationId = $request->get('reservationId');
        
        $em = $this->getDoctrine()->getManager();
        
        if(!file_exists($request->server->get('DOCUMENT_ROOT').'/'.$reservationId.'.pdf')){
            $this->setPdfActivity($reservationId, $em, $request, $this->get('knp_snappy.pdf'));
        }
        
        $content = file_get_contents($request->server->get('DOCUMENT_ROOT').'/'.$reservationId.'.pdf');

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$reservationId.'.pdf"');

        $response->setContent($content);
        return $response;
    }
    
    /**
     * 
     * @param type $reservationId
     * @param type $em
     * @param Request $request
     * @param type $knp_snappy
     * @return boolean
     */
    private function setPdfActivity($reservationId, $em, Request $request, $knp_snappy){
        
        $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($reservationId);
        $picture = $em->getRepository("VientoSurAppAppBundle:Picture")->findOneByActivity($reservation->getActivity());
        
        $base_path ='';
        
        if($picture)
        $base_path = $request->server->get('DOCUMENT_ROOT').'/uploads/activity/image/'.$reservation->getActivity()->getId().'/'.$picture->getImageName();
        
        $knp_snappy->generateFromHtml(
            $this->renderView(
                'VientoSurAppAppBundle:Pdf:bookingActivity.html.twig', array(
                    'reservation'=>$reservation,
                    'item'=>$reservation->getActivity(),
                    'latitude' => (Float) $reservation->getActivity()->getLatitudeDestination(),
                    'longitude' => (Float) $reservation->getActivity()->getLongitudeDestination(),
                    'address_map' => trim($reservation->getActivity()->getAddressDestination(), ','),
                    'activity_phone'=>$reservation->getActivityAgency()->getPhone(),
                    'checking_date'=>$reservation->getCheckin(),
                    'schedule'=>$reservation->getSchedule(),
                    'cant_adut'=>$reservation->getCanAdul(),
                    'cant_chil'=>$reservation->getCanChil(),
                    'price'=>$reservation->getTotalPrice(),
                    'pdf' => true,
                    'base_path'=>$base_path
                )),
            $reservation->getId().'.pdf'
        );
        
        return true;
    }
    
    /**
     * @Route("/delete/fs", name="dete_file")
     *
     */
    public function deleteFileAction()
    {
        $fs = new Filesystem();
        $file = $this->container->getParameter('kernel.root_dir') . '/../web/voucher-vs.pdf';
        if (file_exists($file)){
        $fs->remove($file);
        }
        return new Response('file deleted');
    }
    
    /**
     * @Route("/booking/cancel/reservation/{reservationId}/{external}", name="viento_sur_app_cancel_reservation_activity")
     */
    public function cancelReservationActivity(Request $request){
        
        $reservationId = $request->get('reservationId');
        $external      = $request->get('external');
        
        $em = $this->getDoctrine()->getManager();
        
        $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneBy(['id'=>$reservationId, 'externalReference'=>$external]);
        
        $reservation->setStatus('cancelled');
        
        $reservationCancellation = new ReservationCancellation();
        $reservationCancellation->setDescription('Cancelado desde la web de vientosur');
        $reservationCancellation->setCreatedBy(null);
        $reservationCancellation->setCode($cancel['id']);
        $reservationCancellation->setReservation($internal);

        $em->persist($reservationCancellation);
        $em->flush();
        
        echo $reservation->getId();
        exit();
        
    }
    
    /**
     * @Route("/booking/button/iframe/{id_agency}/{iframe}/{name}", name="viento_sur_app_button_iframe_activity")
     */
    public function getButtonIframe(Request $request){
        
        $agency      = $request->get('id_agency');
        $iframe      = $request->get('iframe');
        $name_agency = $request->get('name');
        $locale      = $request->get('_locale');
        
        $session     = $request->getSession();
        
        $session->set('agency', $agency);
        $session->set('iframe', $iframe);
        $session->set('name_agency', $name_agency);
        
        
        return $this->redirectToRoute('homepage', array('_locale'=> $locale, '_type' => 'actividad'));
    }
}