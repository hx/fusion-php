# Fusion for PHP

Fusion is a bare-bones asset pipelining library for PHP. It offers:

* Simple dependency in which assets can **require** other assets.
* Preprocessing of [CoffeeScript](http://coffeescript.org), [SASS/SCSS](http://sass-lang.com) and [LESS](http://www.lesscss.org) files.
* Compilation of large numbers of files into single files with minification via [UglifyJS](https://github.com/mishoo/UglifyJS2).

## Dependencies

Specify dependencies by **requiring** other files in comments at the tops of your asset files:

```js
/**
 *= require brother.js
 *= require ../uncle/cousin.js
 */
```

You can also require globs of files (note the alternate syntax so that `*/` doesn't break the comment):

```js
//= require_glob=children/*
//= require_glob=children/*/*
```

Generally, you can use either style of comments, as long as the format supports it. For example, vanilla CSS doesn't support single-line `//` comments.

## Processing

```php
<?php
$file = Fusion::file('/path/to/file.scss');
```

This code assigns a new instance of `Fusion\Asset\StyleSheet\Sass` to `$file`, which is a subclass of `Fusion\Asset`. Subclasses are selected based on file extensions.

To specify a base directory, include a second argument. Dependency paths will be evaluated relative to the given base path. The file's directory is used as a base if this argument is omitted.

```php
<?php
$file = Fusion::file('file.scss', '/path/to'); // Equivalent to previous example
```

The `Fusion\Asset` class gives you several basic methods to retrieve information about your file:

```php
<?php

// Test if the represented file exists
echo $file->exists() ? 'found' : 'missing';

// The path of the file, relative to the given base bath
echo $file->path();

// The file's base path
echo $file->basePath();

// The file's absolute path (base + path)
echo $file->absolutePath();

// The file's content type (in this case, "text/css")
echo $file->contentType();
```

You can get the raw, filtered (processed), and compressed (minified) document strings:

```php
<?php

// Raw contents of the file
echo $file->raw();

// Filtered version of the file (processed by Sass, in this case)
echo $file->filtered();

// Filtered AND minified version of the file
echo $file->compressed();
```

### Collections

The above methods are also available on the `Fusion\AssetCollection` class, which represents ordered collections of assets.

Consider these three files `a.coffee`, `b.coffee` and `c.coffee`:

```coffeescript
alert 'File A'
```

```coffeescript
#= require a.coffee
alert 'File B'
```

```coffeescript
#= require b.coffee
alert 'File C'
```

We could create a collection a couple of ways:

```php
<?php

// Manually
$files = new Fusion\AssetCollection([
    Fusion::file('a.coffee'),
    Fusion::file('b.coffee'),
    Fusion::file('c.coffee')
]);

// Using the glob() method
$files = Fusion::glob('*.coffee');

// $files is an instance of Fusion\AssetCollection, which extends ArrayObject
echo count($files);     // 3
echo $files[0]->path(); // a.coffee
```

To get collections of dependencies:
```php
<?php

$file = Fusion::file('c.coffee');

$deps = $file->dependencies();

echo count($deps);     // 2
echo $deps[0]->path(); // a.coffee
echo $deps[1]->path(); // b.coffee

$all = $file->dependenciesAndSelf();

echo count($all);     // 3
echo $all[2]->path(); // c.coffee
```

As illustrated above, the `dependenciesAndSelf()` method returns a collection including the instance on which it was called. This is most useful when combining an asset with its dependencies. Collections support the same output methods as single assets (`raw()`, `filtered()`, and `compressed()`):

```php
<?php
echo $all->compressed();
```

This will output the following combined, minified JavaScript:

```javascript
alert('File A');alert('File B');alert('File C');
```

## Exceptions

Fusion exceptions live in the `Fusion\Exceptions` namespace, and are subclasses of `Fusion\Exception`.

### Processing files

These exceptions may be thrown when processing a file with an external binary using `filtered()` or `compressed()` methods.

#### BadInterpreter

Thrown when an external binary is not found or not executable.

#### SyntaxError

Thrown when an external binary exits with a code other than 0, presumably due to a syntax error in your asset. The exception's message will include the data sent to `STDERR` by the interpreter.

### Collating dependencies

These exceptions may be thrown when attempting to collate a file's dependencies using `dependencies()` or `dependenciesAndSelf()` methods.

#### CircularDependency

Thrown when a file's dependency depends on that file, e.g. if A requires B and B requires A.

#### MissingDependency

Thrown when a dependency specified by `require` doesn't exist.
