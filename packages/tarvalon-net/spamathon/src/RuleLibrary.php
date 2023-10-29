<?php

namespace TarValonNet;

class RuleLibrary {
  private $config = array();
  private $author_groups = array();

  public function __construct(mixed $config) {
    $this->config = $config;   
  }

  /* PROCESS A RULE
     
     Given a post and a rule, determine if the
     rule exists and apply it to the post.
     
     Note: a rule is allowed to modify the post.
   */ 
  public function apply_rule(array &$post, array $rule) {

    // If it ain't there, don't call it!
    if (method_exists($this,'rule_'.$rule['name'])) {
      $ret = null;
      // Eval is ugly, but it works, I guess?
      $code = "\$ret = \$this->rule_".$rule['name']."(\$post, \$rule);";
      //print "CODE IS: $code\n";
      eval($code);
      if ($ret) {
        $post['valid'] = true;
      } else {
        $post['valid'] = false;
        $post['invalid_reason'] = $rule['message'];
      }
      // We always return true or false;
      return $ret;
    } else {
      // Null means some sort of error.
      return null;
    }
  }

  /* SEARCH AND REPLACE
     This just changes the string, it does not check for validity
   */
  private function rule_replace(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    $post[$field] = preg_replace($rule['pattern'], $rule['replace'], $post[$field]);
    return true;
  }

  /* CAPTURE
     Special code to add a new field to the post
   */
  private function rule_capture(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    $post[$rule['savefield']] = '';
    $matches = array();
    if (preg_match($rule['pattern'], $post[$field], $matches)) {
      if ($matches[$rule['backref']]) {
        if ($this->config['debug'] >= 2) { 
          fwrite(STDERR, "    DEBUG: Captured --->".trim($matches[$rule['backref']])."<--- to field ".$rule['savefield']."\n"); 
        }
        $post[$rule['savefield']] = trim($matches[$rule['backref']]);
      }
    }
    return true;
  }
  
  /* MINIMUM WORD COUNT
     Special code to check for string length
   */
  private function rule_min_word_count(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    // Converting to ASCII is brutal to CJK but let's hope for the best.
    $str = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $post[$field] );
    $word_count = str_word_count($str);

