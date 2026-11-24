<?php
require_once __DIR__ . "/../dependencies/Enumerial/src/Enumerial.php";
require_once __DIR__ . "/../src/Consolor.php";

use Consolor\Consolor;

// 1. Direct usage (most common):
echo Consolor::RED()->apply( 'Something went wrong!' ) . PHP_EOL;
echo Consolor::ORANGE()->apply( 'Careful...' ) . PHP_EOL;

// 2. Passing a color into a function (this is where $color would come from):
function printLog( string $message, ?Consolor $color = null ): void {
	$color ??= Consolor::DEFAULT();

	$prefix = match ( $color ) {
		Consolor::RED()    => '[ERROR] ',
		Consolor::YELLOW() => '[WARN]  ',
		Consolor::GREEN()  => '[OK]    ',
		default            => '[INFO]  ',
	};

	echo $color->apply( $prefix . $message ) . PHP_EOL;
}

printLog( 'Database connected', Consolor::GREEN() );
printLog( 'Disk space low', Consolor::YELLOW() );
printLog( 'Connection failed', Consolor::RED() );
printLog( 'Plain message using default color' );

// 3. Dynamically picking a color by string name (e.g., from user config or CLI flags):
$userChoice = 'PURPLE';
echo Consolor::$userChoice()->apply( 'Custom user-selected color' ) . PHP_EOL;
