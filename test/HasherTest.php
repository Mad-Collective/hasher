<?php

namespace Test\Pluggit\Hasher;

use PHPUnit_Framework_TestCase;
use Pluggit\Hasher\Hasher;

class HasherTest extends PHPUnit_Framework_TestCase
{
    public function test_can_create_and_validate_hash()
    {
        $payLoad = "1234a";
        $hasher  = new Hasher("testKey");
        $hash    = $hasher->hash($payLoad);
        $this->assertTrue($hasher->isValid($payLoad, $hash));
    }

    public function test_case_sensitive_keys()
    {
        $payLoad = "ABCD";
        $hasher  = new Hasher("testKey");
        $hash    = $hasher->hash($payLoad);
        $this->assertFalse($hasher->isValid(strtolower($payLoad), $hash));
    }

    public function test_utf_keys()
    {
        $payLoad = "\u1000\u1000\u1000\u1000";
        $hasher  = new Hasher("testKey");
        $hash    = $hasher->hash($payLoad);
        $this->assertTrue($hasher->isValid($payLoad, $hash));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_empty_payload()
    {
        $payLoad = "";
        $hasher  = new Hasher("testKey");
        $hasher->hash($payLoad);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_empty_secret()
    {
        new Hasher(null);
    }

    public function test_empty_validation_and_payload()
    {
        $hasher  = new Hasher("testKey");
        $this->assertFalse($hasher->isValid("", ""));
    }

    public function test_invalid_input_size()
    {
        $hasher  = new Hasher("testKey");
        $this->assertFalse($hasher->isValid("sdfd", "sddf"));
    }

    public function test_invalid_payload_size()
    {
        $hasher  = new Hasher("testKey");
        $this->assertFalse($hasher->isValid("", str_repeat("a", 48)));
    }
}
