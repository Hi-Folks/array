<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function test_is_array(): void
    {
        $arr = Arr::make();
        $this->assertIsArray($arr->arr());
    }

    public function test_creates_arr_from_function(): void
    {
        $arr = Arr::fromFunction(fn (): int => random_int(0, 100), 500);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(500, $arr->length());

        $this->assertTrue($arr->every(fn ($element): bool => $element >= 0));
        $this->assertTrue($arr->every(fn ($element): bool => $element <= 100));

        $arr = Arr::fromFunction(fn ($i) => $i, 5000);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(5000, $arr->length());

        $this->assertTrue($arr->every(fn ($element): bool => $element >= 0));
        $this->assertTrue($arr->every(fn ($element): bool => $element <= 5000));
    }

    public function test_creates_arr_from_value(): void
    {
        $arr = Arr::fromValue(0, 5000);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(5000, $arr->length());
        $this->assertTrue($arr->every(fn ($element): bool => $element === 0));
    }

    public function test_is_array_empty(): void
    {
        $arr = Arr::make();
        $this->assertIsArray($arr->arr());
        $this->assertEquals(0, count($arr->arr()));
        $this->assertEquals(0, $arr->count());
        $this->assertEquals(0, $arr->length());
    }

    public function test_is_array_length_1(): void
    {
        $arr = Arr::make([99]);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(1, count($arr->arr()));
        $this->assertEquals(1, $arr->count());
        $this->assertEquals(1, $arr->length());
    }

    public function test_is_array_for_each(): void
    {
        $arr = Arr::make([99, 98]);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(2, count($arr->arr()));
        $this->assertEquals(2, $arr->count());
        $this->assertEquals(2, $arr->length());
        $this->assertEquals(3, $arr->push(100));
        $this->assertEquals(3, $arr->length());
        $x = $arr->forEach(fn ($element, $key): string => $key * $element . PHP_EOL);
        $this->assertEquals(3, $x->length());
        $this->assertEquals(200, $x->get(2));
    }

    public function test_shift_array(): void
    {
        $arr = Arr::make([99, 98, 97]);
        $this->assertEquals(3, $arr->length());
        $this->assertEquals(99, $arr->shift());
        $this->assertEquals(2, $arr->length());
    }

    public function test_unshift_array(): void
    {
        $arr = Arr::make([99, 98, 97]);
        $this->assertEquals(3, $arr->length());
        $this->assertEquals(4, $arr->unshift(200));
        $this->assertEquals(200, $arr->get(0));
        $this->assertEquals(99, $arr->get(1));

        $arr = Arr::make([1, 2]);
        $arr->unshift(0);
        $this->assertEquals('0,1,2', $arr->toString());
        $arr->unshift(-2, -1);
        $this->assertEquals('-2,-1,0,1,2', $arr->toString());
    }

    public function test_append_array(): void
    {
        $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
        $this->assertEquals(6, $arr->length());
        $arr->append([12], [13, 14]);
        $this->assertEquals(9, $arr->length());
        $arr = Arr::make();
        $arr->append([11]);
        $this->assertEquals(1, $arr->length());
        $number = $arr->pop();
        $this->assertEquals(0, $arr->length());
        $this->assertEquals(11, $number);
    }

    public function test_joins_arrays(): void
    {
        $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
        $this->assertEquals('99,98,97,1,2,3', $arr->join());
    }

    public function test_concats_arrays(): void
    {
        $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
        $arr2 = $arr->concat([1000, 1001]);
        $this->assertIsArray($arr2->arr());
        $this->assertEquals(8, $arr2->length());
        $this->assertEquals(6, $arr->length());
    }

    public function test_concats_more_types(): void
    {
        $arr = Arr::make([99, 98, 97])->concat([1, 2, 3], [1000, 1001]);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(8, $arr->length());
        $arr2 = $arr->concat(9, true);
        $this->assertEquals(10, $arr2->length());
        $arr3 = $arr->concat($arr2);
        $this->assertEquals(18, $arr3->length());
    }

    public function test_concats_indexed_associative_arrays(): void
    {
        $fruits = Arr::make([
            3 => '🥝',
            -1 => '🍓',
            1 => '🍋',
            'mango' => '🥭',
            'apple' => '🍎',
            'banana' => '🍌',
        ]);
        $fruits2 = $fruits->concat(['🍊', '🍍']);

        $this->assertIsArray($fruits2->arr());
        $this->assertEquals(8, $fruits2->length());
        $this->assertEquals('🥭', $fruits2['mango']);
        $this->assertEquals('🍍', $fruits2[4]);
    }

    public function test_slices_arrays(): void
    {
        $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
        $arr2 = $arr->slice(1, 2);
        $this->assertIsArray($arr2->arr());
        $this->assertEquals(1, $arr2->length());
        $this->assertEquals(98, $arr2->get(0));
        $this->assertNull($arr2->get(1));
        $this->assertEquals(6, $arr->length());

        $animals = Arr::make(['ant', 'bison', 'camel', 'duck', 'elephant']);
        $arr = $animals->slice(2, 4);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(2, $arr->length());
        $this->assertEquals('camel', $arr[0]);
        $this->assertEquals('duck', $arr[1]);
        $this->assertNull($arr->get(2));

        $arr = $animals->slice(2);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(3, $arr->length());
        $this->assertEquals('camel', $arr[0]);
        $this->assertEquals('duck', $arr[1]);
        $this->assertEquals('elephant', $arr[2]);
        $this->assertNull($arr->get(3));
        $this->assertNull($arr[3]);

        $arr = $animals->slice(1, 5);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(4, $arr->length());
        $this->assertEquals('bison', $arr[0]);
        $this->assertEquals('camel', $arr[1]);
        $this->assertEquals('duck', $arr[2]);
        $this->assertEquals('elephant', $arr[3]);
        $this->assertNull($arr->get(4));
        $this->assertNull($arr[4]);

        $arr = $animals->slice(-2);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(2, $arr->length());
        $this->assertEquals('duck', $arr[0]);
        $this->assertEquals('elephant', $arr[1]);
        $this->assertNull($arr->get(2));
        $this->assertNull($arr[2]);

        $arr = $animals->slice(2, -1);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(2, $arr->length());
        $this->assertEquals('camel', $arr[0]);
        $this->assertEquals('duck', $arr[1]);
        $this->assertNull($arr->get(2));
        $this->assertNull($arr[2]);

        $arr = $animals->slice(-3, -1);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(2, $arr->length());
        $this->assertEquals('camel', $arr[0]);
        $this->assertEquals('duck', $arr[1]);
        $this->assertNull($arr->get(2));
        $this->assertNull($arr[2]);

        $arr = $animals->slice(27, -1);
        $this->assertIsArray($arr->arr());
        $this->assertEquals(0, $arr->length());
        $this->assertNull($arr[0]);
    }

    public function test_searches_arrays(): void
    {
        $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
        $index = $arr->indexOf(1);
        $this->assertIsInt($index);
        $this->assertEquals(3, $index);
        $this->assertEquals(6, $arr->length());
        $arr = Arr::make(['L' => 'Leon', 'T' => 'Tiger', 'B' => 'Bird']);
        $index = $arr->indexOf('Tiger');
        $this->assertIsString($index);
        $this->assertEquals('T', $index);
        $this->assertEquals(3, $arr->length());
    }

    public function test_searches_last_occurrence_arrays(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $index = $arr->lastIndexOf(5);
        $this->assertIsInt($index);
        $this->assertEquals(12, $index);
        $this->assertEquals(17, $arr->length());
        $arr = Arr::make(['L1' => 'Leon', 'T1' => 'Tiger', 'B1' => 'Bird', 'T2' => 'Tiger', 'B2' => 'Bird']);
        $index = $arr->lastIndexOf('Tiger');
        $this->assertIsString($index);
        $this->assertEquals('T2', $index);
        $this->assertEquals(5, $arr->length());
    }

    public function test_matches_every_element(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $bool = $arr->every(fn ($element): bool => $element > 0);
        $this->assertEquals(true, $bool);
        $bool = $arr->every(fn ($element): bool => $element > 1);
        $this->assertEquals(false, $bool);
        $bool = $arr->every(fn ($element): bool => $element > 10000);
        $this->assertEquals(false, $bool);
    }

    public function test_matches_some_element(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $bool = $arr->some(fn ($element): bool => $element > 0);
        $this->assertEquals(true, $bool);
        $bool = $arr->some(fn ($element): bool => $element > 1);
        $this->assertEquals(true, $bool);
        $bool = $arr->some(fn ($element): bool => $element > 10000);
        $this->assertEquals(false, $bool);

        $arr = Arr::make([1, 2, 3, 4, 5]);
        $even = fn ($element): bool => $element % 2 === 0;
        $bool = $arr->some($even);
        $this->assertEquals(true, $bool);
    }

    public function test_filters_some_element(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $arr2 = $arr->filter(fn ($element): bool => $element > 3);
        $this->assertEquals(17, $arr->length());
        $this->assertEquals(11, $arr2->length());
        $this->assertTrue($arr->every(fn ($element): bool => $element > 0));
        $this->assertFalse($arr->every(fn ($element): bool => $element <= 3));
        $this->assertTrue($arr2->every(fn ($element): bool => $element > 3));
    }

    public function test_finds_the_first_element(): void
    {
        $intArr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 11]);
        $strArr = Arr::make(['begin', 'middle', 'end']);

        $result = $intArr->find(fn ($element): bool => $element > 0);
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);

        $result = $intArr->find(fn ($element): bool => $element < 5);
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);

        $result = $intArr->find(fn ($element): bool => $element > 5);
        $this->assertIsInt($result);
        $this->assertEquals(6, $result);

        $result = $intArr->find(fn ($element, $index): bool => $element > 1 && $index > 1);
        $this->assertIsInt($result);
        $this->assertEquals(3, $result);

        $result = $intArr->find(fn ($element): bool => $element > 50);
        $this->assertNull($result);

        $result = $strArr->find(fn ($element): bool => $element == 'end');
        $this->assertIsString($result);
        $this->assertSame('end', $result);

        $result = $strArr->find(fn ($element): bool => str_contains((string) $element, 'e'));
        $this->assertIsString($result);
        $this->assertSame('begin', $result);

        $result = $strArr->find(fn ($element): bool => $element == 'prefix');
        $this->assertNull($result);
    }

    public function test_finds_the_first_index_of_some_element(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $index = $arr->findIndex(fn ($element): bool => $element > 0);
        $this->assertIsInt($index);
        $this->assertEquals(0, $index);
        $index = $arr->findIndex(fn ($element): bool => $element > 1);
        $this->assertEquals(1, $index);
        $index = $arr->findIndex(fn ($element): bool => $element > 10000);
        $this->assertEquals(-1, $index);
        $index = $arr->findIndex(fn ($element, $index): bool => $element > 1 && $index > 1);
        $this->assertEquals(2, $index);
    }

    public function test_maps_elements(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $arr2 = $arr->map(fn ($element): int|float => $element + 1);

        $this->assertEquals(17, $arr->length());
        $this->assertEquals(17, $arr2->length());
        $this->assertTrue($arr->every(fn ($element): bool => $element > 0));
        $this->assertFalse($arr->every(fn ($element): bool => $element > 1));
        $this->assertTrue($arr2->every(fn ($element): bool => $element > 1));
    }

    public function test_flats_array(): void
    {
        $arr = Arr::make([1, [2, 3], 4, [5, 6, 7]]);

        $arr2 = $arr->flat();
        $this->assertEquals(4, $arr->length());
        $this->assertEquals(7, $arr2->length());
    }

    public function test_flats_and_maps_array(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);

        $arr2 = $arr->flatMap(fn ($element): array => [$element, $element * 2]);
        $this->assertEquals(7, $arr->length());
        $this->assertEquals(14, $arr2->length());
        $this->assertEquals([1, 2, 2, 4, 3, 6, 4, 8, 5, 10, 6, 12, 7, 14], $arr2->arr());

        $arr2 = $arr->flatMap(fn ($element) => $element);
        $this->assertEquals(7, $arr->length());
        $this->assertEquals(7, $arr2->length());
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7], $arr2->arr());
    }

    public function test_fills_array(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
        $this->assertEquals(7, $arr->length());
        $arr->fill(0, 0, 3);
        $this->assertEquals(7, $arr->length());
        $this->assertEquals(0, $arr[0]);
        $this->assertEquals(0, $arr[1]);
        $this->assertEquals(0, $arr[2]);
        $this->assertEquals(0, $arr[3]);
        $this->assertEquals(5, $arr[4]);
        $this->assertEquals(6, $arr[5]);
        $this->assertEquals(7, $arr[6]);
        $arr2 = $arr->filter(fn ($element): bool => $element == 0);
        $this->assertEquals(4, $arr2->length());
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
        $arr->fill(0);
        $allAreZeros = $arr->every(fn ($element): bool => $element === 0);
        $this->assertEquals(7, $arr->length());
        $this->assertTrue($allAreZeros);
    }

    public function test_reduces_arr(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
        $value = $arr->reduce(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
        $this->assertIsInt($value);
        $this->assertEquals(28, $value);
    }

    public function test_reduces_arr_in_reverse_way(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
        $value = $arr->reduceRight(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
        $this->assertIsInt($value);
        $this->assertEquals(28, $value);
    }

    public function test_reverses_arr(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
        $arr2 = $arr->reverse();
        $this->assertEquals(7, $arr2->length());
        $this->assertEquals('7,6,5,4,3,2,1', $arr2->join());
        $this->assertEquals('7,6,5,4,3,2,1', $arr->join());
        $arr->push(0);
        $this->assertEquals('7,6,5,4,3,2,1', $arr2->join());
        $this->assertEquals('7,6,5,4,3,2,1,0', $arr->join());
    }

    public function test_sorts_arr(): void
    {
        $arr = Arr::make([6, 2, 4, 2, 1, 9, 7]);
        $this->assertEquals('6,2,4,2,1,9,7', $arr->join());
        $arr->sort();
        $this->assertEquals(7, $arr->length());
        $this->assertEquals('1,2,2,4,6,7,9', $arr->join());
    }

    public function test_sorts_and_change_arr(): void
    {
        $months = Arr::make(['March', 'Jan', 'Feb', 'Dec']);
        $monthsSorted = $months->sort();
        $this->assertEquals(4, $months->length());
        $this->assertEquals(4, $monthsSorted->length());
        $this->assertEquals('March', $months[3]);
        $this->assertEquals('March', $monthsSorted[3]);
        $month = $months->pop();
        $this->assertEquals('March', $month);
        $this->assertNull($months[3]);
        $this->assertNull($monthsSorted[3]);
    }

    public function test_splices_arr(): void
    {
        $months = Arr::make(['Jan', 'March', 'April', 'June']);
        $months->splice(1, 0, 'Feb');
        $this->assertEquals('Jan,Feb,March,April,June', $months->join());
        $months->splice(4, 1, 'May');
        $this->assertEquals('Jan,Feb,March,April,May', $months->join());
        $months->splice(1);
        $this->assertEquals('Jan', $months->join());
    }

    public function test_stringifies_an_arr(): void
    {
        $months = Arr::make(['Jan', 'Feb', 'March', 'April', 'May']);
        $this->assertEquals('Jan,Feb,March,April,May', $months->toString());

        $arr = Arr::make([1, 2, 'a', '1a']);
        $this->assertEquals('1,2,a,1a', $arr->toString());
    }

    public function test_checks_is_array(): void
    {
        $isArray = Arr::isArray(['Jan', 'Feb', 'March', 'April', 'May']);
        $this->assertEquals(true, $isArray);
        $isArray = Arr::isArray(null);
        $this->assertEquals(false, $isArray);
        $isArray = Arr::isArray(1);
        $this->assertEquals(false, $isArray);
        $isArray = Arr::isArray(0);
        $this->assertEquals(false, $isArray);
    }

    public function test_tests_if_array_is_empty(): void
    {
        $emptyArr = Arr::make([]);
        $this->assertEquals(true, $emptyArr->isEmpty());
        $notEmptyArr = Arr::make([1]);
        $this->assertEquals(false, $notEmptyArr->isEmpty());
    }

    public function test_chainable_empty_array(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertTrue($arr->filter(fn ($element): bool => $element > 100)->isEmpty());
        $this->assertFalse($arr->filter(fn ($element): bool => $element < 100)->isEmpty());
    }

    public function test_implements_of_method(): void
    {
        $arr = Arr::of('Jan', 'Feb', 'March', 'April', 'May');
        $this->assertEquals(5, $arr->length());
        $this->assertEquals('May', $arr[4]);

        $arr = Arr::of(7);
        $this->assertEquals(1, $arr->length());
        $this->assertEquals(7, $arr[0]);
    }

    public function test_tests_keys_method(): void
    {
        $arr = Arr::make(
            [
                '01' => 'Jan', '02' => 'Feb', '03' => 'March', '04' => 'April',
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
            ]
        );
        $this->assertEquals(12, $arr->length());
        $arrKeys = $arr->keys(true);
        $this->assertIsObject($arrKeys);
        $this->assertEquals(12, $arrKeys->length());
        $this->assertEquals('05', $arrKeys[4]);

        $keys = $arr->keys();
        $this->assertIsArray($keys);
        $this->assertEquals(12, is_countable($keys) ? count($keys) : 0);
        $this->assertEquals('05', $keys[4]);

        foreach ($arrKeys as $key => $value) {
            $this->assertEquals($keys[$key], $arrKeys[$key]);
        }
    }

    public function test_at_method(): void
    {
        $arr = Arr::make(
            [5, 12, 8, 130, 44]
        );
        $this->assertEquals(5, $arr->length());
        $this->assertEquals(8, $arr->at(2));
        $this->assertEquals(130, $arr->at(-2));
        $this->assertNull($arr->at(100));
    }

    public function test_includes_element(): void
    {
        $arr = Arr::make(
            [1, 2, 3]
        );
        $this->assertEquals(3, $arr->length());
        $this->assertTrue($arr->includes(2));
        $this->assertFalse($arr->includes('2'));

        $arr = Arr::make(
            ['cat', 'dog', 'bat']
        );
        $this->assertEquals(3, $arr->length());
        $this->assertTrue($arr->includes('cat'));
        $this->assertFalse($arr->includes('Cat'));
        $this->assertFalse($arr->includes('at'));
    }

    public function test_includes_element_from_index(): void
    {
        $arr = Arr::make(
            [1, 2, 3]
        );
        $this->assertEquals(3, $arr->length());
        $this->assertTrue($arr->includes(2));
        $this->assertFalse($arr->includes(4));
        $this->assertFalse($arr->includes(3, 3));
        $this->assertTrue($arr->includes(3, 2));
        $this->assertTrue($arr->includes(3, -1));

        $arr = Arr::make(
            ['a', 'b', 'c']
        );
        $this->assertFalse($arr->includes('c', 3));
        $this->assertFalse($arr->includes('c', 100));

        $this->assertTrue($arr->includes('a', -100));
        $this->assertTrue($arr->includes('b', -100));
        $this->assertTrue($arr->includes('c', -100));
        $this->assertFalse($arr->includes('a', -2));
    }

    public function test_extract_values(): void
    {
        $fruits = Arr::make([
            7 => '🥝',
            -1 => '🍓',
            1 => '🍋',
            'mango' => '🥭',
            'apple' => '🍎',
            'banana' => '🍌',
            '🍊',
            '🍍',
        ]);

        $this->assertIsArray($fruits->arr());
        $this->assertEquals(8, $fruits->length());
        $this->assertEquals('🥭', $fruits['mango']);
        $this->assertEquals('🍍', $fruits[9]);

        $onlyFruits = $fruits->values();
        $this->assertIsArray($onlyFruits->arr());
        $this->assertEquals(8, $onlyFruits->length());
        $this->assertEquals('🥭', $onlyFruits[3]);
        $this->assertEquals('🍍', $onlyFruits[7]);
        $i = 0;
        foreach ($fruits->values() as $value) {
            $i++;
        }
        $this->assertEquals(8, $i);
    }

    public function test_creates_entries(): void
    {
        $fruits = Arr::make([
            7 => '🥝',
            -1 => '🍓',
            1 => '🍋',
            'mango' => '🥭',
            'apple' => '🍎',
            'banana' => '🍌',
            '🍊',
            '🍍',
        ]);

        $entries = $fruits->entries();
        $this->assertIsArray($entries->arr());
        $this->assertEquals(8, $entries->length());
        $this->assertEquals(Arr::make([
            [7, '🥝'],
            [-1, '🍓'],
            [1, '🍋'],
            ['mango', '🥭'],
            ['apple', '🍎'],
            ['banana', '🍌'],
            [8, '🍊'],
            [9, '🍍'],
        ]), $entries);
    }

    public function test_copy_within_method_with_one_parameter(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5]);

        $result = $arr->copyWithin(-2);
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals([1, 2, 3, 1, 2], $result);
    }

    public function test_copy_within_method_with_two_parameters(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5]);

        $result = $arr->copyWithin(0, 3);
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals([4, 5, 3, 4, 5], $result);
    }

    public function test_copy_within_method_with_all_the_parameters(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5]);

        $result = $arr->copyWithin(0, 3, 4);
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals([4, 2, 3, 4, 5], $result);
    }

    public function test_copy_within_method_with_all_the_parameters_negative(): void
    {
        $arr = Arr::make([1, 2, 3, 4, 5]);

        $result = $arr->copyWithin(-2, -3, -1);
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals([1, 2, 3, 3, 4], $result);
    }

    public function test_flat_map_can_handle_an_array_of_arrays(): void
    {
        $arr = Arr::make([
            [1, 2],
            [3, 4]
        ]);

        $result = $arr->flatMap(fn ($element): int|float => $element * 2);
        $this->assertEquals(4, $result->length());
        $this->assertEquals([2, 4, 6, 8], $result->arr());
    }

    public function test_can_unset_array_elements_by_their_keys(): void
    {
        $arr = Arr::make([
            'mango' => '🥭',
            'apple' => '🍎',
            'banana' => '🍌'
        ]);

        $arr->unset('apple');
        $this->assertCount(2, $arr);
        $this->assertEquals([
            'mango' => '🥭',
            'banana' => '🍌'
        ], $arr->arr());
        $this->assertFalse($arr->unset('orange'));
        $this->assertCount(2, $arr);
        $this->assertEquals([
            'mango' => '🥭',
            'banana' => '🍌'
        ], $arr->arr());
    }

    public function test_can_set_array_key(): void
    {
        $arr = Arr::make([
            'mango' => '🥭',
            'banana' => '🍌'
        ]);

        $arr->set('apple', '🍎');
        $this->assertCount(3, $arr);
        $this->assertEquals([
            'mango' => '🥭',
            'banana' => '🍌',
            'apple' => '🍎'
        ], $arr->arr());
    }
}
