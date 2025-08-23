<?php

namespace Coderubix\GeminiSearch\Tests\Unit;

use Coderubix\GeminiSearch\Services\QueryValidator;
use PHPUnit\Framework\TestCase;

class QueryValidatorTest extends TestCase
{
    public function test_it_validates_safe_select_queries()
    {
        $this->assertTrue(QueryValidator::isSafe('SELECT * FROM users'));
        $this->assertTrue(QueryValidator::isSafe('SELECT id, name FROM users WHERE active = 1'));
        $this->assertTrue(QueryValidator::isSafe('SELECT COUNT(*) as total FROM orders'));
    }

    public function test_it_rejects_unsafe_queries()
    {
        $this->assertFalse(QueryValidator::isSafe('INSERT INTO users (name) VALUES ("test")'));
        $this->assertFalse(QueryValidator::isSafe('UPDATE users SET name = "test"'));
        $this->assertFalse(QueryValidator::isSafe('DELETE FROM users'));
        $this->assertFalse(QueryValidator::isSafe('DROP TABLE users'));
    }

    public function test_it_handles_case_insensitive_queries()
    {
        $this->assertTrue(QueryValidator::isSafe('select * from users'));
        $this->assertTrue(QueryValidator::isSafe('Select * From users'));
        $this->assertFalse(QueryValidator::isSafe('insert into users values (1)'));
    }

    public function test_it_handles_whitespace()
    {
        $this->assertTrue(QueryValidator::isSafe('   SELECT * FROM users   '));
        $this->assertFalse(QueryValidator::isSafe('   INSERT INTO users   '));
    }
}
