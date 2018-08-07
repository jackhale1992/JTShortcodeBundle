<?php

namespace JT\Bundle\ShortcodeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * 
 *
 * @author JT
 */
class ShortcodePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('templating.helper.shortcode');
        foreach ($container->findTaggedServiceIds('jt.shortcode') as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall('addShortcodeType', array($attributes['alias'], new Reference($id)));
            }
        }
    }

}
