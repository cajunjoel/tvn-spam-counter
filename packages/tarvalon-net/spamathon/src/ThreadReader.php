<?php

namespace TarValonNet;

use GuzzleHttp\Client;
use Masterminds\HTML5;
use GuzzleHttp\Cookie\CookieJar;
use Emoji;

class ThreadReader {

  private $cookiejar = '';
  private $config = '';

  public function __construct(CookieJar $cookiejar, array $config) {
    $this->cookiejar = $cookiejar;
    $this->config = $config;

    if (!file_exists($this->config['cache_path'])) {
      mkdir($this->config['cache_path']);
    } else {
      if (!is_dir($this->config['cache_path'])) {
        die("Cache cannot be created. File already exists.\n");
      }
    }
  }

  /* Make a name for a cache file
   * 
   * Given a string, return another string suitable
   * for use as a filename.
   */
  private function get_cachefile_name(string $text) {
    $text = preg_replace("/[^a-zA-Z0-9]+/", "_", $text);
    $text = preg_replace("/_+/", "_", $text);
    return $this->config['cache_path'].'/'.$text;
  }

  /* Write to the cache
   * 
   * Given some data and a filename save the data
   * to the file. 
   */
  private function write_cache(string $filename, mixed $data) {
    if ($this->config['verbose']) { fwrite(STDERR, "  Writing to cache.\n"); }
    file_put_contents($filename, serialize($data));
  }

  /* Check if we have a valid cache file
   * 
   */
  private function test_cache(string $filename) {
    if (file_exists($filename)) {
      if (time()-filemtime($filename) > $this->config['cache_lifetime']) {
        if ($this->config['verbose']) { fwrite(STDERR, "  Cache file too old. Deleting.\n"); }
        unlink($filename);
      }
    }
    return file_exists($filename);
  }

  /* Read from the cache
   * 
   * Given a filename get the data there. 
   * Returns null if the file doesn't exist.
   */
  private function read_cache(string $filename) {
    if (!file_exists($filename)) {
      if ($this->config['verbose']) { fwrite(STDERR, "  Can't read from cache. File not found. (This is ok).\n;"); }
      return null;
    }
    return unserialize(file_get_contents($filename));
  }

  /* Search the Children
   * 
   * Mostly used for searching for emoji (class = "smilie")
   */
  private function search_children($el, $tag, $class) {

		if ($el->nodeName == $tag) {
			if (strpos($el->getAttribute('class'), 'smilie') >= 0) {
				return $el->getAttribute('alt');
			}
		}

		if ($el->hasChildNodes()) {
			foreach ($el->childNodes as $child) {
				$res = $this->search_children($child, $tag, $class); 
				if ($res) {
					return $res;
				}
			}
		}
		return null;
	}

  /* Parse a post
   * 
   * Read the HTML and pull out the bits of text
   * we need. Returns a strucure for the post (an
   * associative array)
   */
  private function parse_page(string $text, string $url, int $page) {    
    $posts = array();

    if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Parsing Page...\n"); }

    // Parse the page
    $html5 = new HTML5();
    $dom = $html5->loadHTML($text);
    $els = $dom->getElementsByTagName('article');

    foreach ($els as $el) {
      $class = $el->getAttribute('class');
      // Get the message
      if (preg_match('/message message--post/', $class)) {
        if ($this->config['debug'] >= 1) { fwrite(STDERR, "DEBUG: Working on new post ".$el->getAttribute('data-content')."\n"); }
        
        // Find the post content itself
        $els2 = $el->getElementsByTagName('article');        
        // Remove the quoted content 
        foreach ($els2 as $el2) {
          $bq = $el2->getElementsByTagName('blockquote');
          while (count($bq) > 0) {
            if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Found ".count($bq). "quotations.\n"); }
            $children = $els2->item(0)->childNodes;
            foreach($children as $c) {
              if ($c->nodeName == 'div') {
                if ($c->getAttribute('class') == 'bbWrapper') {
                  // see if there is a blockquote here
                  $children2 = $c->childNodes;
                  foreach($children2 as $c2) {
                    if ($c2->nodeName == 'blockquote') {
                      if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Removed a quotation\n"); }
                      $c->removeChild($c2);
                    }
                  }
                }
              }
            }
            $bq = $el2->getElementsByTagName('blockquote');
          }
        }
        // Save the post
        
        if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: got post content: ===> ".trim($els2->item(0)->textContent)." <===\n"); }
        if ($el->getAttribute('data-author') != 'Faire Ground Fairy') {
          $posts[] = array(
            'author'         => $el->getAttribute('data-author'),
            'url'            => $url.'#'.$el->getAttribute('data-content'),
            'page'           => $page,
            'page_url'       => $url,
            'post'           => preg_replace('/\n/', '', trim($els2->item(0)->textContent)),
            'valid'          => '?',
            'emoji'          => Emoji\detect_emoji($els2->item(0)->textContent),
            'has_smilies'    => $this->search_children($els2->item(0), 'img', 'smilie'),
            'invalid_reason' => ''
          );
        }
      }
    }

    return $posts;
  }

  /* Gather all of the posts in a thread.
   * 
   * Call each page of the thread in succession,
   * gettng the content of the page.
   */
  public function read_thread(string $base_url, bool $override = false) {
    if (!$base_url) {
      return null;
    }

    $cache_file = $this->get_cachefile_name($base_url);

    // Default to reading from the cache
    // but kill the cache file if it's too old.
    $this->test_cache($cache_file);
    if (!$override &&  file_exists($cache_file)) {
      if ($this->config['verbose']) { fwrite(STDERR, "  Using cached posts.\n"); }
      return $this->read_cache($cache_file);
    }
    if ($this->config['verbose']) { fwrite(STDERR, "  Getting posts from the site.\n"); }

    // Loop through the pages 
    $page = 0;
    $client = new Client();
    $posts = [];
    
    while (true) {
      $page++;
      print chr(13)."Page $page...";
      if ($page > 1) {
        $url = $base_url."/page-".$page;
      } else {
        $url = $base_url;
      }
      if ($this->config['verbose']) { fwrite(STDERR, "  Reading page $page (Found ".count($posts)." posts).\n"); }
      sleep($this->config['sleep']);

      // Get the page
      if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Getting URL: $url\n"); }
      $response = $client->request('GET', $url, ['cookies' => $this->cookiejar, 'allow_redirects' => false]);
      $code = $response->getStatusCode();
      if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Response Code: $code\n"); }
      if ($code == 301) {
        $url = $response->getHeaderLine('Location');
        if ($this->config['debug'] >= 1) { fwrite(STDERR, "  DEBUG: Got redirect to $url \n"); }
        $response = $client->request('GET', $url, ['cookies' => $this->cookiejar, 'allow_redirects' => false]);
        $code = $response->getStatusCode();
      }
      if ($code == 303) {
        break;
      }
      if ($code == 200) {
        $body = $response->getBody();
        $posts = array_merge($posts, $this->parse_page((string)$body, $url, $page));
      }
    }
    print "\n";
    if ($this->config['verbose']) { fwrite(STDERR, "  Saving ".count($posts)." posts to cache file: $cache_file\n"); }
    $this->write_cache($cache_file, $posts);
    return $posts;
  }
}