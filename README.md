<p align="center">
  <img src="https://static.igorora-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/igororaproject/Iterator"><img src="https://img.shields.io/travis/igororaproject/Iterator/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/igororaproject/Iterator?branch=master"><img src="https://img.shields.io/coveralls/igororaproject/Iterator/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/igorora/iterator"><img src="https://img.shields.io/packagist/dt/igorora/iterator.svg" alt="Packagist" /></a>
  <a href="https://igorora-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/igorora/iterator.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# igorora\Iterator

[![Help on IRC](https://img.shields.io/badge/help-%23igororaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#igororaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/igororaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.igorora-project.net/Documentation/Library/Iterator)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/igororaproject/iterator)

This library provides a set of useful iterator (compatible with PHP iterators).
Existing PHP iterators have been updated to get new features and prior PHP
versions compatibility.

[Learn more](https://central.igorora-project.net/Documentation/Library/Iterator).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`igorora/iterator`](https://packagist.org/packages/igorora/iterator):

```sh
$ composer require igorora/iterator '~2.0'
```

For more installation procedures, please read [the Source
page](https://igorora-project.net/Source.html).

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/igorora test:run
```

For more information, please read the [contributor
guide](https://igorora-project.net/Literature/Contributor/Guide.html).

## Quick usage

We propose a quick overview of all iterators.

### The One

`igorora\Iterator\Iterator` defines the basis of an iterator. It extends
[`Iterator`](http://php.net/class.iterator).

### External iterator

`igorora\Iterator\Aggregate` allows a class to use an external iterator through the
`getIterator` method. It extends
[`IteratorAggregate`](http://php.net/iteratoraggregate)

### Traversable to iterator

`igorora\Iterator\IteratorIterator` transforms anything that is
[traversable](http://php.net/traversable) into an iterator. It extends
[`IteratorIterator`](http://php.net/iteratoriterator).

### Iterator of iterators

`igorora\Iterator\Outer` represents an iterator that iterates over iterators. It
extends [`OuterIterator`](http://php.net/outeriterator).

### Mock

`igorora\Iterator\Mock` represents an empty iterator. It extends
[`EmptyIterator`](http://php.net/emptyiterator).

### Seekable

`igorora\Iterator\Seekable` represents an iterator that can be seeked. It extends
[`SeekableIterator`](http://php.net/seekableiterator).

### Map

`igorora\Iterator\Map` allows to iterate an array. It extends
[`ArrayIterator`](http://php.net/arrayiterator).

```php
$foobar = new igorora\Iterator\Map(['f', 'o', 'o', 'b', 'a', 'r']);

foreach ($foobar as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobar
 */
```

### Filters

`igorora\Iterator\Filter` and `igorora\Iterator\CallbackFilter` allows to filter the
content of an iterator. It extends
[`FilterIterator`](http://php.net/filteriterator) and
[`CallbackFilterIterator`](http://php.net/callbackfilteriterator).

```php
$filter = new igorora\Iterator\CallbackFilter(
    $foobar,
    function ($value, $key, $iterator) {
        return false === in_array($value, ['a', 'e', 'i', 'o', 'u']);
    }
);

foreach ($filter as $value) {
    echo $value;
}

/**
 * Will output:
 *     fbr
 */
```

Also, `igorora\Iterator\RegularExpression` allows to filter based on a regular
expression.

### Limit

`igorora\Iterator\Limit` allows to iterate *n* elements of an iterator starting from
a specific offset. It extends [`LimitIterator`](http://php.net/limititerator).

```php
$limit = new igorora\Iterator\Limit($foobar, 2, 3);

foreach ($limit as $value) {
    echo $value;
}

/**
 * Will output:
 *     oba
 */
```

### Infinity

`igorora\Iterator\Infinite` allows to iterate over and over again the same iterator.
It extends [`InfiniteIterator`](http://php.net/infiniteiterator).

```php
$infinite = new igorora\Iterator\Infinite($foobar);
$limit    = new igorora\Iterator\Limit($infinite, 0, 21);

foreach ($limit as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobarfoobarfoobarfoo
 */
```

Also, `igorora\Iterator\NoRewind` is an iterator that does not rewind. It extends
[`NoRewindIterator`](http://php.net/norewinditerator).

### Repeater

`igorora\Iterator\Repeater` allows to repeat an iterator *n* times.

```php
$repeater = new igorora\Iterator\Repeater(
    $foobar,
    3,
    function ($i) {
        echo "\n";
    }
);

foreach ($repeater as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobar
 *     foobar
 *     foobar
 */
```

### Counter

`igorora\Iterator\Counter` is equivalent to a `for($i = $from, $i < $to, $i +=
$step)` loop.

```php
$counter = new igorora\Iterator\Counter(0, 12, 3);

foreach ($counter as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 3 6 9
 */
```

### Union of iterators

`igorora\Iterator\Append` allows to iterate over iterators one after another. It
extends [`AppendIterator`](http://php.net/appenditerator).

```php
$counter1 = new igorora\Iterator\Counter(0, 12, 3);
$counter2 = new igorora\Iterator\Counter(13, 23, 2);
$append   = new igorora\Iterator\Append();
$append->append($counter1);
$append->append($counter2);

foreach ($append as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 3 6 9 13 15 17 19 21 
 */
```

### Multiple

`igorora\Iterator\Multiple` allows to iterate over several iterator at the same
times. It extends [`MultipleIterator`](http://php.net/multipleiterator).

```php
$foobar   = new igorora\Iterator\Map(['f', 'o', 'o', 'b', 'a', 'r']);
$baz      = new igorora\Iterator\Map(['b', 'a', 'z']);
$multiple = new igorora\Iterator\Multiple(
    igorora\Iterator\Multiple::MIT_NEED_ANY
  | igorora\Iterator\Multiple::MIT_KEYS_ASSOC
);
$multiple->attachIterator($foobar, 'one', '!');
$multiple->attachIterator($baz,    'two', '?');

foreach ($multiple as $iterators) {
    echo $iterators['one'], ' | ', $iterators['two'], "\n";
}

/**
 * Will output:
 *     f | b
 *     o | a
 *     o | z
 *     b | ?
 *     a | ?
 *     r | ?
 */
```

### Demultiplexer

`igorora\Iterator\Demultiplexer` demuxes result from another iterator. This iterator
is somehow the opposite of the `igorora\Iterator\Multiple` iterator.

```php
$counter  = new igorora\Iterator\Counter(0, 10, 1);
$multiple = new igorora\Iterator\Multiple();
$multiple->attachIterator($counter);
$multiple->attachIterator(clone $counter);
$demultiplexer = new igorora\Iterator\Demultiplexer(
    $multiple,
    function ($current) {
        return $current[0] * $current[1];
    }
);

foreach ($demultiplexer as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 1 4 9 16 25 36 49 64 81 
 */
```

### File system

`igorora\Iterator\Directory` and `igorora\Iterator\FileSystem` allow to iterate the file
system where files are represented by instances of `igorora\Iterator\SplFileInfo`.
They respectively extend
[`DirectoryIterator`](http://php.net/directoryiterator),
[`FilesystemIterator`](http://php.net/filesystemiterator) and
[`SplFileInfo`](http://php.net/splfileinfo).

```php
$directory = new igorora\Iterator\Directory(resolve('igorora://Library/Iterator'));

foreach ($directory as $value) {
    echo $value->getFilename(), "\n";
}

/**
 * Will output:
 *     .
 *     ..
 *     .State
 *     Aggregate.php
 *     Append.php
 *     CallbackFilter.php
 *     composer.json
 *     Counter.php
 *     Demultiplexer.php
 *     …
 */
```

Also, the `igorora\Iterator\Glob` allows to iterator with the glob strategy. It
extends [`GlobIterator`](http://php.net/globiterator). Thus:

```php
$glob = new igorora\Iterator\Glob(resolve('igorora://Library/Iterator') . '/M*.php');

foreach ($glob as $value) {
    echo $value->getFilename(), "\n";
}

/**
 * Will output:
 *     Map.php
 *     Mock.php
 *     Multiple.php
 */
```

### Look ahead

`igorora\Iterator\Lookahead` allows to look ahead for the next element:

```php
$counter   = new igorora\Iterator\Counter(0, 5, 1);
$lookahead = new igorora\Iterator\Lookahead($counter);

foreach ($lookahead as $value) {
    echo $value;

    if (true === $lookahead->hasNext()) {
        echo ' (next: ', $lookahead->getNext(), ')';
    }

    echo "\n";
}

/**
 * Will output:
 *     0 (next: 1)
 *     1 (next: 2)
 *     2 (next: 3)
 *     3 (next: 4)
 *     4
 */
```

The `igorora\Iterator\Lookbehind` also exists and allows to look behind for the
previous element.

### Buffer

`igorora\Iterator\Buffer` allows to move forward as usual but also backward up to a
given buffer size over another iterator:

```php
$abcde  = new igorora\Iterator\Map(['a', 'b', 'c', 'd', 'e']);
$buffer = new igorora\Iterator\Buffer($abcde, 3);

$buffer->rewind();
echo $buffer->current(); // a

$buffer->next();
echo $buffer->current(); // b

$buffer->next();
echo $buffer->current(); // c

$buffer->previous();
echo $buffer->current(); // b

$buffer->previous();
echo $buffer->current(); // a

$buffer->next();
echo $buffer->current(); // b

/**
 * Will output:
 *     abcbab
 */
```

### Callback generator

`igorora\Iterator\CallbackGenerator` allows to transform any callable into an
iterator. This is very useful when combined with other iterators, for instance
with `igorora\Iterator\Limit`:

```php
$generator = new igorora\Iterator\CallbackGenerator(function ($key) {
    return mt_rand($key, $key * 2);
});
$limit = new igorora\Iterator\Limit($generator, 0, 10);

foreach ($limit as $value) {
    echo $value, ' ';
}

/**
 * Could output:
 *     0 2 3 4 4 7 8 10 12 18 
 */
```

### Recursive iterators

A recursive iterator is an iterator where its values is other iterators. The
most important interface is `igorora\Iterator\Recursive\Recursive` (it extends
[`RecursiveIterator`](http://php.net/recursiveiterator)). Then we find (in
alphabetic order):

  * `igorora\Iterator\Recursive\CallbackFilter` (it extends
    [`RecursiveCallbackFilterIterator`](http://php.net/recursivecallbackfilteriterator)),
  * `igorora\Iterator\Recursive\Directory` (it extends
    [`RecursiveDirectoryIterator`](http://php.net/recursivedirectoryiterator)),
  * `igorora\Iterator\Recursive\Filter` (it extends
    [`RecursiveFilterIterator`](http://php.net/recursivefilteriterator)),
  * `igorora\Iterator\Recursive\Iterator` (it extends
    [`RecursiveIteratorIterator`](http://php.net/recursiveiteratoriterator)),
  * `igorora\Iterator\Recursive\Map` (it extends
    [`RecursiveArrayIterator`](http://php.net/recursivearrayiterator)),
  * `igorora\Iterator\Recursive\Mock`,
  * `igorora\Iterator\Recursive\RegularExpression`
    (it extends [`RecursiveRegularExpression`](http://php.net/recursiveregexiterator)).

## Documentation

The
[hack book of `igorora\Iterator`](https://central.igorora-project.net/Documentation/Library/Iterator) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev igorora/devtools
$ vendor/bin/igorora devtools:documentation --open
```

More documentation can be found on the project's website:
[igorora-project.net](https://igorora-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#igororaproject`](https://webchat.freenode.net/?channels=#igororaproject)
    IRC channel,
  * On the forum at [users.igorora-project.net](https://users.igorora-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://igorora-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://igorora-project.net/LICENSE) for details.
