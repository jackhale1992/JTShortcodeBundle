<?php

namespace JT\Bundle\ShortcodeBundle\Helper;

use Symfony\Component\Templating\Helper\HelperInterface;

/**
 *
 *
 * @author Michel Weimerskirch
 */
class ShortcodeHelper implements HelperInterface
{

    private static $shortcodes = array();

    public static function addShortcodeType($alias, $shortcode)
    {
        self::$shortcodes[$alias] = $shortcode;
    }

    private function getShortcodeNamesRegex()
    {
        $shortcode_names = array_keys(self::$shortcodes);
        $shortcode_names_regex = join('|', array_map('preg_quote', $shortcode_names));

        return $shortcode_names_regex;
    }

    public function doShortcodes($content)
    {
        $shortcode_names_regex = $this->getShortcodeNamesRegex();
        $content = preg_replace_callback("/\[($shortcode_names_regex)( [^\]]*)?\](?:(.+?)?\[\/\\1\])?/", array($this, 'replaceShortcode'), $content);

        return $content;
    }

    public function replaceShortcode($code)
    {
        $alias = $code[1];
        $atts = (isset($code[2])) ? $code[2] : "";
        $options = self::shortcode_parse_atts($atts);
        return self::$shortcodes[$alias]->parse($options);
    }

    public function get_shortcode_atts_regex() {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
    }

    public function shortcode_parse_atts($text) {
        $atts = array();
        $pattern = self::get_shortcode_atts_regex();
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]) && strlen($m[8]))
                    $atts[] = stripcslashes($m[8]);
                elseif (isset($m[9]))
                    $atts[] = stripcslashes($m[9]);
            }

            // Reject any unclosed HTML elements
            foreach( $atts as &$value ) {
                if ( false !== strpos( $value, '<' ) ) {
                    if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    /**
     * {@inheritDoc}
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * {@inheritDoc}
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'shortcode';
    }

}
