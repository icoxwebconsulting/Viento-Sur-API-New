<?php

/* VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig */
class __TwigTemplate_a0c6dfdcc8d0fe33acf293a9c5c9a04eb510606c26bd52832c1e815159b95b92 extends Twig_Template
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
        $__internal_80dd13a9d0f5f53539c03650d0da12af32c1d397926b6e1fde8fa69b4890e6b9 = $this->env->getExtension("native_profiler");
        $__internal_80dd13a9d0f5f53539c03650d0da12af32c1d397926b6e1fde8fa69b4890e6b9->enter($__internal_80dd13a9d0f5f53539c03650d0da12af32c1d397926b6e1fde8fa69b4890e6b9_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig"));

        // line 1
        echo "Name: ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "name", array()), "html", null, true);
        echo " /
Estrallas: ";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "stars", array()), "html", null, true);
        echo "
<br>
<div>
<img src=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "pictures", array()), 0, array(), "array"), "url", array()), "html", null, true);
        echo "\" width=\"400\"  height=\"300\">
</div>
<br>

Información: ";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "information", array()), "es", array()), "html", null, true);
        echo "
<br>    
";
        // line 13
        echo "<br>
Nuemero de habitaciones: ";
        // line 14
        echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "rooms", array())), "html", null, true);
        echo "
<br>
";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "rooms", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["room"]) {
            // line 17
            echo "    ";
            // line 18
            echo "    Referencia:";
            echo twig_escape_filter($this->env, $this->getAttribute($context["room"], "reference", array()), "html", null, true);
            echo "
    Nombre :";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($context["room"], "name", array()), "html", null, true);
            echo "
   ";
            // line 21
            echo "    Codigo: ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["room"], "room_code", array()), "html", null, true);
            echo "
   ";
            // line 25
            echo "  
    <br>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['room'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "Servicios: <bR>
";
        // line 30
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "amenities", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["amenitie"]) {
            // line 31
            echo "    ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["amenitie"], "descriptions", array()), "es", array()), "html", null, true);
            echo "<br>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['amenitie'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "
Precio del paquete: <br>
Moneda:";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "currency", array()), "html", null, true);
        echo "<br>
impuestos :";
        // line 36
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "taxes", array()), "html", null, true);
        echo "<br>
detalle impuestos: <br>
";
        // line 38
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "taxes_detail", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["taxes_detail"]) {
            // line 39
            echo "    codigo/ ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "code", array()), "html", null, true);
            echo "
    cantidad/";
            // line 40
            echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "amount", array()), "html", null, true);
            echo "<br>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['taxes_detail'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "cuota(fee): ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "fee", array()), "html", null, true);
        echo "<br>
Descuentos : ";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "discounts", array()), "html", null, true);
        echo "<br>
subtotal :";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "subtotal", array()), "html", null, true);
        echo "<br>
total: ";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "price_detail", array()), "total", array()), "html", null, true);
        echo "<br>
Metodos de pago del Paquete: 
";
        // line 48
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["dispoHotel"]) ? $context["dispoHotel"] : $this->getContext($context, "dispoHotel")), "roompacks", array()), 0, array(), "array"), "payment_type", array()), "es", array()), "html", null, true);
        echo "
";
        
        $__internal_80dd13a9d0f5f53539c03650d0da12af32c1d397926b6e1fde8fa69b4890e6b9->leave($__internal_80dd13a9d0f5f53539c03650d0da12af32c1d397926b6e1fde8fa69b4890e6b9_prof);

    }

    public function getTemplateName()
    {
        return "VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 48,  140 => 46,  136 => 45,  132 => 44,  127 => 43,  119 => 40,  114 => 39,  110 => 38,  105 => 36,  101 => 35,  97 => 33,  88 => 31,  84 => 30,  81 => 29,  73 => 25,  68 => 21,  64 => 19,  59 => 18,  57 => 17,  53 => 16,  48 => 14,  45 => 13,  40 => 9,  33 => 5,  27 => 2,  22 => 1,);
    }
}
