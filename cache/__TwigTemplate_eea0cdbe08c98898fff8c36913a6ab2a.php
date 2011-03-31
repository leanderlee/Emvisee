<?php

/* common/stylesheet.tml */
class __TwigTemplate_eea0cdbe08c98898fff8c36913a6ab2a extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        // line 5
        echo "

";
        // line 7
        echo "
";
        // line 9
        echo "
";
        // line 11
        echo "
";
    }

    // line 7
    public function getsrc($file = null)
    {
        $context = array(
            "file" => $file,
        );

        echo "<link href=\"static/css/";
        echo (isset($context['file']) ? $context['file'] : null);
        echo ".css\" rel=\"stylesheet\" type=\"text/css\" />";
    }

    // line 9
    public function getie($file = null)
    {
        $context = array(
            "file" => $file,
        );

        echo "<!--[if IE]><link href=\"static/css/";
        echo (isset($context['file']) ? $context['file'] : null);
        echo ".css\" rel=\"stylesheet\" type=\"text/css\" /><![endif]-->";
    }

    // line 11
    public function getui()
    {
        $context = array(
        );

        echo $this->getAttribute($this, "src", array("jqueryui", ), "method");
    }

    public function getTemplateName()
    {
        return "common/stylesheet.tml";
    }
}
