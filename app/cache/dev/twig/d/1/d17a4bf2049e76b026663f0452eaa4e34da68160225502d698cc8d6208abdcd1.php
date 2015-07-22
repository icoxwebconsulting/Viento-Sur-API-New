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
        $__internal_9d593a2909702e58c3cc11163e475b305f3cf31ab57a4ddaf0efd33493305d95 = $this->env->getExtension("native_profiler");
        $__internal_9d593a2909702e58c3cc11163e475b305f3cf31ab57a4ddaf0efd33493305d95->enter($__internal_9d593a2909702e58c3cc11163e475b305f3cf31ab57a4ddaf0efd33493305d95_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig"));

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
            <td>Nombre: </td>
            <td>Identificacion Hotel</td>
            <td> Habitaciones disponibles (paquetes) </td>
            <td>Precio:</td>
        </tr>
        <tr>
            <td>
                <a href=\"";
            // line 15
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("viento_sur_app_app_homepage_show_hotel_id", array("idHotel" => $this->getAttribute($context["item"], "id", array()), "restUrl" => (isset($context["restUrl"]) ? $context["restUrl"] : $this->getContext($context, "restUrl")))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "name", array()), "html", null, true);
            echo "</a> /
                Stars: ";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "stars", array()), "html", null, true);
            echo "
            </td>
            <td>
                ";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "hotel", array()), "hotel_type_id", array()), "html", null, true);
            echo "
            </td>
            <td>
                ";
            // line 22
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "roompacks", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["roompack"]) {
                // line 23
                echo "                    Plan de comidas:";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["roompack"], "meal_plan", array()), "id", array()), "html", null, true);
                echo "/
                    hab disp:  ";
                // line 24
                echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($context["roompack"], "rooms", array())), "html", null, true);
                echo "
                    ";
                // line 26
                echo "                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["roompack"], "price_detail", array()), "currency", array()), "html", null, true);
                echo "
                ";
                // line 28
                echo "                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['roompack'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "            </td>
            <td>
            ";
            // line 32
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "roompacks", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["price"]) {
                echo "<br>
                 Moneda:";
                // line 33
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "currency", array()), "html", null, true);
                echo "<br>
                 Sub Total:";
                // line 34
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "subtotal", array()), "html", null, true);
                echo "<br>
                 Impuestos:";
                // line 35
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "taxes", array()), "html", null, true);
                echo "<br>
                 Detalles de impuestos:
                 ";
                // line 37
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "taxes_detail", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["taxes_detail"]) {
                    // line 38
                    echo "                    codigo/ ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "code", array()), "html", null, true);
                    echo "
                    cantidad/";
                    // line 39
                    echo twig_escape_filter($this->env, $this->getAttribute($context["taxes_detail"], "amount", array()), "html", null, true);
                    echo "<br>
                 ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['taxes_detail'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 40
                echo "<br>
                 Cuota:";
                // line 41
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "fee", array()), "html", null, true);
                echo "<br>
                 Descuentos:";
                // line 42
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "discounts", array()), "html", null, true);
                echo "<br>
                 Total:";
                // line 43
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["price"], "price_detail", array()), "total", array()), "html", null, true);
                echo "<br>
                 ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['price'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
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
        // line 49
        echo "
";
        
        $__internal_9d593a2909702e58c3cc11163e475b305f3cf31ab57a4ddaf0efd33493305d95->leave($__internal_9d593a2909702e58c3cc11163e475b305f3cf31ab57a4ddaf0efd33493305d95_prof);

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
        return array (  178 => 49,  161 => 45,  153 => 43,  149 => 42,  145 => 41,  142 => 40,  134 => 39,  129 => 38,  125 => 37,  120 => 35,  116 => 34,  112 => 33,  105 => 32,  101 => 29,  95 => 28,  90 => 26,  86 => 24,  81 => 23,  77 => 22,  71 => 19,  65 => 16,  59 => 15,  46 => 5,  42 => 4,  39 => 3,  22 => 2,);
    }
}
