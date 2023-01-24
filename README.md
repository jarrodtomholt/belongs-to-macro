# Laravel BelongsTo Macro

Automatically add index and foreign key constraints to your migrations enforcing data integrity, 
preserving the referential association and improving query performance.

## Installation

You can install the package via composer:

```bash
composer require jarrodtomholt/belongs-to-macro
```

## Usage

In a migrations `up()` method, use the following in place of `foreignIdFor` to add
indexes and foreign key constraints.

```php
Schema::create('posts', function (Blueprint $table) {
    $table->belongsTo(User::class);
});

// will expand to
$table->foreignIdFor(User::class)->index()->constrained();
```

Need a nullable field? use `belongsToOrNull`

```php
Schema::create('posts', function (Blueprint $table) {
    $table->belongsToOrNull(User::class);
});

// will expand to
$table->foreignIdFor(User::class)->nullable()->index()->constrained();
```

### Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please raise an issue using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
