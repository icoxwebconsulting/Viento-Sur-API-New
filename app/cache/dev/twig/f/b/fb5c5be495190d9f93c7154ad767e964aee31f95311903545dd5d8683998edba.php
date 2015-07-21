<?php

/* VientoSurAppAppBundle:Hotel:searchVuelos.html.twig */
class __TwigTemplate_fb5c5be495190d9f93c7154ad767e964aee31f95311903545dd5d8683998edba extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_182aa17bbd6c4f0b15b3997ddc57a5036571e2781c5c90eb3d3d95d3d4e1b4d1 = $this->env->getExtension("native_profiler");
        $__internal_182aa17bbd6c4f0b15b3997ddc57a5036571e2781c5c90eb3d3d95d3d4e1b4d1->enter($__internal_182aa17bbd6c4f0b15b3997ddc57a5036571e2781c5c90eb3d3d95d3d4e1b4d1_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:searchVuelos.html.twig"));

        // line 1
        echo "
<form name=\"searchVuelos\" id=\"search-vuelos\" action =\"";
        // line 2
        echo $this->env->getExtension('routing')->getPath("viento_sur_app_app_homepage_send_flights");
        echo "\" method=\"POST\">
    <ul class=\"trip-type-container\" id=\"contenedorTipoViaje\">
        <li>
            <input type=\"radio\" id=\"roundTripType\" name=\"tripType\" value=\"1\"  checked=\"checked\"/>
            <label for=\"roundTripType\" id=\"lbl-round\">Ida y vuelta</label>
        </li>
        <li>
            <input type=\"radio\" id=\"oneWayTripType\" name=\"tripType\"  value=\"2\"  />
            <label for=\"oneWayTripType\" id=\"lbl-oneway\">Sólo ida</label>
        </li>
        <li>
            <input type=\"radio\" id=\"multipleDestinationsTripType\" name=\"tripType\" value=\"3\"  />
            <label for=\"multipleDestinationsTripType\" id=\"lbl-multiple\">Múltiples destinos</label>
        </li>
    </ul>

    <div class=\"contenedorCiudades\" id=\"contenedorCiudades\">


        <div id=\"originContainer\" class=\"city-container\">\t<!--Origin city input-->
            <label id=\"origenLabel\" for=\"origen\">Origen</label>
            <input id=\"origen\" name=\"origin\" type=\"text\" class=\"city-input required\" data-provide=\"typeahead\" placeholder=\"Ingrese una ciudad de Origen\" 
                   value=\"\"/>
            <input type=\"hidden\" id=\"origenID\" style=\"display:none\" name=\"originId\" class=\"required\" value=\"\"/>
        </div>
        <div id=\"destinationContainer\" class=\"city-container\">\t<!--Destination city input-->
            <label id=\"destinoLabel\" for=\"destino\">Destino</label>
            <input id=\"destino\" name= \"destination\" type=\"text\" class=\"city-input required\" data-provide=\"typeahead\" placeholder=\"Ingrese una ciudad de Destino\" 
                   value=\"\"/>
            <input type=\"hidden\" id =\"destinoID\" style=\"display:none\" name=\"destinationId\" class=\"required\" value=\"\">
        </div>
        <div class=\"clearBox\"></div>\t\t \t
    </div>

    <div class=\"flightDates-container\" id=\"contenedorFechas\">
        <!-- dateCalendar.ftl -->

        <input id=\"flightsMinDays\" type=\"hidden\" class=\"ignore\" value=\"1\" />

        <div class=\"date-container\" id=\"fromDateInputContainer\">\t<!--Departure date input-->
            <label id=\"fromDateLabel\" for=\"fromCalendar\">Partida</label><br />
            <input type=\"text\" placeholder=\"dd-mm-aaaa\" class=\"input-date required\" id=\"fromCalendar\" name=\"fromDate\" >

            <span class=\"mainSprite calendar\"></span>
        </div>
        <p class=\"date-separator\">&nbsp;</p>
        <div class=\"date-container\" id=\"toDateInputContainer\">\t<!--Arrival date input-->
            <label id=\"toDateLabel\" for=\"toCalendar\">Regreso</label><br />
            <input type=\"text\" placeholder=\"dd-mm-aaaa\" class=\"input-date required\" id=\"toCalendar\" name=\"toDate\">

            <span class=\"mainSprite calendar\"></span>
        </div>
        <div class=\"clearBox\"></div>\t\t \t
    </div>
    <div id=\"passengers\">
        <!-- passengers.ftl -->
        <div class=\"passengers-container\">
            <label id=\"adultSelectLabel\" for=\"adultsSelect\">Adultos</label>
            <select class=\"selectNum\" id=\"adultsSelect\" name=\"adultsSelect\">
                <option value=1>1</option>
                <option value=2>2</option>
                <option value=3>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
                <option value=6>6</option>
                <option value=7>7</option>
                <option value=8>8</option>
            </select>
        </div>
        <div class=\"passengers-container\">
            <label id=\"childrenSelectLabel\" for=\"childrenSelect\">Menores</label>       
            <select class=\"selectNum\" id=\"childrenSelect\" name=\"childrenSelect\" onChange=\"mostrarInputsChildren(false)\"><br />
                <option value=0>0</option>
                <option value=1>1</option>
                <option value=2>2</option>
                <option value=3>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
                <option value=6>6</option>
                <option value=7>7</option>
            </select>
        </div>

        <div class=\"passengers-container\">
            <label id=\"childrenSelectLabel\" for=\"infantsSelect\">infantes</label>       
            <select class=\"selectNum\" id=\"infantsSelect\" name=\"infantsSelect\" onChange=\"mostrarInputsChildren(false)\"><br />
                <option value=0>0</option>
                <option value=1>1</option>
                <option value=2>2</option>
                <option value=3>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
                <option value=6>6</option>
                <option value=7>7</option>
            </select>
        </div>  \t

        <!-- advancedOptions.ftl -->
    </div>
        <div class=\"mod-searchbutton\">
            <button type=\"submit\">Buscar</button>
            ";
        // line 106
        echo "        </div>\t\t
</form>";
        
        $__internal_182aa17bbd6c4f0b15b3997ddc57a5036571e2781c5c90eb3d3d95d3d4e1b4d1->leave($__internal_182aa17bbd6c4f0b15b3997ddc57a5036571e2781c5c90eb3d3d95d3d4e1b4d1_prof);

    }

    public function getTemplateName()
    {
        return "VientoSurAppAppBundle:Hotel:searchVuelos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  129 => 106,  25 => 2,  22 => 1,);
    }
}
