<?php

namespace Splitstack\EnumFriendly\Tests;

use PHPUnit\Framework\TestCase;
use Splitstack\EnumFriendly\Tests\Enums\TestStatusStr;
use Splitstack\EnumFriendly\Tests\Enums\TestStatusInt;
use Splitstack\EnumFriendly\Tests\Enums\TestStatusUnbacked;
use UnitEnum;

class EnumsTest extends TestCase
{
    /** @test */
    public function it_can_get_all_values()
    {
        // String backed enum
        $this->assertEquals(
            ['pending', 'in_progress', 'completed'],
            TestStatusStr::values()
        );

        // Int backed enum
        $this->assertEquals(
            [1, 2, 3],
            TestStatusInt::values()
        );

        // Unbacked enum
        $this->assertEquals(
            [
                'PENDING',
                'IN_PROGRESS',
                'COMPLETED'
            ],
            TestStatusUnbacked::values()
        );
    }

    /** @test */
    public function it_can_implode_values()
    {
        // String backed enum
        // $this->assertEquals('pending,in_progress,completed', TestStatusStr::implode());
        // $this->assertEquals('pending|in_progress|completed', TestStatusStr::implode('|'));

        // // Int backed enum
        // $this->assertEquals('1,2,3', TestStatusInt::implode());
        // $this->assertEquals('1|2|3', TestStatusInt::implode('|'));

        // Unbacked enum
        $this->assertEquals('PENDING,IN_PROGRESS,COMPLETED', TestStatusUnbacked::implode());
        $this->assertEquals('PENDING|IN_PROGRESS|COMPLETED', TestStatusUnbacked::implode('|'));
    }

    /** @test */
    public function it_can_convert_to_select_options()
    {
        // String backed enum
        $strOptions = TestStatusStr::toOptionsArray();
        $this->assertIsArray($strOptions);
        $this->assertEquals([
            ['value' => 'pending', 'label' => 'Pending', 'name' => 'Pending'],
            ['value' => 'in_progress', 'label' => 'In Progress', 'name' => 'In Progress'],
            ['value' => 'completed', 'label' => 'Completed', 'name' => 'Completed']
        ], $strOptions);

        // Int backed enum
        $intOptions = TestStatusInt::toOptionsArray();
        $this->assertIsArray($intOptions);
        $this->assertEquals([
            ['value' => 1, 'label' => 'Pending', 'name' => 'Pending'],
            ['value' => 2, 'label' => 'In Progress', 'name' => 'In Progress'],
            ['value' => 3, 'label' => 'Completed', 'name' => 'Completed']
        ], $intOptions);

        // Unbacked enum
        $plainOptions = TestStatusUnbacked::toOptionsArray();
        $this->assertIsArray($plainOptions);
        $this->assertEquals([
            ['value' => 'PENDING', 'label' => 'Pending', 'name' => 'Pending'],
            ['value' => 'IN_PROGRESS', 'label' => 'In Progress', 'name' => 'In Progress'],
            ['value' => 'COMPLETED', 'label' => 'Completed', 'name' => 'Completed']
        ], $plainOptions);
    }

    /** @test */
    public function it_can_get_keys()
    {
        $expected = ['PENDING', 'IN_PROGRESS', 'COMPLETED'];

        // All enum types should return the same keys
        $this->assertEquals($expected, TestStatusStr::keys());
        $this->assertEquals($expected, TestStatusInt::keys());
        $this->assertEquals($expected, TestStatusUnbacked::keys());
    }

    /** @test */
    public function it_can_get_readable_values()
    {
        $expected = ['Pending', 'In Progress', 'Completed'];

        // All enum types should return the same readable values
        $this->assertEquals($expected, TestStatusStr::readable());
        $this->assertEquals($expected, TestStatusInt::readable());
        $this->assertEquals($expected, TestStatusUnbacked::readable());
    }

