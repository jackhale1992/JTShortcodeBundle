<?php

namespace JT\Bundle\ShortcodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use JT\Bundle\ShortcodeBundle\DependencyInjection\Compiler\ShortcodePass;

/**
 * 
 *
 * @author JT
 */
class JTShortcodeBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ShortcodePass());
    }

}
