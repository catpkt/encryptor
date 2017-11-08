<?php

namespace CatPKT\Encryptor;

////////////////////////////////////////////////////////////////

interface IEncryptor
{

	/**
	 * Method encrypt
	 *
	 * @abstract
	 *
	 * @access public
	 *
	 * @param  mixed $data
	 *
	 * @return string
	 */
	function encrypt( $data ):string;

	/**
	 * Method decrypt
	 *
	 * @abstract
	 *
	 * @access public
	 *
	 * @param  string $payload
	 *
	 * @return mixed
	 */
	function decrypt( string$payload );

}