    /** @test */
    public function it_can_get_random_value()
    {
        // String backed enum
        $strRandom = TestStatusStr::random();
        $this->assertContains($strRandom, ['pending', 'in_progress', 'completed']);

        // Int backed enum
        $intRandom = TestStatusInt::random();
        $this->assertContains($intRandom, [1, 2, 3]);

        // Unbacked enum
        $plainRandom = TestStatusUnbacked::random();
        $this->assertContains($plainRandom, ['PENDING', 'IN_PROGRESS', 'COMPLETED']);
    }

    /** @test */
    public function it_can_convert_to_typescript()
    {
        // String backed enum
        $strTs = TestStatusStr::toTypeScript();
        $this->assertArrayHasKey('type', $strTs);
        $this->assertArrayHasKey('values', $strTs);
        $this->assertEquals('TestStatusStr', $strTs['type']);
        $this->assertEquals(['pending', 'in_progress', 'completed'], $strTs['values']);

        // Int backed enum
        $intTs = TestStatusInt::toTypeScript();
        $this->assertEquals('TestStatusInt', $intTs['type']);
        $this->assertEquals([1, 2, 3], $intTs['values']);

        // Unbacked enum
        $plainTs = TestStatusUnbacked::toTypeScript();
        $this->assertEquals('TestStatusUnbacked', $plainTs['type']);
        $this->assertEquals(['PENDING', 'IN_PROGRESS', 'COMPLETED'], $plainTs['values']);
    }

    /** @test */
    public function it_can_generate_comment_strings()
    {
        // String backed enum
        $this->assertEquals(
            'possible values: pending, in_progress, completed',
            TestStatusStr::comment()
        );
        $this->assertEquals(
            'allowed values are: pending, in_progress, completed',
            TestStatusStr::comment('allowed values are: ')
        );

        // Int backed enum
        $this->assertEquals(
            'possible values: 1, 2, 3',
            TestStatusInt::comment()
        );
        $this->assertEquals(
            'allowed values are: 1, 2, 3',
            TestStatusInt::comment('allowed values are: ')
        );

        // Unbacked enum
        $this->assertEquals(
            'possible values: PENDING, IN_PROGRESS, COMPLETED',
            TestStatusUnbacked::comment()
        );
        $this->assertEquals(
            'allowed values are: PENDING, IN_PROGRESS, COMPLETED',
            TestStatusUnbacked::comment('allowed values are: ')
        );
    }

    /** @test */
    public function it_can_return_safe_enum_instance_or_null_regardless_of_passed_param_type()
    {

        $this->assertInstanceOf(
            TestStatusStr::class,
            TestStatusStr::coerceEnum('pending')
        );

        $this->assertInstanceOf(
            TestStatusStr::class,
            TestStatusStr::coerceEnum(TestStatusStr::PENDING)
        );

        $this->assertInstanceOf(
            TestStatusInt::class,
            TestStatusInt::coerceEnum(1)
        );

        $this->assertInstanceOf(
            TestStatusInt::class,
            TestStatusInt::coerceEnum(TestStatusInt::PENDING)
        );

        $this->assertInstanceOf(
            TestStatusUnbacked::class,
            TestStatusUnbacked::coerceEnum('PENDING')
        );

        $this->assertNull(
            TestStatusUnbacked::coerceEnum('NON_EXISTENT')
        );

        $this->assertNull(
            TestStatusInt::coerceEnum(999)
        );

        $this->assertNull(
            TestStatusStr::coerceEnum('non_existent')
        );

        $this->assertNull(
            TestStatusStr::coerceEnum(null)
        );

        $this->assertNull(
            TestStatusInt::coerceEnum(null)
        );

        $this->assertNull(
            TestStatusUnbacked::coerceEnum(null)
        );
    }

