<?php
namespace app\components;
use yii;
use yii\helpers\Html;
use yii\helpers\Url;
class Helper {
	
	/**
	 * Checks whether the haystack starts with the given needle 
	 * @param string $haystack
	 * @param sting $needle
	 * @return boolean TRUE if haystack starts with needle, FALSE otherwise
	 */
	public static function starts($haystack, $needle) {
	    return $needle === "" || strpos($haystack, $needle) === 0;
	}
	
	public static function array_merge_recursive_simple() {

	    if (func_num_args() < 2) {
	        trigger_error(__FUNCTION__ .' needs two or more array arguments', E_USER_WARNING);
	        return;
	    }
	    $arrays = func_get_args();
	    $merged = array();
	    while ($arrays) {
	        $array = array_shift($arrays);
	        if (!is_array($array)) {
	            trigger_error(__FUNCTION__ .' encountered a non array argument', E_USER_WARNING);
	            return;
	        }
	        if (!$array)
	            continue;
	        foreach ($array as $key => $value)
	            if (is_string($key))
	                if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key]))
	                    $merged[$key] = MHelper::array_merge_recursive_simple($merged[$key], $value);
	                else
	                    $merged[$key] = $value;
	            else
	                $merged[] = $value;
	    }
	    return $merged;
	}
	
	/**
	 * Determines the Dutch VAT for the given sum 
	 * @param float $amount The sum
	 * @param boolean $inputIncludesVat Whether the input sum already includes vat.
	 * @param boolean $inclusive Whether or not to include the sum ex vat in the result. This option is ignored if the input already includes vat
	 * @return integer The result
	 */
	public static function vat($amount, $inputIncludesVat = true, $inclusive = false) {
		if (!$inputIncludesVat)
			return $amount * Yii::$app->params['vat']/100 + ($inclusive ? $amount : 0);
		else 
			return $amount - ($amount / (1 + Yii::$app->params['vat']/100));
	}
	
	/**
	 * Returns an absolute URL to the given route
	 * @param mixed $route The route as you would use with Url::to()
	 * @return string The absolute URL
	 */
	public static function absolute($route) {
		return Url::base(true).(self::starts(Url::to($route), '/') ? '' : '/').Url::to($route);
	}
	
	public static function truncate($text, $length = 150, $ending = '...', $exact = false, $considerHtml = false) {
		mb_internal_encoding("UTF-8");
		if ($considerHtml) {
			if (strlen ( preg_replace ( '/<.*?>/', '', $text ) ) <= $length) {
				return $text;
			}
			
			preg_match_all ( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
			
			$total_length = strlen ( $ending );
			$open_tags = array ();
			$truncate = '';
			
			foreach ( $lines as $line_matchings ) {
				if (! empty ( $line_matchings [1] )) {
					if (preg_match ( '/^<(s*.+?/s*|s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(s.+?)?)>$/is', $line_matchings [1] )) {
					} else if (preg_match ( '/^<s*/([^s]+?)s*>$/s', $line_matchings [1], $tag_matchings )) {
						$pos = array_search ( $tag_matchings [1], $open_tags );
						if ($pos !== false) {
							unset ( $open_tags [$pos] );
						}
					} else if (preg_match ( '/^<s*([^s>!]+).*?>$/s', $line_matchings [1], $tag_matchings )) {
						array_unshift ( $open_tags, strtolower ( $tag_matchings [1] ) );
					}
					$truncate .= $line_matchings [1];
				}
				$content_length = strlen ( preg_replace ( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings [2] ) );
				if ($total_length + $content_length > $length) {
					$left = $length - $total_length;
					$entities_length = 0;
					if (preg_match_all ( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings [2], $entities, PREG_OFFSET_CAPTURE )) {
						foreach ( $entities [0] as $entity ) {
							if ($entity [1] + 1 - $entities_length <= $left) {
								$left --;
								$entities_length += strlen ( $entity [0] );
							} else {
								break;
							}
						}
					}
					$truncate .= mb_substr ( $line_matchings [2], 0, $left + $entities_length );
					break;
				} else {
					$truncate .= $line_matchings [2];
					$total_length += $content_length;
				}
				if ($total_length >= $length) {
					break;
				}
			}
		} else {
			if (strlen ( $text ) <= $length) {
				return $text;
			} else {
				$truncate = mb_substr ( $text, 0, $length - strlen ( $ending ) );
			}
		}
		
		if (! $exact) {
			$spacepos = strrpos ( $truncate, ' ' );
			if (isset ( $spacepos )) {
				$truncate = mb_substr ( $truncate, 0, $spacepos );
			}
		}
		$truncate .= $ending;
		if ($considerHtml) {
			foreach ( $open_tags as $tag ) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
}
