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
        $__internal_c5e72ec9aaf042a788937e6e5b480574a7dd49e86b7bdadb7ed319e6ee68d23c = $this->env->getExtension("native_profiler");
        $__internal_c5e72ec9aaf042a788937e6e5b480574a7dd49e86b7bdadb7ed319e6ee68d23c->enter($__internal_c5e72ec9aaf042a788937e6e5b480574a7dd49e86b7bdadb7ed319e6ee68d23c_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig"));

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
                echo "                  Plan de comidas:";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["roompack"], "meal_plan", array()), "id", array()), "html", null, true);
                echo "/
                  hab disp:  ";
                // line 24
                echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($context["roompack"], "rooms", array())), "html", null, true);
                echo "
                 ";
                // line 26
                echo "                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['roompack'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            echo "                </td>
                <td>";
            // line 28
            echo "</td>
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
        // line 32
        echo "
";
        
        $__internal_c5e72ec9aaf042a788937e6e5b480574a7dd49e86b7bdadb7ed319e6ee68d23c->leave($__internal_c5e72ec9aaf042a788937e6e5b480574a7dd49e86b7bdadb7ed319e6ee68d23c_prof);

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
        return array (  116 => 32,  99 => 28,  96 => 27,  90 => 26,  86 => 24,  81 => 23,  77 => 22,  71 => 19,  65 => 16,  59 => 15,  46 => 5,  42 => 4,  39 => 3,  22 => 2,);
    }
}
