<?php

/* default.tml */
class __TwigTemplate_d67992acbf2a1968544832a727ce0769 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'language' => array($this, 'block_language'),
            'title' => array($this, 'block_title'),
            'base' => array($this, 'block_base'),
            'styles' => array($this, 'block_styles'),
            'scripts' => array($this, 'block_scripts'),
            'head' => array($this, 'block_head'),
            'page' => array($this, 'block_page'),
        );
    }

    public function display(array $context, array $blocks = array())
    {
        // line 1
        $context['js'] = $this->env->loadTemplate("common/javascript.tml", true);
        // line 2
        $context['css'] = $this->env->loadTemplate("common/stylesheet.tml", true);
        echo "<!doctype html>
<html lang=\"";
        // line 4
        $this->getBlock('language', $context, $blocks);
        echo "\">
<head>
    
        <!--
        
        Good developers always view source.
        
        -->
    
    <title>";
        // line 13
        $this->getBlock('title', $context, $blocks);
        echo "</title>
    
    <meta charset=\"utf-8\" />
    
    <base href=\"";
        // line 17
        $this->getBlock('base', $context, $blocks);
        echo "\" />
    
    ";
        // line 19
        echo $this->getAttribute((isset($context['css']) ? $context['css'] : null), "src", array("reset", ), "method");
        echo "
";
        // line 20
        $this->getBlock('styles', $context, $blocks);
        // line 21
        echo "    
    ";
        // line 23
        echo $this->getAttribute((isset($context['js']) ? $context['js'] : null), "jquery", array(), "method");
        echo "
";
        // line 24
        $this->getBlock('scripts', $context, $blocks);
        // line 25
        echo "    
    ";
        // line 27
        $this->getBlock('head', $context, $blocks);
        // line 28
        echo "
</head>
<body>
";
        // line 32
        $this->getBlock('page', $context, $blocks);
        // line 33
        echo "</body>
</html>
";
    }

    // line 4
    public function block_language($context, array $blocks = array())
    {
        echo twig_default_filter((isset($context['language']) ? $context['language'] : null), "en");
    }

    // line 13
    public function block_title($context, array $blocks = array())
    {
        echo twig_default_filter((isset($context['title']) ? $context['title'] : null), "");
    }

    // line 17
    public function block_base($context, array $blocks = array())
    {
        echo twig_default_filter((isset($context['base']) ? $context['base'] : null), "/");
    }

    // line 20
    public function block_styles($context, array $blocks = array())
    {
    }

    // line 24
    public function block_scripts($context, array $blocks = array())
    {
    }

    // line 27
    public function block_head($context, array $blocks = array())
    {
        echo "    ";
    }

    // line 32
    public function block_page($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "default.tml";
    }
}
