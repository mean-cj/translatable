<?php

namespace AbbyLynn\Translatable\Services;

class LangFiles {

  /**
   * Default Language
   * @var string
   */
  private $lang;

  /**
   * @var string
   */
  private $file = 'auth';

  /**
   * Constructor Method
   */
  public function __construct() {
    $this->lang = config('app.locale');
  }

  /**
   * Set the language to be used
   * @param string $lang
   */
  public function setLanguage($lang) {
    $this->lang = $lang;
    return $this;
  }

  /**
   * Set the file to be used
   * @param string $file
   */
  public function setFile($file) {
    $this->file = $file;
    return $this;
  }

  /**
   * get the content of a language file as an array sorted ascending
   * @param   String    $lang
   * @param   String    $file
   * @return  Array/Bool
   */
  public function getFileContent() {

    $filepath = $this->getFilePath();

    if ( is_file($filepath) ) {
      $wordsArray = include $filepath;
      asort($wordsArray);
      return $wordsArray;
    }

    return false;
  }

  /**
   * rewrite the file with the modified texts
   * @param Array     $postArray
   * @param String    $lang
   * @param String    $file
   * @return  Integer
   */
  public function setFileContent($postArray) {

    $postArray = $this->prepareContent($postArray);

    $return = (int)file_put_contents( $this->getFilePath(), print_r("<?php \n\n return ".$this->var_export54($postArray).";", true));

    return $return;
  }

  /**
   * get the language files that can be edited, to ignore a file add it in the config/translatable file to ignore key
   * @param   String    $lang
   * @param   String    $activeFile
   * @return  Array
   */
  public function getlangFiles() {
    $fileList = [];

    foreach (scandir($this->getLangPath(), SCANDIR_SORT_DESCENDING) as $file) {
      $fileName = str_replace('.php', '', $file);

      if (!in_array($fileName, array_merge(['.', '..'], config('translatable.ignore')))) {
        $fileList[] = [
          'name' => ucfirst(str_replace('_', ' ', $fileName)),
          'url' => route("languages/view", ['lang' => $this->lang, 'file' => $fileName]),
          'active' => $fileName == $this->file,
        ];
      }
    }

    return json_decode(json_encode($fileList, false));
  }

  /**
   * Get path of specific file within language folder
   * @return string
   */
  private function getFilePath(){
    return base_path("resources/lang/{$this->lang}/{$this->file}.php");
  }

  /**
   * Get path of langauge folder
   * @return string
   */
  private function getLangPath(){
    return base_path("resources/lang/{$this->lang}/");
  }

  /**
   * create the array that will be saved in the file
   * @param  Array    $postArray
   * @return Array
   */
  private function prepareContent($postArray) {
    $returnArray = [];
    /**
     * function used to concatenate two arrays key by key
     * @param   String    $item1
     * @param   String    $item2
     * @return  String
     */
    function combine($item1, $item2) {
      return $item1.$item2;
    }
    unset($postArray['_token']);
    foreach ($postArray as $key => $item) {
      $keys = explode('__', $key);
      if (is_array($item)) {
        if (isset($item['before'])) {
          $value = $this->sanitize(implode('|', array_map(function($item1, $item2) {return $item1.$item2;}, str_replace('|', '&#124;', $item['before']), str_replace('|', '&#124;',$item['after']))));
        } else {
          $value = $this->sanitize(implode('|', str_replace('|', '&#124;', $item['after'])));
        }
      } else {
        $value = $this->sanitize(str_replace('|', '&#124;',$item));
      }
      $this->setArrayValue($returnArray, $keys, $value);
    }
    return $returnArray;
  }

  /**
   * var_export54() gets structured information about the given variable. It is similar to var_dump() with one exception: the returned representation is valid PHP code.
   * Following functionality defined by var_export(), var_export54() uses PHP5.4 square bracket array syntax
   * @param  mixed $var
   * @param  string $indent
   * @return mixed
   */
  private function var_export54($var, $indent="") {
      switch (gettype($var)) {
          case "string":
              return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
          case "array":
              $indexed = array_keys($var) === range(0, count($var) - 1);
              $r = [];
              foreach ($var as $key => $value) {
                  $r[] = "$indent    "
                       . ($indexed ? "" : $this->var_export54($key) . " => ")
                       . $this->var_export54($value, "$indent    ");
              }
              return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
          case "boolean":
              return $var ? "TRUE" : "FALSE";
          default:
              return var_export($var, TRUE);
      }
  }

  /**
   * add filters to the values inserted by the user
   * @param   String    $str
   * @return  String
   */
  private function sanitize($str) {
    return e(trim($str));
  }

  /**
   * set a value in a multidimensional array when knowing the keys
   * @param   Array     $data
   * @param   Array     $keys
   * @param   String    $value
   * @return  Array
   */
  private function setArrayValue(&$data, $keys, $value) {
      foreach ($keys as $key) {
          $data = &$data[$key];
      }
      return $data = $value;
  }

}