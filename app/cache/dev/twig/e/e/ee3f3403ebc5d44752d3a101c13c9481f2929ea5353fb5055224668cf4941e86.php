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
        $__internal_ed60d1adee67450e8fe3cbd85c0848688b24e02a171a65f156b9c6a88fe9de44 = $this->env->getExtension("native_profiler");
        $__internal_ed60d1adee67450e8fe3cbd85c0848688b24e02a171a65f156b9c6a88fe9de44->enter($__internal_ed60d1adee67450e8fe3cbd85c0848688b24e02a171a65f156b9c6a88fe9de44_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_ed60d1adee67450e8fe3cbd85c0848688b24e02a171a65f156b9c6a88fe9de44->leave($__internal_ed60d1adee67450e8fe3cbd85c0848688b24e02a171a65f156b9c6a88fe9de44_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_2041603f1ef13ca545ae07bbdd168e6421d0029a01b3a8711500265dbf204b9b = $this->env->getExtension("native_profiler");
        $__internal_2041603f1ef13ca545ae07bbdd168e6421d0029a01b3a8711500265dbf204b9b->enter($__internal_2041603f1ef13ca545ae07bbdd168e6421d0029a01b3a8711500265dbf204b9b_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('request')->generateAbsoluteUrl($this->env->getExtension('asset')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_2041603f1ef13ca545ae07bbdd168e6421d0029a01b3a8711500265dbf204b9b->leave($__internal_2041603f1ef13ca545ae07bbdd168e6421d0029a01b3a8711500265dbf204b9b_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_c9e8c01835ad80ea26f3b28c399e0d7e497f34f5c6dd3550e9efa31dee145a8c = $this->env->getExtension("native_profiler");
        $__internal_c9e8c01835ad80ea26f3b28c399e0d7e497f34f5c6dd3550e9efa31dee145a8c->enter($__internal_c9e8c01835ad80ea26f3b28c399e0d7e497f34f5c6dd3550e9efa31dee145a8c_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_c9e8c01835ad80ea26f3b28c399e0d7e497f34f5c6dd3550e9efa31dee145a8c->leave($__internal_c9e8c01835ad80ea26f3b28c399e0d7e497f34f5c6dd3550e9efa31dee145a8c_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_27963f2b754893294b98f34938d28795aa9253d6c794ee83a969e3d469f2f624 = $this->env->getExtension("native_profiler");
        $__internal_27963f2b754893294b98f34938d28795aa9253d6c794ee83a969e3d469f2f624->enter($__internal_27963f2b754893294b98f34938d28795aa9253d6c794ee83a969e3d469f2f624_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("TwigBundle:Exception:exception.html.twig", "TwigBundle:Exception:exception_full.html.twig", 12)->display($context);
        
        $__internal_27963f2b754893294b98f34938d28795aa9253d6c794ee83a969e3d469f2f624->leave($__internal_27963f2b754893294b98f34938d28795aa9253d6c794ee83a969e3d469f2f624_prof);

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
