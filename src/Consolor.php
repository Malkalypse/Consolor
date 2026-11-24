<?php
namespace Consolor;

use Enumerial\Enumerial;

class Consolor extends Enumerial {
	private const FALLBACK_RESET = "\033[0m"; // fallback reset code if DEFAULT is not defined

  /** Path to JSON file */
	protected static function sourceFile(): ?string {
		return __DIR__ . '/colors.json';
	}

  /** Load definitions from JSON file */
	protected static function load( string $sourceFile ): array {

    // Ensure ANSI escape codes are properly formatted
		return array_map(
			function( string|int $value ): string {
				$value = (string)$value; // ensure value is a string

        // Prepend "\033[" if not already present
				if ( ! str_starts_with( $value, "\033[" ) ) {
					$value = "\033[{$value}";
				}

        // Append "m" if not already present
				return str_ends_with( $value, 'm' )
          ? $value
          : "{$value}m";
			},

			parent::load( $sourceFile ) // get raw values
		);
	}

  /** Apply color to given string
   * @param string $text text to colorize
   * @return string colorized text
   */
	public function apply( string $text ): string {

    // Use DEFAULT color for reset if available, otherwise use fallback reset code
		$reset = $this->tryFromName( 'DEFAULT' )?->value ?? self::FALLBACK_RESET;

		return $this->value . $text . $reset; // apply color and reset
	}
}