    /** @test */
    public function it_can_return_safe_value_regardless_of_passed_param_type()
    {

        $this->assertEquals(
            'pending',
            TestStatusStr::coerceValue('pending')
        );

        $this->assertEquals(
            'pending',
            TestStatusStr::coerceValue(TestStatusStr::PENDING)
        );

        $this->assertEquals(
            1,
            TestStatusInt::coerceValue(1)
        );

        $this->assertEquals(
            1,
            TestStatusInt::coerceValue(TestStatusInt::PENDING)
        );

        $this->assertEquals(
            'PENDING',
            TestStatusUnbacked::coerceValue('PENDING')
        );

        $this->assertNull(
            TestStatusUnbacked::coerceValue('NON_EXISTENT')
        );

        $this->assertNull(
            TestStatusInt::coerceValue(999)
        );

        $this->assertNull(
            TestStatusStr::coerceValue('non_existent')
        );

        $this->assertNull(
            TestStatusStr::coerceValue(null)
        );

        $this->assertNull(
            TestStatusInt::coerceValue(null)
        );

        $this->assertNull(
            TestStatusUnbacked::coerceValue(null)
        );
    }

    /** @test */
    public function it_can_convert_to_readable_array()
    {
        // String backed enum
        $strreadable = TestStatusStr::toReadableArray();
        $this->assertEquals([
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed'
        ], $strreadable);

        // Int backed enum
        $intreadable = TestStatusInt::toReadableArray();
        $this->assertEquals([
            1 => 'Pending',
            2 => 'In Progress',
            3 => 'Completed'
        ], $intreadable);

        // Unbacked enum
        $unbackedreadable = TestStatusUnbacked::toReadableArray();
        $this->assertEquals([
            'PENDING' => 'Pending',
            'IN_PROGRESS' => 'In Progress',
            'COMPLETED' => 'Completed'
        ], $unbackedreadable);
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        // String backed enum
        $strArray = TestStatusStr::toArray();
        $this->assertEquals([
            'pending' => 'PENDING',
            'in_progress' => 'IN_PROGRESS',
            'completed' => 'COMPLETED'
        ], $strArray);

        // Int backed enum
        $intArray = TestStatusInt::toArray();
        $this->assertEquals([
            1 => 'PENDING',
            2 => 'IN_PROGRESS',
            3 => 'COMPLETED'
        ], $intArray);

        // Unbacked enum
        $unbackedArray = TestStatusUnbacked::toArray();
        $this->assertEquals([
            'PENDING' => 'PENDING',
            'IN_PROGRESS' => 'IN_PROGRESS',
            'COMPLETED' => 'COMPLETED'
        ], $unbackedArray);
    }

    /** @test */
    public function it_can_get_random_case()
    {
        // String backed enum
        $strRandomCase = TestStatusStr::randomCase();
        $this->assertInstanceOf(TestStatusStr::class, $strRandomCase);
        $this->assertContains($strRandomCase, TestStatusStr::cases());

        // Int backed enum
        $intRandomCase = TestStatusInt::randomCase();
        $this->assertInstanceOf(TestStatusInt::class, $intRandomCase);
        $this->assertContains($intRandomCase, TestStatusInt::cases());

        // Unbacked enum
        $unbackedRandomCase = TestStatusUnbacked::randomCase();
        $this->assertInstanceOf(TestStatusUnbacked::class, $unbackedRandomCase);
        $this->assertContains($unbackedRandomCase, TestStatusUnbacked::cases());
    }

    /** @test */
    public function it_can_get_value_with_fallback()
    {
        // String backed enum - valid value
        $result = TestStatusStr::fromValueOr('pending', TestStatusStr::COMPLETED);
        $this->assertEquals(TestStatusStr::PENDING, $result);

        // String backed enum - invalid value with fallback
        $result = TestStatusStr::fromValueOr('invalid', TestStatusStr::COMPLETED);
        $this->assertEquals(TestStatusStr::COMPLETED, $result);

        // Int backed enum - valid value
        $result = TestStatusInt::fromValueOr(2, TestStatusInt::COMPLETED);
        $this->assertEquals(TestStatusInt::IN_PROGRESS, $result);

        // Int backed enum - invalid value with fallback
        $result = TestStatusInt::fromValueOr(999, TestStatusInt::COMPLETED);
        $this->assertEquals(TestStatusInt::COMPLETED, $result);

        // Unbacked enum - valid value
        $result = TestStatusUnbacked::fromValueOr('PENDING', TestStatusUnbacked::COMPLETED);
        $this->assertEquals(TestStatusUnbacked::PENDING, $result);

        // Unbacked enum - invalid value with fallback
        $result = TestStatusUnbacked::fromValueOr('INVALID', TestStatusUnbacked::COMPLETED);
        $this->assertEquals(TestStatusUnbacked::COMPLETED, $result);
    }

