<?php

/* VientoSurAppAppBundle:Hotel:index.html.twig */
class __TwigTemplate_9197a874c401442f8fb2311e77fe8bcca12bfeb91b1558032528be6a7419ff2f extends Twig_Template
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
        $__internal_cdb563fc10b6c3c0597f0c0e97941419699ad14803e953f10f1c158358ef33f5 = $this->env->getExtension("native_profiler");
        $__internal_cdb563fc10b6c3c0597f0c0e97941419699ad14803e953f10f1c158358ef33f5->enter($__internal_cdb563fc10b6c3c0597f0c0e97941419699ad14803e953f10f1c158358ef33f5_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "VientoSurAppAppBundle:Hotel:index.html.twig"));

        // line 2
        echo "<!doctype html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
        <title>Demo Viento Sur</title>
        <link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css\">
        <link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css\">
        <script src=\"//code.jquery.com/jquery-1.10.2.js\"></script>
        <script src=\"//code.jquery.com/ui/1.11.4/jquery-ui.js\"></script>
        <script src=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('asset')->getAssetUrl("bundles/vientosurappapp/js/Hotel/hotel.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('asset')->getAssetUrl("bundles/vientosurappapp/js/AutoComplete/jquery.mockjax.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('asset')->getAssetUrl("bundles/vientosurappapp/js/AutoComplete/jquery.autocomplete.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        <script src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('asset')->getAssetUrl("bundles/fosjsrouting/js/router.js"), "html", null, true);
        echo "\"></script>
<script src=\"";
        // line 15
        echo $this->env->getExtension('routing')->getPath("fos_js_routing_js", array("callback" => "fos.Router.setData"));
        echo "\"></script>
";
        // line 17
        echo "        <script>
            \$(function () {
                \$(\"#tabs\").tabs();
            });
        </script>
    </head>
    <body>
        <div id=\"tabs\">
            <ul>
                <li><a href=\"#tabs-1\">Hoteles</a></li>
                <li><a href=\"#tabs-2\">Vuelos</a></li>
                <li><a href=\"#tabs-3\">Aenean lacinia</a></li>
            </ul>
            <div id=\"tabs-1\">
                ";
        // line 31
        $this->loadTemplate("VientoSurAppAppBundle:Hotel:searchHotels.html.twig", "VientoSurAppAppBundle:Hotel:index.html.twig", 31)->display($context);
        // line 32
        echo "            </div>
            <div id=\"tabs-2\">
                ";
        // line 34
        $this->loadTemplate("VientoSurAppAppBundle:Hotel:searchVuelos.html.twig", "VientoSurAppAppBundle:Hotel:index.html.twig", 34)->display($context);
        // line 35
        echo "            </div>
            <div id=\"tabs-3\">
                <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
                <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
            </div>
        </div>
    </body>
</html>";
        
        $__internal_cdb563fc10b6c3c0597f0c0e97941419699ad14803e953f10f1c158358ef33f5->leave($__internal_cdb563fc10b6c3c0597f0c0e97941419699ad14803e953f10f1c158358ef33f5_prof);

    }

    public function getTemplateName()
    {
        return "VientoSurAppAppBundle:Hotel:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 35,  75 => 34,  71 => 32,  69 => 31,  53 => 17,  49 => 15,  45 => 14,  41 => 13,  37 => 12,  33 => 11,  22 => 2,);
    }
}
