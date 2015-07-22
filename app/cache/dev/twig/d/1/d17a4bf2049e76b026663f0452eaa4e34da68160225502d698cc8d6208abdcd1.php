<?php

/* VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig */
class __TwigTemplate_d17a4bf2049e76b026663f0452eaa4e34da68160225502d698cc8d6208abdcd1 extends Twig_Template
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
        $__internal_16105ee0f8fd90ff0de2c093661a07eb7f8916d535f47fdc8e1cf3165c0a659b = $this->env->getExtension("native_profiler");
        $__internal_16105ee0f8fd90ff0de2c093661a07eb7f8916d535f47fdc8e1cf3165c0a659b->enter($__internal_16105ee0f8fd90ff0de2c093661a07eb7f8916d535f47fdc8e1cf3165c0a659b_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig"));

        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["items"]) ? $context["items"] : $this->getContext($context, "items")));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 3
            echo "
    Hotel : ";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
            echo "
    ";
            // line 5
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "id", array()), "html", null, true);
            echo "
    <table border=\"1\">
        <tr>
            <td></td>
            <td>Nombre: </td>
            <td>Identificacion Hotel</td>
            <td> Habitaciones disponibles (paquetes) </td>
            <td>Precio:</td>
        </tr>
        <tr>
            <td>
                ";
            // line 16
            echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("VientoSurAppAppBundle:Hotel:detailsHotelListForId", array("idHotel" => $this->getAttribute($context["item"], "id", array()))));
            echo "
            </td>
            <td>
                <a href=\"";
            // line 19
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("viento_sur_app_app_homepage_show_hotel_id", array("idHotel" => $this->getAttribute($context["item"], "id", array()), "restUrl" => (isset($context["restUrl"]) ? $context["restUrl"] : $this->getContext($context, "restUrl")))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "name", array()), "html", null, true);
            echo "</a> /
                Stars: ";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "stars", array()), "html", null, true);
            echo "
            </td>
            <td>
                ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "hotel_type_id", array()), "html", null, true);
            echo "
            </td>
            <td>
                ";
            // line 26
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "roompacks", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["roompack"]) {
                // line 27
                echo "                    Plan de comidas:";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["roompack"], "meal_plan", array()), "id", array()), "html", null, true);
                echo "/
                    hab disp:  ";
                // line 28
                echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($context["roompack"], "rooms", array())), "html", null, true);
                echo "
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['roompack'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 30
            echo "            </td>
            <td>
            ";
            // line 33
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "roompacks", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["price"]) {
                echo "<br>
                 Moneda:";
                // line 34
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "currency", array()), "html", null, true);
                echo "<br>
                 Sub Total:";
                // line 35
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "subtotal", array()), "html", null, true);
                echo "<br>
                 Impuestos:";
                // line 36
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "taxes", array()), "html", null, true);
                echo "<br>
                 Detalles de impuestos:<br>
                 ";
                // line 38
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "taxes_detail", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["taxes_detail"]) {
                    // line 39
                    echo "                    codigo: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "code", array()), "html", null, true);
                    echo "
                    cantidad: ";
                    // line 40
                    echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "amount", array()), "html", null, true);
                    echo "<br>
                 ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['taxes_detail'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 41
                echo "<br>
                 Cuota:";
                // line 42
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "fee", array()), "html", null, true);
                echo "<br>
                 Descuentos:";
                // line 43
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "discounts", array()), "html", null, true);
                echo "<br>
                 Total:";
                // line 44
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "total", array()), "html", null, true);
                echo "<br>
                 ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['price'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "            </td>
        </tr>
    </table><br>
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "
";
        
        $__internal_16105ee0f8fd90ff0de2c093661a07eb7f8916d535f47fdc8e1cf3165c0a659b->leave($__internal_16105ee0f8fd90ff0de2c093661a07eb7f8916d535f47fdc8e1cf3165c0a659b_prof);

    }

    public function getTemplateName()
    {
        return "VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  178 => 50,  161 => 46,  153 => 44,  149 => 43,  145 => 42,  142 => 41,  134 => 40,  129 => 39,  125 => 38,  120 => 36,  116 => 35,  112 => 34,  105 => 33,  101 => 30,  93 => 28,  88 => 27,  84 => 26,  78 => 23,  72 => 20,  66 => 19,  60 => 16,  46 => 5,  42 => 4,  39 => 3,  22 => 2,);
    }
}