    if ($this->config['debug'] >= 2) { 
      fwrite(STDERR, "    DEBUG: Rule ".$rule['name'].": count is $word_count on on field \"$field\" containing \"".$str."\".\n"); 
    }
    return ($word_count >= $rule['count']);
  }

  /* NO DOUBLE POSTING
     Special code to check for double posting 
     The caller is responsible for adding previous author to the rule 
   */
  private function rule_no_double_post(array &$post, $rule) {
    return ($post['author'] != $rule['last_author']);
  }

  /* REQUIRED GROUP OR TEXT
     Generalized code to check that a piece exists
   */
  private function rule_required_part(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    $matches = array();
    if (!preg_match($rule['pattern'], $post[$field], $matches)) {
      return false;
    } else {
      if (!$matches[$rule['backref']]) {
        return false;
      }
    }
    return true;
  }

  /* REQUIRED STRING
     Generalized code to check the entire post

   */
  private function rule_required_string(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    $data = trim($post[$field]);
    if (!preg_match($rule['pattern'], $data)) {
      if ($this->config['debug'] >= 2) { 
        fwrite(STDERR, "    DEBUG: NO MATCH: Pattern ".$rule['pattern']." data --->".$data."<---\n"); 
      }
      return false;
    } else {
      if ($this->config['debug'] >= 2) { 
        fwrite(STDERR, "    DEBUG: MATCH: Pattern ".$rule['pattern']." data --->".$data."<---\n"); 
      }
    }
    return true;
  }
  
  /* STRING NOT ALLOWED
     Generalized code to check for something not allowed
   */
  private function rule_not_allowed_string(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    if (preg_match($rule['pattern'], $post[$field])) {
      return false;
    }
    return true;
  }

  /* GET THE EMOJI
     Save the emoji, or at least try to
   */
  private function rule_get_emoji(array &$post, $rule) {
    $emoji = [];
    if ($post['has_smilies']) {
      $emoji[] = $post['has_smilies'];
    }
    if (count($post['emoji'])) {
      foreach ($post['emoji'] as $e) {
        $emoji[] = ':'.$e['short_name'].':';
      }
    }
    $post['emoji_used'] = implode(' ', $emoji);
    return true;
  }

  /* HAVE AT LEAST ONE EMOJI
     Check if we have saved emoji     
     This depends on rule_get_emoji() above and will call it if necessary.
   */
  private function rule_emoji_required(array &$post, $rule) {
    if (!$post['emoji_used']) {
      $this->rule_get_emoji($post, $rule);
    }
    $post['emoji_count'] = count($post['emoji_used']);
    if ($post['emoji_count'] < 1) {
      return false;
    }
    return true;
  }

  /* HAVE AT LEAST # EMOJI
     Check how many emoji we have  
     This depends on rule_get_emoji() above and will call it if necessary.
   */
  private function rule_emoji_count(array &$post, $rule) {
    if (!$post['emoji_used']) {
      $this->rule_get_emoji($post, $rule);
    }
    $post['emoji_count'] = count($post['emoji_used']);
    if ($post['emoji_count'] < $rule['count']) {
      return false;
    }
    return true;
  }

  /* I CAN HAZ GOAT EMOJI ??
     Specialized code to determine if there is a goat 
     emoji present. I think there is only one?
     This depends on rule_get_emoji() above and will call it if necessary.
   */
  private function rule_has_goat_emoji(array &$post, $rule) {
    if (!$post['emoji_used']) {
      $this->rule_get_emoji($post, $rule);
    }
    foreach ($post['emoji_used'] as $e) {
      if (strtolower($e) == ':goat:') {
        return true;
      }
    }
    return false;
  }

  /* PRAISE BE TO GOATS
     Super special code to see if we are praising goats.
     currently for testing returns true if the word "Goat" appears
  */
  private function rule_praise_be_to_goats(array &$post, $rule) {
    $text = preg_replace("/[^a-zA-Z?0-9 ]/", ' ', $post['text']);
    $valid = false;
    if (preg_match('/goat/i', $post['text'])) {
      $valid = true;
    }
    require_once('AffirmativeWords.php');
    foreach ($affirmative_words as $w) {
      $patt = "/$w/";
      if (preg_match($patt, $text)) {
        $valid = true;
        break;
      }
    }
    return $valid;
  }

  /* SAVE AUTHOR AND GROUP
     Specialized code to save group + author to a new field
   */
  private function rule_capture_author_and_group(array &$post, $rule) {
    $post[$rule['savefield']] = '['.$post['group'].'] '.$post['author'];
    return true;
  }

  /* INNER WORD CAPITALIZED
     Specialized code to ensure an interior word is in all caps
       
     This operates by splitting the string into an array of words
     Then it drops the first and last word
     Then it removes all except letters and numbers from each word
     Then it checks to see if any of the remaining words are ALL CAPS 
   */
  private function rule_middle_word_caps(array &$post, $rule) {
    $field = (isset($rule['field']) ? $rule['field'] : 'post');
    $text = $post[$rule['field']];
    if ($this->config['debug'] >= 2) { fwrite(STDERR, "    DEBUG: Word Splitting data --->".$text."<---\n");  }
    // Split into words
    $words = preg_split('/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/', $text, -1, PREG_SPLIT_NO_EMPTY);
    // Extract only the word words, not emoji and such
    $good_words = [];
    foreach ($words as $w) {
      if (preg_match("/[A-Za-z']+/", $w)) {
        $good_words[] = $w;
      }
    }	
    // Drop the first and last
    $x = array_pop($good_words);
    $y = array_shift($good_words);
    if ($this->config['debug'] >= 2) { fwrite(STDERR, "    DEBUG: Removed '$x' and '$y'\n");  }				
    // Find one with all caps
    $found = false;
    foreach ($good_words	 as $w) {
      // Remove all except letters and numbers. This could be a bug. :)
      $w = preg_replace("/[^A-Za-z0-9]+/",'',$w);
      if (preg_match("/^[A-Z]+$/", $w)) {
        $found = true;
      }
    }	
    if (!$found) {
      return false;
    }
    return true;
  }

  /* CAN'T CHANGE GROUPS
     Special code to see track that someone continues posting to
     the same group during th event.
  */
  private function rule_ensure_same_group(array &$post, $rule) {
    if (!isset($this->author_groups[$post['author']])) {
      $this->author_groups[$post['author']] = $post['group'];
      return true;
    } else {
      if (strtolower($this->author_groups[$post['author']]) != strtolower($post['group'])) {
        return false;
      } else {
        return true;
      }
    }
  }
}