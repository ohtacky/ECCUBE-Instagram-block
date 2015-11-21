<?php

namespace Plugin\InstagramApi;

use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\Filesystem\Filesystem;

class PluginManager extends AbstractPluginManager
{

    public function install($config, $app)
    {
        $this->migrationSchema($app, __DIR__ . '/Migration', $config['code']);

        $file = new Filesystem();
        try {
            $file->copy($app['config']['root_dir']. '/app/Plugin/InstagramApi/instagram_api.twig', $app['config']['template_realdir']. '/Block/instagram_api.twig', true);
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    public function uninstall($config, $app)
    {
        unlink($app['config']['template_realdir']. '/Block/instagram_api.twig');

        $this->migrationSchema($app, __DIR__ . '/Migration', $config['code'], 0);
    }

    public function enable($config, $app)
    {
    }

    public function disable($config, $app)
    {
      $qb = $app['orm.em']->createQueryBuilder();
      $qb->select("c")
         ->from("Eccube\\Entity\\Block", "c")
         ->where("c.file_name = :file_name")
         ->setParameter("file_name", "instagram_api");
      $query = $qb->getQuery();
      $block = $query->getSingleResult();
      $blockId = $block->getId();

      $app['orm.em']->createQueryBuilder()
          ->delete("Eccube\\Entity\\BlockPosition", "d")
          ->where("d.block_id = :block_id")
          ->setParameter("block_id", $blockId)
          ->getQuery()
          ->execute();
    }

    public function update($config, $app)
    {
    }
}