    /** @test */
    public function it_can_check_if_value_exists()
    {
        // String backed enum
        $this->assertTrue(TestStatusStr::hasValue('pending'));
        $this->assertTrue(TestStatusStr::hasValue('in_progress'));
        $this->assertFalse(TestStatusStr::hasValue('invalid'));
        $this->assertFalse(TestStatusStr::hasValue('PENDING')); // Case sensitive

        // Int backed enum
        $this->assertTrue(TestStatusInt::hasValue(1));
        $this->assertTrue(TestStatusInt::hasValue(2));
        $this->assertFalse(TestStatusInt::hasValue(999));
        
        // Test strict vs non-strict comparison
        $this->assertFalse(TestStatusInt::hasValue('1', true)); // strict
        $this->assertTrue(TestStatusInt::hasValue('1', false)); // non-strict

        // Unbacked enum
        $this->assertTrue(TestStatusUnbacked::hasValue('PENDING'));
        $this->assertTrue(TestStatusUnbacked::hasValue('IN_PROGRESS'));
        $this->assertFalse(TestStatusUnbacked::hasValue('pending')); // Case sensitive
        $this->assertFalse(TestStatusUnbacked::hasValue('INVALID'));
    }

    /** @test */
    public function it_can_filter_cases_by_names()
    {
        // String backed enum - only specific cases
        $strOnly = TestStatusStr::only(['PENDING', 'COMPLETED']);
        $this->assertCount(2, $strOnly);
        $names = array_map(fn($case) => $case->name, $strOnly);
        $this->assertContains('PENDING', $names);
        $this->assertContains('COMPLETED', $names);

        // Int backed enum - only specific cases
        $intOnly = TestStatusInt::only(['IN_PROGRESS']);
        $this->assertCount(1, $intOnly);
        $names = array_map(fn($case) => $case->name, $intOnly);
        $this->assertContains('IN_PROGRESS', $names);

        // Unbacked enum - only specific cases
        $unbackedOnly = TestStatusUnbacked::only(['PENDING', 'IN_PROGRESS']);
        $this->assertCount(2, $unbackedOnly);
        $names = array_map(fn($case) => $case->name, $unbackedOnly);
        $this->assertContains('PENDING', $names);
        $this->assertContains('IN_PROGRESS', $names);

        // Test with non-existent case names
        $empty = TestStatusStr::only(['NON_EXISTENT']);
        $this->assertEmpty($empty);
    }

    /** @test */
    public function it_can_filter_cases_by_values()
    {
        // String backed enum - only specific values
        $strOnlyValues = TestStatusStr::onlyValues(['pending', 'completed']);
        $this->assertCount(2, $strOnlyValues);
        $this->assertContains('pending', $strOnlyValues);
        $this->assertContains('completed', $strOnlyValues);

        // Int backed enum - only specific values
        $intOnlyValues = TestStatusInt::onlyValues([1, 3]);
        $this->assertCount(2, $intOnlyValues);
        $this->assertContains(1, $intOnlyValues);
        $this->assertContains(3, $intOnlyValues);

        // Unbacked enum - only specific names (since unbacked)
        $unbackedOnlyValues = TestStatusUnbacked::onlyValues(['PENDING']);
        $this->assertCount(1, $unbackedOnlyValues);
        $this->assertContains('PENDING', $unbackedOnlyValues);

        // Test strict vs non-strict comparison for int enum
        $strictResult = TestStatusInt::onlyValues(['1'], true);
        $this->assertEmpty($strictResult); // '1' string doesn't match 1 int strictly

        $nonStrictResult = TestStatusInt::onlyValues(['1'], false);
        $this->assertCount(1, $nonStrictResult); // '1' string matches 1 int non-strictly
    }

