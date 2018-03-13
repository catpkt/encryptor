<?php

namespace CatPKT\Encryptor;

////////////////////////////////////////////////////////////////

class Encryptor implements IEncryptor
{

	/**
	 * Var apiKey
	 *
	 * @access protected
	 *
	 * @var    string
	 */
	protected $apiKey;

	/**
	 * Var encryptMethod
	 *
	 * @access protected
	 *
	 * @var    string
	 */
	protected $encryptMethod= 'AES-256-CBC';

	/**
	 * Method __construct
	 *
	 * @access public
	 *
	 * @param  string $apiUri
	 * @param  string $apiKey
	 * @param  string $encryptMethod
	 */
	public function __construct( string$apiKey, string$encryptMethod='AES-256-CBC' )
	{
		$this->apiKey= $apiKey;
		$this->encryptMethod= $encryptMethod;
	}

	/**
	 * Method encrypt
	 *
	 * @access public
	 *
	 * @param mixed $data
	 *
	 * @return string
	 */
	public function encrypt( $data ):string
	{
		$iv= random_bytes(16);

		$value= openssl_encrypt( serialize($data), $this->encryptMethod, $this->apiKey, 0, $iv );

		if( $value===false )
		{
			throw new EncryptException('Could not encrypt the data.');
		}

		$iv= base64_encode($iv);

		$mac= hash_hmac( 'sha256', $iv.$value, $this->apiKey );

		return base64_encode(json_encode([ 'iv'=>$iv, 'value'=>$value, 'mac'=>$mac, ]));
	}

	/**
	 * Method decrypt
	 *
	 * @access public
	 *
	 * @param  string $payload
	 *
	 * @return mixed
	 */
	public function decrypt( string$payload )
	{
		try{
			$payload= json_decode( base64_decode( $payload ) );

			$this->validMac( $payload );

			$iv= base64_decode( $payload->iv );

			$decrypted= openssl_decrypt( $payload->value, $this->encryptMethod, $this->apiKey, 0, $iv );

			if( false===$decrypted )
			{
				throw new DecryptException('Decrypt failed.');
			}

			return unserialize( $decrypted );
		}
		catch( \Throwable$e )
		{
			throw new DecryptException('Could not decrypt the data');
		}
	}

	/**
	 * Method __invoke
	 *
	 * @access public
	 *
	 * @param  mixed $data
	 *
	 * @return string
	 */
	public function __invoke( $data ):string
	{
		return $this->encrypt( $data );
	}

	/**
	 * Method validMac
	 *
	 * @access private
	 *
	 * @param  \stdClass $payload
	 *
	 * @return void
	 */
	private function validMac( \stdClass$payload )
	{
		$bytes= random_bytes(16);

		if(!(
			hash_equals(
				hash_hmac( 'sha256', $payload->mac, $bytes, true )
				,
				hash_hmac( 'sha256', hash_hmac( 'sha256', $payload->iv.$payload->value, $this->apiKey ), $bytes, true )
			)
		)){
			throw new DecryptException('aoeu');
		}
	}

}
