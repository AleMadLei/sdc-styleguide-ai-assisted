<?php

namespace Drupal\shareable_sdc;

use Drupal\Core\Plugin\Discovery\DirectoryWithMetadataPluginDiscovery;
use Drupal\Core\Theme\ComponentPluginManager;

/**
 * Extended component plugin manager that supports global /components directory.
 *
 * This manager extends the core ComponentPluginManager to add support for
 * components located in a global /components directory, accessible via
 * the "components:" namespace in Twig templates.
 */
class ShareableComponentPluginManager extends ComponentPluginManager {

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery(): DirectoryWithMetadataPluginDiscovery {
    // Basically this is an exact copy of the parent method.
    if (!isset($this->discovery)) {
      $directories = $this->getScanDirectories();
      $this->discovery = new DirectoryWithMetadataPluginDiscovery($directories, 'component', $this->fileSystem);
    }
    return $this->discovery;
  }

  /**
   * Get the list of directories to scan.
   *
   * Because the parent class has this method as private instead of protected, we cannot override it. So we have to
   * create our own version (which is almost a copy of the parent class) with the extension for our custom code.
   *
   * @return string[]
   *   The directories.
   */
  protected function getScanDirectories(): array {
    // Get the original extension directories (modules and themes)
    $extension_directories = [
      ...$this->moduleHandler->getModuleDirectories(),
      ...$this->themeHandler->getThemeDirectories(),
      ...$this->getGlobalComponentDirectories(),
    ];

    return array_map(
      static fn(string $path) => rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'components',
      $extension_directories
    );
  }

  /**
   * Find all component directories within the global components path.
   *
   * @param string $global_components_path
   *   The path to the global components directory.
   *
   * @return array
   *   Array of component directory paths.
   */
  private function getGlobalComponentDirectories(string $global_components_path): array {
    $component_dirs = [];

    // Recursively search for *.component.yml files
    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($global_components_path, \RecursiveDirectoryIterator::SKIP_DOTS),
      \RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
      if ($file->isFile() && $file->getExtension() === 'yml') {
        $filename = $file->getFilename();
        // Check if this is a component.yml file (ends with .component.yml)
        if (str_ends_with($filename, '.component.yml')) {
          $component_dirs[] = $file->getPath();
        }
      }
    }

    return array_unique($component_dirs);
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    dump(parent::providerExists($provider) || $provider == 'components');
    return parent::providerExists($provider) || $provider == 'components';
  }
}
