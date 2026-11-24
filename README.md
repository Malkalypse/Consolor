# Consolor

A lightweight ANSI terminal color utility built on [`Enumerial`](https://github.com/your-repo/enumerial).

Instead of hardcoding color cases in PHP, `Consolor` loads color definitions from a JSON file—making it easy to add custom colors or swap palettes without modifying source code.

## Requirements

- PHP 8.1+
- `Enumerial\Enumerial`

## Dependencies

This project uses [Deppend](https://github.com/Malkalypse/Deppend) to manage its dependencies (`deps.txt`). *(On Windows, run these commands in **Git Bash** or **WSL**.)*

### Using an Installed Copy of Deppend

- **Download this project and its dependencies** into `./dependencies/`:
  ```bash
  deppend Consolor
  ```
- **Or, if you already cloned this repository**, run `deppend` from the folder containing `deps.txt`:
  ```bash
  deppend
  ```

### Without Installing Deppend (Run Directly From GitHub)

- **Download this project and its dependencies** into `./dependencies/`:
  ```bash
  curl -fsSL https://raw.githubusercontent.com/Malkalypse/Deppend/main/deppend.sh | bash -s -- Consolor
  ```
- **Or, if you already cloned this repository**, run from the folder containing `deps.txt`:
  ```bash
  curl -fsSL https://raw.githubusercontent.com/Malkalypse/Deppend/main/deppend.sh | bash
  ```

## Basic Usage

```php
<?php
use Consolor\Consolor;

// Wrap text in a color and automatically reset afterward
echo Consolor::RED()->apply( 'Error: something went wrong' ) . PHP_EOL;
echo Consolor::ORANGE()->apply( 'Warning: check your config' ) . PHP_EOL;

// Access raw case details
$green = Consolor::GREEN();
echo $green->name;  // "GREEN"
echo $green->value; // "\033[32m"

// Strict equality and match expressions work just like native enums
$label = match ( $color ) {
	Consolor::RED()    => '[ERR]',
	Consolor::YELLOW() => '[WRN]',
	default            => '[INF]',
};

// Enumerial lookup helpers
Consolor::cases();                // All defined Consolor instances
Consolor::tryFromName( 'CYAN' );  // Consolor::CYAN() or null
Consolor::from( "\033[31m" );     // Consolor::RED() (throws ValueError if invalid)
Consolor::tryFrom( 'invalid' );   // null
```

## Configuring `colors.json`

By default, `Consolor` looks for `colors.json` in the same directory as `Consolor.php`.

`Consolor::load()` automatically normalizes values by prepending `\033[` and appending `m` when omitted, so all of the following formats are valid in JSON:

```json
{
	"GRAY": "90",
	"RED": "31m",
	"ORANGE": "38;5;208",
	"YELLOW": "38;5;226",
	"GREEN": "32",
	"BLUE": "34",
	"PURPLE": "35",
	"WHITE": "1;37",
	"CYAN": "96",
	"DEFAULT": "\u001b[0m"
}
```

> **Note on raw escape codes in JSON:** JSON does not support octal escapes like `\033`. If you write a full ANSI sequence in JSON, use `\u001b[` (without curly braces), or simply omit it and provide the numeric code (e.g., `"31"` or `"38;5;208"`).

### Reset Behavior (`DEFAULT`)

When `apply()` finishes wrapping text, it appends the `DEFAULT` entry from your JSON file to reset terminal formatting. If `DEFAULT` is omitted from the JSON file, `Consolor` safely falls back to `\033[0m`.

## Using a Custom Color Palette

You can override the default `colors.json` per instance by passing a path to the constructor—useful for user-defined themes or multiple palettes side-by-side:

```php
$theme = new Consolor( __DIR__ . '/theme-colors.json' );

echo $theme->PRIMARY()->apply( 'Uses theme-colors.json' ) . PHP_EOL;
echo Consolor::RED()->apply( 'Static calls still use colors.json' ) . PHP_EOL;
```

## API Reference

| Method / Property | Description |
| :--- | :--- |
| `__construct(?string $sourceFile = null)` | Loads a custom JSON file, or falls back to `colors.json`. |
| `apply(string $text): string` | Wraps `$text` in this color's ANSI sequence followed by `DEFAULT` (or `\033[0m`). |
| `$color->name` | `readonly string` key name (e.g., `"ORANGE"`). |
| `$color->value` | `readonly string` normalized ANSI escape sequence (e.g., `"\033[38;5;208m"`). |
| `cases()` | Returns all defined `Consolor` instances for the active file. |
| `from(string $value)` | Looks up a color by ANSI sequence; throws `ValueError` if not found. |
| `tryFrom(string $value)` | Looks up a color by ANSI sequence; returns `null` if not found. |
| `tryFromName(string $name)` | Looks up a color by key name; returns `null` if not found. |
| `<COLOR_NAME>()` | Returns the cached `Consolor` instance for `<COLOR_NAME>`; throws `ValueError` if undefined. |

## License

This library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).