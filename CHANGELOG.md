# Changelog

## 0.0.4 - WIP
### Add
- *of()* static method, for creating Arr instance from parameters
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


