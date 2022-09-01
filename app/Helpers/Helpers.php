<?php

if (!function_exists('dollarToReal')) {
	function dollarToReal($value, $places = 2)
	{
		return number_format($value, $places === null ? 2 : $places, ',', '.');
	}
}

if (!function_exists('realToDollar')) {
	function realToDollar($value)
	{
		return str_replace(['.', ','], ['', '.'], $value);
	}
}
