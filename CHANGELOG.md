# Changelog

## 0.0.10 - WIP
From Hacktoberfest:
- Test coverage at 100%
- Arr::isEmpty() methods thanks to @Tautve for the implementation and @RoadSigns for the review
- Allow Arr::flatMap() to support multidimensional arrays by @RoadSigns
- Add toLocaleString() to get the string representation of the elements in the array

## 0.0.9 - 2022-10-02
From Hacktoberfest:
- Arr::find() method that returns the first element in the array that satisfies the testing function by @tombenevides
- Arr::copyWithin() method that copies part of the array to a location but keeps the original length by @RoadSigns
- Improved code style by adding missing return types, removing unused variables, improving phpdocs, removing default values by @Tautve
- PHP Stan level 5

## 0.0.8 - 2022-10-01
- not equal operator for where method
- Table::except() method to exclude some data
- Table::groupThenApply() method to apply aggregate functions

From Hacktoberfest:
- Add Pint as style checker by @tharun634
- Arr::from() method for creating new Arr from a string or array-like object by @nuernbergerA
- Arr::findIndex() method for finding the index of some element by @martijnengler
- Arr::entries() method returns a new Arr object that contains the key/value pairs for each index in the array by @LeoVie
- Arr::find() method for finding some element by @tombenevides


## 0.0.7 - 2022-04-25
- Review where method, where(price, '>', 100)

## 0.0.6 - 2022-04-24
- Creating Table class
- updating composer.json removing unused package

## 0.0.5 - 2022-01-22
### Add
- *includes()* method;
- *values()* method extracts values from current Arr and creates new one (without keys and generating numeric keys starting from 0).

## 0.0.4 - 2021-12-28
### Add
- *of()* static method, for creating Arr instance from parameters;
- *keys()* method for extracting keys from Arr;
- *at()* method for returning the item at that integer index;
- *sum()* method;
- *avg()* method;
- *doc/arr.md*: class usage documentation.

### Improve
- Improve concat() method, now accept array and scalar values.

## 0.0.3 - 2021-12-19
- Now, Arr implements "ArrayAccess"

## 0.0.2 - 2021-12-14
### Add
- fromFunction() method for creating new Arr from a assignment function;
- fromValue() method for creating new Arr from a value;

## 0.0.1 - 2021-12-12
Initial release with some Arr methods:
- make() create array;
- length(): length/size of the array;
- arr(): returns data with the type PHP array
- get(): get the element by index
- Iterator methods: current(), next(), prev(), key(), valid(), rewind()
- forEach(): execute a function for each element;
- push(): add new element (at the end);
- pop(): remove an element (at the end);
- unshift(): add new element at the start;
- shift(): remove an element from the start;
- append(): append arrays to the current one;
- concat(): return new array joining more arrays;
- join(): joins all elements into a string;
- slice(): returns a sub array;
- indexOf(): find the first occurrence;
- lastIndexOf(): find the last occurrence;
- every(): all elements match a fn();
- some(): at least one element matches a fn();
- filter(): filter elements by a fn();
- map(): apply a fn() for each element;
- flat(): flat an array of arrays;
- flatMap(): map() and flat();
- fill(): fill an array (or a piece of an array);
- reduce(): calculate a fn() with the array as input;
- reduceRight(): like reduce(), but parsing the array in reverse order;
- reverse(): reverse the array;
- sort(): sort the array;
- splice(): changes content of arr removing, replacing and adding elements;
- toString(): the string representing the array (same as join(','));
- isArray(): check if the input is an array.


