<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_purge
                if ($pathinfo === '/_profiler/purge') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:purgeAction',  '_route' => '_profiler_purge',);
                }

                // _profiler_info
                if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            if (0 === strpos($pathinfo, '/_configurator')) {
                // _configurator_home
                if (rtrim($pathinfo, '/') === '/_configurator') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_configurator_home');
                    }

                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
                }

                // _configurator_step
                if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_configurator_step')), array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',));
                }

                // _configurator_final
                if ($pathinfo === '/_configurator/final') {
                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_twig_error_test')), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        if (0 === strpos($pathinfo, '/app/hotel')) {
            // hotel_index
            if (rtrim($pathinfo, '/') === '/app/hotel/index') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hotel_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'hotel_index');
                }

                return array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::indexAction',  '_route' => 'hotel_index',);
            }
            not_hotel_index:

            // hotel_autocomplete
            if (rtrim($pathinfo, '/') === '/app/hotel/autocomplete') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hotel_autocomplete;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'hotel_autocomplete');
                }

                return array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::autocompleteHotelAction',  '_route' => 'hotel_autocomplete',);
            }
            not_hotel_autocomplete:

            if (0 === strpos($pathinfo, '/app/hotel/s')) {
                if (0 === strpos($pathinfo, '/app/hotel/send')) {
                    // viento_sur_app_app_homepage_send_flights
                    if ($pathinfo === '/app/hotel/send/flights/itineraries') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_viento_sur_app_app_homepage_send_flights;
                        }

                        return array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::sendFlightsItinerariesAction',  '_route' => 'viento_sur_app_app_homepage_send_flights',);
                    }
                    not_viento_sur_app_app_homepage_send_flights:

                    // viento_sur_app_app_homepage_send_hotels
                    if ($pathinfo === '/app/hotel/send/hotels/availabilities') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_viento_sur_app_app_homepage_send_hotels;
                        }

                        return array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::sendHotelsAvailabilitiesAction',  '_route' => 'viento_sur_app_app_homepage_send_hotels',);
                    }
                    not_viento_sur_app_app_homepage_send_hotels:

                }

                if (0 === strpos($pathinfo, '/app/hotel/show')) {
                    // viento_sur_app_app_homepage_show_hotel_id
                    if (preg_match('#^/app/hotel/show/(?P<idHotel>[^/]++)/availabilities/(?P<restUrl>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_viento_sur_app_app_homepage_show_hotel_id;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'viento_sur_app_app_homepage_show_hotel_id')), array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::showHotelIdAvailabilitiesAction',));
                    }
                    not_viento_sur_app_app_homepage_show_hotel_id:

                    // viento_sur_app_app_homepage_show_hotel_photo
                    if (0 === strpos($pathinfo, '/app/hotel/show/details') && preg_match('#^/app/hotel/show/details(?P<idHotel>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_viento_sur_app_app_homepage_show_hotel_photo;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'viento_sur_app_app_homepage_show_hotel_photo')), array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::detailsHotelListForIdAction',));
                    }
                    not_viento_sur_app_app_homepage_show_hotel_photo:

                }

            }

            // viento_sur_app_app_homepage_send_hotel_booking
            if ($pathinfo === '/app/hotel/booking/hotel/send') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_viento_sur_app_app_homepage_send_hotel_booking;
                }

                return array (  '_controller' => 'VientoSur\\App\\AppBundle\\Controller\\HotelController::sendHotelBookingAction',  '_route' => 'viento_sur_app_app_homepage_send_hotel_booking',);
            }
            not_viento_sur_app_app_homepage_send_hotel_booking:

        }

        // fos_js_routing_js
        if (0 === strpos($pathinfo, '/js/routing') && preg_match('#^/js/routing(?:\\.(?P<_format>js|json))?$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'fos_js_routing_js')), array (  '_controller' => 'fos_js_routing.controller:indexAction',  '_format' => 'js',));
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
