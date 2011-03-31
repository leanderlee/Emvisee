<?php

/* common/javascript.tml */
class __TwigTemplate_67775347864cd021ded8f98d1269637e extends Twig_Template
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

        echo "<script type=\"text/javascript\" src=\"static/js/";
        echo (isset($context['file']) ? $context['file'] : null);
        echo ".js\"></script>";
    }

    // line 9
    public function getcorners()
    {
        $context = array(
        );

        echo $this->getAttribute($this, "src", array("jquery.corner.min", ), "method");
    }

    // line 11
    public function getui()
    {
        $context = array(
        );

        echo $this->getAttribute($this, "src", array("jqueryui.min", ), "method");
    }

    // line 13
    public function getjquery()
    {
        $context = array(
        );

        echo $this->getAttribute($this, "src", array("jquery.min", ), "method");
    }

    public function getTemplateName()
    {
        return "common/javascript.tml";
    }
}
