<?php

namespace CatPKT\PictureProvider\Encrypt;

////////////////////////////////////////////////////////////////

trait TWithEncryptor
{

	/**
	 * Var encryptor
	 *
	 * @access protected
	 *
	 * @var    Encryptor
	 */
	protected $encryptor;

	/**
	 * Method getEncryptor
	 *
	 * @access public
	 *
	 * @return Encryptor
	 */
	public function getEncryptor():Encryptor
	{
		return $this->encryptor??($this->createEncryptor());
	}

	/**
	 * Method createEncryptor
	 *
	 * @abstract
	 *
	 * @access protected
	 *
	 * @return Encryptor
	 */
	abstract protected function createEncryptor():Encryptor;

}
