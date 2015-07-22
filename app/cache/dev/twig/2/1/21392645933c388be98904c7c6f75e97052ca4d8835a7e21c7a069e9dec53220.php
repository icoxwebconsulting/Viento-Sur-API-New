<?php

/* VientoSurAppAppBundle:Hotel:detailsHotelListForId.html.twig */
class __TwigTemplate_21392645933c388be98904c7c6f75e97052ca4d8835a7e21c7a069e9dec53220 extends Twig_Template
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
        $__internal_f1a9b5c7283712bebbf087f7e52775d4f4c929ee897f9c542cfdc1ed0b48355a = $this->env->getExtension("native_profiler");
        $__internal_f1a9b5c7283712bebbf087f7e52775d4f4c929ee897f9c542cfdc1ed0b48355a->enter($__internal_f1a9b5c7283712bebbf087f7e52775d4f4c929ee897f9c542cfdc1ed0b48355a_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:detailsHotelListForId.html.twig"));

        // line 1
        echo "<div>
<img src=\"";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["hotelDetails"]) ? $context["hotelDetails"] : $this->getContext($context, "hotelDetails")), 0, array(), "array"), "pictures", array()), 0, array(), "array"), "url", array()), "html", null, true);
        echo "\" width=\"200\"  height=\"150\">
</div>
";
        
        $__internal_f1a9b5c7283712bebbf087f7e52775d4f4c929ee897f9c542cfdc1ed0b48355a->leave($__internal_f1a9b5c7283712bebbf087f7e52775d4f4c929ee897f9c542cfdc1ed0b48355a_prof);

    }

    public function getTemplateName()
    {
        return "VientoSurAppAppBundle:Hotel:detailsHotelListForId.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  25 => 2,  22 => 1,);
    }
}
