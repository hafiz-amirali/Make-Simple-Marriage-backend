<?php


namespace App\Utils;

use Firebase\JWT\JWT;
use Tuupola\Base62;
use Slim\Http\UploadedFile;


class Utils
{
	public function formatDateTime($dateToConvert)
	{
		return 'formattedDate';
	}
	/**
	 * Encrypt a message
	 * 
	 * @param string $message - message to encrypt
	 * @param string $key - encryption key
	 * @return string
	 */
	public static function safeEncrypt($message, $key)
	{
		$nonce = random_bytes(
			SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
		);
		$message = base64_encode($message); //converts obj to str
		$cipher = base64_encode(
			$nonce .
				sodium_crypto_secretbox(
					$message,
					$nonce,
					$key
				)
		);
		sodium_memzero($message);
		sodium_memzero($key);
		return $cipher;
	}

	/**
	 * Decrypt a message
	 * 
	 * @param string $encrypted - message encrypted with safeEncrypt()
	 * @param string $key - encryption key
	 * @return string
	 */
	public static function safeDecrypt($encrypted, $key)
	{
		$decoded = base64_decode($encrypted);
		if ($decoded === false) {
			throw new \Exception('Scream bloody murder, the encoding failed');
		}
		if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
			throw new \Exception('Scream bloody murder, the message was truncated');
		}
		$nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
		$ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

		$plain = sodium_crypto_secretbox_open(
			$ciphertext,
			$nonce,
			$key
		);
		if ($plain === false) {
			throw new \Exception('the message was tampered with in transit');
		}
		sodium_memzero($ciphertext);
		sodium_memzero($key);
		return base64_decode($plain); //converts str to obj
	}
	public static function getJWTToken($data)
	{
		$now = new \DateTime();
		$future = new \DateTime("+20 days");
		// $future = new \DateTime("+1 minutes");
		$jti = (new Base62)->encode(random_bytes(16));

		// $key = sodium_bin2hex(sodium_crypto_secretbox_keygen()); // random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES); //


		$enc = Utils::safeEncrypt($data, sodium_hex2bin($_REQUEST['key'], '')); //generates random  encrypted string (Base64 related)



		// $dec = Utils::safeDecrypt($enc, sodium_hex2bin($_REQUEST['key'], '')); //decrypts encoded string generated via safeEncrypt function 
		// $dec = json_decode($dec, true); //only use when you encrypted an object instead of string

		$payload = [
			"iat" => $now->getTimeStamp(),
			"exp" => $future->getTimeStamp(),
			"jti" => $jti,
			"user" => $enc,
		];
		$token = JWT::encode($payload, $_REQUEST['key'], "HS256");
		return $token;
	}
	public static function moveUploadedFile(UploadedFile $uploadedFile)
	{
		$directory =  $_REQUEST['upload_directory_relative'];
		$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
		$directory = $directory . DIRECTORY_SEPARATOR . $extension;
		$absolutePath = $_REQUEST['upload_directory'] . $directory;
		if (!file_exists($absolutePath)) {
			mkdir($absolutePath, 0777, true);
		}
		$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
		$filename = sprintf('%s.%0.8s', $basename, $extension);
		$directory = $directory . DIRECTORY_SEPARATOR . $filename;
		$uploadedFile->moveTo($absolutePath . DIRECTORY_SEPARATOR . $filename);
		return 'src' . $directory;
	}
	public static function moveBase64File($data)
	{
		if (preg_match('/^data:image\/(\w+);base64,/', $data, $extension)) {
			$data = substr($data, strpos($data, ',') + 1);
			$extension = strtolower($extension[1]); // jpg, png, gif

			if (!in_array($extension, ['jpg', 'jpeg', 'gif', 'png'])) {
				throw new \Exception('invalid image type');
			}

			$data = base64_decode($data);

			if ($data === false) {
				throw new \Exception('base64_decode failed');
			}
		} else {
			throw new \Exception('did not match data URI with image data');
		}
		$directory =  $_REQUEST['upload_directory_relative'];
		$directory = $directory . DIRECTORY_SEPARATOR . $extension;
		$absolutePath = $_REQUEST['upload_directory'] . $directory;
		if (!file_exists($absolutePath)) {
			mkdir($absolutePath, 0777, true);
		}
		$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
		$filename = sprintf('%s.%0.8s', $basename, $extension);
		$directory = $directory . DIRECTORY_SEPARATOR . $filename;
		$res = file_put_contents($absolutePath . DIRECTORY_SEPARATOR . $filename, $data);
		if (!$res) {
			throw new \Exception('something went wrong while saving the picture');
		}
		return 'src' . $directory;
	}
	public static function object_hash($object, $algorithm = 'md5')
	{
		$serialized_object = serialize($object);

		return hash($algorithm, $serialized_object);
	}

	public static function  validate_imei($imei)
	{
		if (!preg_match('/^[0-9]{15}$/', $imei)) return false;
		$sum = 0;
		for ($i = 0; $i < 14; $i++) {
			$num = $imei[$i];
			if (($i % 2) != 0) {
				$num = $imei[$i] * 2;
				if ($num > 9) {
					$num = (string) $num;
					$num = $num[0] + $num[1];
				}
			}
			$sum += $num;
		}
		if ((($sum + $imei[14]) % 10) != 0) return false;
		return true;
	}
}