    /** @test */
    public function it_can_exclude_cases_by_names()
    {
        // String backed enum - exclude specific cases
        $strExcept = TestStatusStr::except(['IN_PROGRESS']);
        $this->assertCount(2, $strExcept);
        $names = array_map(fn($case) => $case->name, $strExcept);
        $this->assertContains('PENDING', $names);
        $this->assertContains('COMPLETED', $names);
        $this->assertNotContains('IN_PROGRESS', $names);

        // Int backed enum - exclude multiple cases
        $intExcept = TestStatusInt::except(['PENDING', 'COMPLETED']);
        $this->assertCount(1, $intExcept);
        $names = array_map(fn($case) => $case->name, $intExcept);
        $this->assertContains('IN_PROGRESS', $names);

        // Unbacked enum - exclude all but one
        $unbackedExcept = TestStatusUnbacked::except(['PENDING', 'IN_PROGRESS']);
        $this->assertCount(1, $unbackedExcept);
        $names = array_map(fn($case) => $case->name, $unbackedExcept);
        $this->assertContains('COMPLETED', $names);

        // Test with non-existent case names (should return all)
        $all = TestStatusStr::except(['NON_EXISTENT']);
        $this->assertCount(3, $all);
    }

    /** @test */
    public function it_can_exclude_cases_by_values()
    {
        // String backed enum - exclude specific values
        $strExceptValues = TestStatusStr::exceptValues(['in_progress']);
        $this->assertCount(2, $strExceptValues);
        $this->assertContains('pending', $strExceptValues);
        $this->assertContains('completed', $strExceptValues);
        $this->assertNotContains('in_progress', $strExceptValues);

        // Int backed enum - exclude multiple values
        $intExceptValues = TestStatusInt::exceptValues([1, 3]);
        $this->assertCount(1, $intExceptValues);
        $this->assertContains(2, $intExceptValues);

        // Unbacked enum - exclude specific names
        $unbackedExceptValues = TestStatusUnbacked::exceptValues(['COMPLETED']);
        $this->assertCount(2, $unbackedExceptValues);
        $this->assertContains('PENDING', $unbackedExceptValues);
        $this->assertContains('IN_PROGRESS', $unbackedExceptValues);

        // Test strict vs non-strict comparison
        $strictResult = TestStatusInt::exceptValues(['2'], true);
        $this->assertCount(3, $strictResult); // '2' string doesn't match 2 int strictly

        $nonStrictResult = TestStatusInt::exceptValues(['2'], false);
        $this->assertCount(2, $nonStrictResult); // '2' string matches 2 int non-strictly
    }

    /** @test */
    public function it_can_count_enum_cases()
    {
        // All test enums have 3 cases
        $this->assertEquals(3, TestStatusStr::count());
        $this->assertEquals(3, TestStatusInt::count());
        $this->assertEquals(3, TestStatusUnbacked::count());
    }

    /** @test */
    public function it_can_check_if_enum_is_backed()
    {
        // String and int backed enums should return true
        $this->assertTrue(TestStatusStr::isBacked());
        $this->assertTrue(TestStatusInt::isBacked());
        
        // Unbacked enum should return false
        $this->assertFalse(TestStatusUnbacked::isBacked());
    }

    /** @test */
    public function it_can_get_description()
    {
        // Since our test enums don't have getDescription methods, 
        // description() should return null
        $pending = TestStatusStr::PENDING;
        $this->assertNull($pending->description());

        $inProgress = TestStatusInt::IN_PROGRESS;
        $this->assertNull($inProgress->description());

        $completed = TestStatusUnbacked::COMPLETED;
        $this->assertNull($completed->description());
    }

