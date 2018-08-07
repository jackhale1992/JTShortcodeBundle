<?php

namespace JT\Bundle\ShortcodeBundle\Twig\Extension;

use JT\Bundle\ShortcodeBundle\Helper\ShortcodeHelper;

/**
 * 
 *
 * @author Michel Weimerskirch
 */
class ShortcodeTwigExtension extends \Twig_Extension
{

    protected $helper;

    function __construct(ShortcodeHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('shortcodes', array($this, 'shortcodes')),
        );
    }

    public function shortcodes($content)
    {
        return $this->helper->doShortcodes($content);
    }

    public function getName()
    {
        return 'shortcodes';
    }

}
