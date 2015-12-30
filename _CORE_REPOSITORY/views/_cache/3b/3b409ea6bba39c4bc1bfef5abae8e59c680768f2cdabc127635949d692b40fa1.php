<?php

/* dashboards/event.twig */
class __TwigTemplate_377e330e06754734405e2325901d278b615e9ad07552283ccf704b411d9b2a22 extends Twig_Template
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
        // line 1
        echo "<ol class=\"\">
    ";
        // line 2
        if ((twig_length_filter($this->env, (isset($context["sortedEvents"]) ? $context["sortedEvents"] : null)) == 0)) {
            // line 3
            echo "        <h4>Нет вкс</h4>
    ";
        } else {
            // line 5
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["sortedEvents"]) ? $context["sortedEvents"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["vks"]) {
                // line 6
                echo "            <li>
                ";
                // line 7
                if ($this->getAttribute($context["vks"], "fromCa", array())) {
                    // line 8
                    echo "                    <a target=\"_blank\" href=\"";
                    echo twig_escape_filter($this->env, (isset($context["coreHttpAddress"]) ? $context["coreHttpAddress"] : null), "html", null, true);
                    echo "?route=vks/show/";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "\">
            <span class=\"label label-info label-as-badge\" data-type='ca-was' data-id='";
                    // line 9
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "'>
                 #";
                    // line 10
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "
            </span>
                    </a>
                ";
                } else {
                    // line 14
                    echo "                    <a target=\"_blank\" href=\"";
                    echo twig_escape_filter($this->env, (isset($context["nodeHttpAddress"]) ? $context["nodeHttpAddress"] : null), "html", null, true);
                    echo "?route=vks/show/";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "\">
            <span class=\"label label-success label-as-badge show-as-modal\" data-type='local' data-id='";
                    // line 15
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "'>
                 #";
                    // line 16
                    echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "id", array()), "html", null, true);
                    echo "
            </span>
                    </a>
                ";
                }
                // line 20
                echo "                &nbsp;";
                echo $this->getAttribute($context["vks"], "titleCustom", array());
                echo "
                <br>
                <span style=\"margin: 4px; display: block;\">";
                // line 22
                echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "start_time", array()), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["vks"], "end_time", array()), "html", null, true);
                echo "</span>

                ";
                // line 24
                if (($this->getAttribute($context["vks"], "fromCa", array()) == false)) {
                    // line 25
                    echo "                    ";
                    if (twig_length_filter($this->env, $this->getAttribute($context["vks"], "connection_codes", array()))) {
                        // line 26
                        echo "                        ";
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["vks"], "connection_codes", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
                            // line 27
                            echo "                            <p>
                                <span class=\"connection-code-highlighter-compact\">
                                   ";
                            // line 29
                            echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "value", array()), "html", null, true);
                            echo "
                                    ";
                            // line 30
                            if (twig_length_filter($this->env, $this->getAttribute($context["code"], "tip", array()))) {
                                // line 31
                                echo "                                        <sup>";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "tip", array()), "html", null, true);
                                echo "</sup>
                                    ";
                            }
                            // line 33
                            echo "                                    </span>
                                ";
                            // line 34
                            if ($this->getAttribute($context["vks"], "flag", array())) {
                                // line 35
                                echo "                                    &nbsp<span class=\"label label-as-badge label-danger\">flag</span>
                                ";
                            }
                            // line 37
                            echo "                            </p>
                        ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 39
                        echo "                    ";
                    } else {
                        // line 40
                        echo "                        <p>
                            <span class=\"connection-code-highlighter-compact\">Код подключения не выдан</span>
                            ";
                        // line 42
                        if ($this->getAttribute($context["vks"], "flag", array())) {
                            // line 43
                            echo "                                <span class=\"label label-as-badge label-danger\">flag</span>
                            ";
                        }
                        // line 45
                        echo "                        </p>
                    ";
                    }
                    // line 47
                    echo "
                ";
                } else {
                    // line 49
                    echo "                    <p>
                        ";
                    // line 51
                    echo "                       <span class=\"connection-code-highlighter-compact\"> ЦА: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["vks"], "connection_code", array()), "value", array()), "html", null, true);
                    echo "</span>
                        ";
                    // line 52
                    if ($this->getAttribute($context["vks"], "flag", array())) {
                        // line 53
                        echo "                            <span class=\"label label-as-badge label-danger\">flag</span>
                        ";
                    }
                    // line 55
                    echo "                    </p>
                ";
                }
                // line 57
                echo "
            </li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vks'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 60
            echo "    ";
        }
        // line 61
        echo "</ol>";
    }

    public function getTemplateName()
    {
        return "dashboards/event.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  179 => 61,  176 => 60,  168 => 57,  164 => 55,  160 => 53,  158 => 52,  153 => 51,  150 => 49,  146 => 47,  142 => 45,  138 => 43,  136 => 42,  132 => 40,  129 => 39,  122 => 37,  118 => 35,  116 => 34,  113 => 33,  107 => 31,  105 => 30,  101 => 29,  97 => 27,  92 => 26,  89 => 25,  87 => 24,  80 => 22,  74 => 20,  67 => 16,  63 => 15,  56 => 14,  49 => 10,  45 => 9,  38 => 8,  36 => 7,  33 => 6,  28 => 5,  24 => 3,  22 => 2,  19 => 1,);
    }
}
/* <ol class="">*/
/*     {% if sortedEvents|length == 0 %}*/
/*         <h4>Нет вкс</h4>*/
/*     {% else %}*/
/*         {% for vks in sortedEvents %}*/
/*             <li>*/
/*                 {% if vks.fromCa %}*/
/*                     <a target="_blank" href="{{ coreHttpAddress }}?route=vks/show/{{ vks.id }}">*/
/*             <span class="label label-info label-as-badge" data-type='ca-was' data-id='{{ vks.id }}'>*/
/*                  #{{ vks.id }}*/
/*             </span>*/
/*                     </a>*/
/*                 {% else %}*/
/*                     <a target="_blank" href="{{ nodeHttpAddress }}?route=vks/show/{{ vks.id }}">*/
/*             <span class="label label-success label-as-badge show-as-modal" data-type='local' data-id='{{ vks.id }}'>*/
/*                  #{{ vks.id }}*/
/*             </span>*/
/*                     </a>*/
/*                 {% endif %}*/
/*                 &nbsp;{{ vks.titleCustom|raw }}*/
/*                 <br>*/
/*                 <span style="margin: 4px; display: block;">{{ vks.start_time }} - {{ vks.end_time }}</span>*/
/* */
/*                 {% if vks.fromCa == false %}*/
/*                     {% if vks.connection_codes|length %}*/
/*                         {% for code in vks.connection_codes %}*/
/*                             <p>*/
/*                                 <span class="connection-code-highlighter-compact">*/
/*                                    {{ code.value }}*/
/*                                     {% if code.tip|length %}*/
/*                                         <sup>{{ code.tip }}</sup>*/
/*                                     {% endif %}*/
/*                                     </span>*/
/*                                 {% if vks.flag %}*/
/*                                     &nbsp<span class="label label-as-badge label-danger">flag</span>*/
/*                                 {% endif %}*/
/*                             </p>*/
/*                         {% endfor %}*/
/*                     {% else %}*/
/*                         <p>*/
/*                             <span class="connection-code-highlighter-compact">Код подключения не выдан</span>*/
/*                             {% if vks.flag %}*/
/*                                 <span class="label label-as-badge label-danger">flag</span>*/
/*                             {% endif %}*/
/*                         </p>*/
/*                     {% endif %}*/
/* */
/*                 {% else %}*/
/*                     <p>*/
/*                         {#{{ vks }}#}*/
/*                        <span class="connection-code-highlighter-compact"> ЦА: {{ vks.connection_code.value }}</span>*/
/*                         {% if vks.flag %}*/
/*                             <span class="label label-as-badge label-danger">flag</span>*/
/*                         {% endif %}*/
/*                     </p>*/
/*                 {% endif %}*/
/* */
/*             </li>*/
/*         {% endfor %}*/
/*     {% endif %}*/
/* </ol>*/