    /** @test */
    public function it_can_compare_enum_cases()
    {
        // String backed enum comparisons
        $pending = TestStatusStr::PENDING;
        
        // Compare with same case
        $this->assertTrue($pending->is(TestStatusStr::PENDING));
        
        // Compare with different case
        $this->assertFalse($pending->is(TestStatusStr::COMPLETED));
        
        // Compare with value string
        $this->assertTrue($pending->is('pending'));
        $this->assertFalse($pending->is('completed'));
        
        // Compare with invalid value
        $this->assertFalse($pending->is('invalid'));

        // Int backed enum comparisons
        $inProgress = TestStatusInt::IN_PROGRESS;
        
        // Compare with same case
        $this->assertTrue($inProgress->is(TestStatusInt::IN_PROGRESS));
        
        // Compare with different case
        $this->assertFalse($inProgress->is(TestStatusInt::PENDING));
        
        // Compare with value integer
        $this->assertTrue($inProgress->is(2));
        $this->assertFalse($inProgress->is(1));

        // Unbacked enum comparisons
        $completed = TestStatusUnbacked::COMPLETED;
        
        // Compare with same case
        $this->assertTrue($completed->is(TestStatusUnbacked::COMPLETED));
        
        // Compare with different case
        $this->assertFalse($completed->is(TestStatusUnbacked::PENDING));
        
        // Compare with name string
        $this->assertTrue($completed->is('COMPLETED'));
        $this->assertFalse($completed->is('PENDING'));
    }

    /** @test */
    public function it_can_check_if_enum_is_in_array()
    {
        // String backed enum
        $pending = TestStatusStr::PENDING;
        
        // Check if in array of enum cases
        $this->assertTrue($pending->in([TestStatusStr::PENDING, TestStatusStr::COMPLETED]));
        $this->assertFalse($pending->in([TestStatusStr::IN_PROGRESS, TestStatusStr::COMPLETED]));
        
        // Check if in array of string values
        $this->assertTrue($pending->in(['pending', 'completed']));
        $this->assertFalse($pending->in(['in_progress', 'completed']));
        
        // Check with mixed types
        $this->assertTrue($pending->in([TestStatusStr::COMPLETED, 'pending']));

        // Int backed enum
        $inProgress = TestStatusInt::IN_PROGRESS;
        
        // Check if in array of enum cases
        $this->assertTrue($inProgress->in([TestStatusInt::IN_PROGRESS, TestStatusInt::COMPLETED]));
        $this->assertFalse($inProgress->in([TestStatusInt::PENDING, TestStatusInt::COMPLETED]));
        
        // Check if in array of integer values
        $this->assertTrue($inProgress->in([1, 2]));
        $this->assertFalse($inProgress->in([1, 3]));

        // Unbacked enum
        $completed = TestStatusUnbacked::COMPLETED;
        
        // Check if in array of enum cases
        $this->assertTrue($completed->in([TestStatusUnbacked::COMPLETED, TestStatusUnbacked::PENDING]));
        $this->assertFalse($completed->in([TestStatusUnbacked::PENDING, TestStatusUnbacked::IN_PROGRESS]));
        
        // Check if in array of name strings
        $this->assertTrue($completed->in(['COMPLETED', 'PENDING']));
        $this->assertFalse($completed->in(['PENDING', 'IN_PROGRESS']));
        
        // Empty array should return false
        $this->assertFalse($pending->in([]));
        $this->assertFalse($inProgress->in([]));
        $this->assertFalse($completed->in([]));
    }

    /** @test */
    public function it_can_convert_to_json_array()
    {
        $arr = TestStatusStr::toJsonArray();
        $this->assertIsArray($arr);
        $this->assertEquals([
            'PENDING' => 'pending',
            'IN_PROGRESS' => 'in_progress',
            'COMPLETED' => 'completed'
        ], $arr);

        $arr = TestStatusInt::toJsonArray();
        $this->assertIsArray($arr);
        $this->assertEquals([
            'PENDING' => 1,
            'IN_PROGRESS' => 2,
            'COMPLETED' => 3
        ], $arr);

        $arr = TestStatusUnbacked::toJsonArray();
        $this->assertIsArray($arr);

    }
}
