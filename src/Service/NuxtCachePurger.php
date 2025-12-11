<?php

namespace Drupal\custom_nuxt_multi_cache_purge\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\path_alias\AliasManagerInterface;
use Psr\Log\LoggerInterface;

class NuxtCachePurger {
  
  protected ClientInterface $httpClient;
  protected AliasManagerInterface $aliasManager;
  protected LoggerInterface $logger;
  protected ConfigFactoryInterface $configFactory;

  public function __construct(ClientInterface $httpClient, AliasManagerInterface $aliasManager, LoggerInterface $logger, ConfigFactoryInterface $configFactory) {
    $this->httpClient = $httpClient;
    $this->aliasManager = $aliasManager;
    $this->logger = $logger;
    $this->configFactory = $configFactory;
  }

  public function purge(string $node_path) {
    $alias = $this->aliasManager->getAliasByPath($node_path);
    if (!$alias) {
      $this->logger->error('Nuxt cache purge failed: No alias found for ' . $node_path);
      return FALSE;
    }

    // Fetch values from module configuration.
    $config = $this->configFactory->get('custom_nuxt_multi_cache_purge.settings');
    $frontend_url = $config->get('frontend_url');
    $endpoint = $config->get('purge_endpoint');
    $authToken = $config->get('auth_token');

    // $endpoint = 'https://newtdv.vercel.app/api/nuxt-multi-cache/purge/tags';
    // $authToken = '4|$+FmL`#O!N|[q:bA,#TS=o/PHtM1p}ZE#6N_{5]J@M"W5A^PDM]r';

    // dump($frontend_url, $endpoint, $authToken, $alias, $frontend_url . $endpoint);

    try {
      if($endpoint === "/api/nuxt-multi-cache/purge/tags"){
        $response = $this->httpClient->post($frontend_url . $endpoint, [
          'headers' => [
            'x-nuxt-multi-cache-token' => $authToken,
          ],
          'json' => ["url:$alias"],
        ]);
      } else {
        $response = $this->httpClient->post($frontend_url . $endpoint, [
          'headers' => [
            'Authorization' => 'Basic ' . $authToken,
            'Content-Type' => 'application/json',
          ],
          'json' => ["tag:$alias"],
        ]);
      }

      if ($response->getStatusCode() === 200) {
        $this->logger->info('Nuxt cache successfully purged for {tag}: (' . 'url:' . $alias .')');
        return TRUE;
      }

      $this->logger->warning('Nuxt cache purge returned non-200 response: {code}', [
        'code' => $response->getStatusCode(),
      ]);
      return FALSE;
    } catch (RequestException $e) {
      $this->logger->error('Nuxt cache purge failed: {message}',
        ['message' => $e->getMessage()
      ]);
      return FALSE;
    }
  }
}
