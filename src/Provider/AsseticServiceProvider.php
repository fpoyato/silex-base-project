<?php
/**
 * User: fpoyato
 * Date: 3/07/13
 */

namespace Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Assetic\Asset\AssetCollection;
use Assetic\Factory\AssetFactory;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\LessFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;
use Assetic\AssetWriter;
use Assetic\AssetManager;

class AsseticServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     */
    function register(Application $app)
    {
        // Define las carpetas de cada tipo de asset si no se están definidas
        if (!isset ($app['assetic.assets_types_folders'])) {
            $app['assetic.assets_types_folders'] = array(
                'css' => '/css/',
                'js' => '/js/',
                'img' => '/img/'
            );
        }

        $app['assetic.filters'] = array(
            'less' => new LessFilter(),
            'lessphp' => new LessphpFilter()
        );

        // Define la ruta al directorio publico de assets si no está definida
        if (!isset ($app['assetic.asset_writer.path'])) {
            $app['assetic.asset_writer.path'] = __DIR__ . '/../../web';
        }

        // Copia los assets al directorio público
        $app['assetic.write_assets'] = function () use ($app) {

            // Borra todos los assets del directorio público
            foreach ($app['assetic.assets_types_folders'] as $type => $folder) {
                if ($assetDir = @opendir($app['assetic.asset_writer.path'] . $folder)) {
                    while ($asset = readdir($assetDir)) {
                        if ('.' !== $asset && '..' !== $asset) {
                            unlink($app['assetic.asset_writer.path'] . $folder . $asset);
                        }
                    }
                    rmdir($app['assetic.asset_writer.path'] . $folder);
                }
            }

            $aM = new AssetManager();
            $aW = new AssetWriter($app['assetic.asset_writer.path']);

            // Copia los assets al directorio público
            foreach ($app['file_assets'] as $asset) {

                isset($asset['filter'])
                    ? $filter = array($app['assetic.filters'][$asset['filter']])
                    : $filter = array();

                $fA = new FileAsset($asset['asset_path'], $filter);
                $fA->setTargetPath($asset['type'] . '/' . $asset['target']);

                $aM->set(key($asset), $fA);
                $aW->writeManagerAssets($aM);
            }
        };
    }

    function boot(Application $app)
    {

    }
}
