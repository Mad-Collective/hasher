<?php

namespace Pluggit\Hasher;

/**
 * The Hasher class is in charge of create and validate hashes given a payload
 *
 * @package Pluggit\Hasher
 */
class Hasher
{
    const PADDING_LENGTH = 8;
    const HASH_LEN = 32;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * Hasher constructor, both parts of the communication, have to use the same private key.
     *
     * @param string $privateKey
     */
    public function __construct($privateKey)
    {
        $this->assertEmptyInput($privateKey);
        $this->privateKey = $privateKey;
    }

    /**
     * Create a hash given a payload
     * @param string $payload
     *
     * @return string
     */
    public function hash($payload)
    {
        $payload = trim($payload);
        $this->assertEmptyInput($payload);

        return $this->addRandomPadding(md5($payload.$this->privateKey));
    }

    /**
     * Validate a hash given a payload
     * @param string $payload
     * @param string $hash
     *
     * @return bool
     */
    public function isValid($payload, $hash)
    {
        $hashLen = self::HASH_LEN + self::PADDING_LENGTH * 2;
        if (strlen($hash) != $hashLen) {
            return false;
        }

        try {
            $h = substr($hash, self::PADDING_LENGTH, self::HASH_LEN);
            $p = substr($this->hash($payload), self::PADDING_LENGTH, self::HASH_LEN);
            return $p === $h;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $payload
     *
     * @return string
     */
    private function addRandomPadding($payload)
    {
        return $this->generateRandomString().$payload.$this->generateRandomString();
    }

    /**
     * @return string
     */
    private function generateRandomString()
    {
        $characters       = '0123456789abcdef';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < self::PADDING_LENGTH; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @param string $input
     *
     * @throws \InvalidArgumentException
     */
    private function assertEmptyInput($input)
    {
        if (empty($input)) {
            throw new \InvalidArgumentException("Empty input is not valid.");
        }
    }
}
