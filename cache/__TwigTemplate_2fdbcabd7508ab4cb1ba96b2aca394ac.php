<?php

/* tests/all.tml */
class __TwigTemplate_2fdbcabd7508ab4cb1ba96b2aca394ac extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'styles' => array($this, 'block_styles'),
            'scripts' => array($this, 'block_scripts'),
            'page' => array($this, 'block_page'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("default.tml");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_styles($context, array $blocks = array())
    {
        echo "<style type=\"text/css\">
div.title { font: 3em/2em black; padding: 5px 30px; }
div.info { font: 1.4em/1.6em black; padding: 4px 50px; }
a.test { font: 2em/2em black; margin: 0; padding: 0 35px; }
div.passed { background: #9EF7B5; }
div.failed { background: #F5CECE; }
</style>
";
    }

    // line 13
    public function block_scripts($context, array $blocks = array())
    {
        echo "<script type=\"text/javascript\">
\$(function () {
    \$('.module').each(function () {
        if (\$(this).find('.failed').size() > 0) {
            \$(this).addClass('failed');
        }
        else {
            \$(this).addClass('passed');
        }
    });
});
</script>
";
    }

    // line 28
    public function block_page($context, array $blocks = array())
    {
        echo "<div class=\"title\">Unit Tests</div>
<div class=\"info\">";
        // line 30
        echo (isset($context['total_tests']) ? $context['total_tests'] : null);
        echo " tests.</div>
";
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_iterator_to_array((isset($context['tests']) ? $context['tests'] : null));
        $countable = is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable);
        $length = $countable ? count($context['_seq']) : null;
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if ($countable) {
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context['_key'] => $context['test']) {
            echo "<a href=\"tests/";
            // line 32
            echo (isset($context['test']) ? $context['test'] : null);
            echo "/\" class=\"test\">";
            echo (isset($context['test']) ? $context['test'] : null);
            echo "</a>
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if ($countable) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['test'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "tests/all.tml";
    }
}
