<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('unice_sil_syllabus_moodle_importer');

        $rootNode
            ->children()
                ->scalarNode('em')
                    ->defaultValue('apogee')
                ->end()
                ->arrayNode('course')
                    ->children()
                        ->arrayNode('types')
                            ->scalarPrototype()->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
