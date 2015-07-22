<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_ee3f3403ebc5d44752d3a101c13c9481f2929ea5353fb5055224668cf4941e86 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("TwigBundle::layout.html.twig", "TwigBundle:Exception:exception_full.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_035c80ea0e4e37f0ab582761c74e80d3665128ecc93d6c0cf754d9081c6c5621 = $this->env->getExtension("native_profiler");
        $__internal_035c80ea0e4e37f0ab582761c74e80d3665128ecc93d6c0cf754d9081c6c5621->enter($__internal_035c80ea0e4e37f0ab582761c74e80d3665128ecc93d6c0cf754d9081c6c5621_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_035c80ea0e4e37f0ab582761c74e80d3665128ecc93d6c0cf754d9081c6c5621->leave($__internal_035c80ea0e4e37f0ab582761c74e80d3665128ecc93d6c0cf754d9081c6c5621_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_561812729309d97d84cb92bfb4102725cc275a68ba0240209ac978fe17df549b = $this->env->getExtension("native_profiler");
        $__internal_561812729309d97d84cb92bfb4102725cc275a68ba0240209ac978fe17df549b->enter($__internal_561812729309d97d84cb92bfb4102725cc275a68ba0240209ac978fe17df549b_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('request')->generateAbsoluteUrl($this->env->getExtension('asset')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_561812729309d97d84cb92bfb4102725cc275a68ba0240209ac978fe17df549b->leave($__internal_561812729309d97d84cb92bfb4102725cc275a68ba0240209ac978fe17df549b_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_2eeb98825500fb1f8f59507fa7bdf2458e72408a6cc1369ccad439b2143ea89a = $this->env->getExtension("native_profiler");
        $__internal_2eeb98825500fb1f8f59507fa7bdf2458e72408a6cc1369ccad439b2143ea89a->enter($__internal_2eeb98825500fb1f8f59507fa7bdf2458e72408a6cc1369ccad439b2143ea89a_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_2eeb98825500fb1f8f59507fa7bdf2458e72408a6cc1369ccad439b2143ea89a->leave($__internal_2eeb98825500fb1f8f59507fa7bdf2458e72408a6cc1369ccad439b2143ea89a_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_818cc1ecc9d6ffdf62d09771016c2e143b6ef772633bf868f625abcb2724d10e = $this->env->getExtension("native_profiler");
        $__internal_818cc1ecc9d6ffdf62d09771016c2e143b6ef772633bf868f625abcb2724d10e->enter($__internal_818cc1ecc9d6ffdf62d09771016c2e143b6ef772633bf868f625abcb2724d10e_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("TwigBundle:Exception:exception.html.twig", "TwigBundle:Exception:exception_full.html.twig", 12)->display($context);
        
        $__internal_818cc1ecc9d6ffdf62d09771016c2e143b6ef772633bf868f625abcb2724d10e->leave($__internal_818cc1ecc9d6ffdf62d09771016c2e143b6ef772633bf868f625abcb2724d10e_prof);

    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 12,  72 => 11,  58 => 8,  52 => 7,  42 => 4,  36 => 3,  11 => 1,);
    }
}
