# EnumFriendly Core

![Tests](https://img.shields.io/github/actions/workflow/status/emilienkopp/EnumFriendly/tests.yml?label=tests)
<!-- [![Coverage Status](https://img.shields.io/coveralls/github/emilienkopp/EnumFriendly/main.svg?style=flat-square)](https://coveralls.io/github/emilienkopp/EnumFriendly?branch=main) -->
![PHP Version](https://img.shields.io/badge/php-^8.1-blue.svg?style=flat-square)
![Framework Agnostic](https://img.shields.io/badge/framework-agnostic-green.svg?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/splitstack/enum-friendly-core.svg?style=flat-square)](https://packagist.org/packages/splitstack/enum-friendly-core)

## Introduction

EnumFriendly Core is a powerful, **dependency-free** PHP package that enhances your enum experience across any PHP 8.1+ project. It provides a comprehensive set of utilities for working with enums, making them more versatile and easier to integrate with TypeScript, forms, APIs, and more.

**🚀 Key Features:**
- **Zero Dependencies** - Works with any PHP 8.1+ project, no framework required
- **Framework Agnostic** - Use with Laravel, Symfony, vanilla PHP, or any other framework
- **Comprehensive Enum Utilities** - Over 20 helpful methods for enum manipulation
- **TypeScript Integration** - Generate TypeScript-compatible type definitions
- **Developer Friendly** - Intuitive API with extensive documentation

With EnumFriendly Core, you can:
- Convert enum values to human-readable labels
- Generate TypeScript-compatible type definitions
- Create form-friendly select options  
- Get random enum values for testing
- Safely coerce values to enum instances
- Check enum membership and filter cases
- And much more!

## Installation

You can install the package via composer:

```bash
composer require splitstack/enum-friendly-core
```

That's it! No configuration needed - the package is ready to use immediately.

## Usage

### Adding the EnumFriendly Trait

Simply add the `EnumFriendly` trait to your existing enums to unlock all the enhanced functionality:

```php
<?php

use Splitstack\EnumFriendly\Traits\EnumFriendly;

enum UserStatus: string
{
    use EnumFriendly;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
}

enum Priority: int
{
    use EnumFriendly;

    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case CRITICAL = 4;
}

enum Color
{
    use EnumFriendly;

    case RED;
    case GREEN;
    case BLUE;
}
```

### Using the EnumFriendly Trait

The `EnumFriendly` trait provides additional methods for your enums (string-backed, int-backed, or unbacked):

```php
use Splitstack\EnumFriendly\Traits\EnumFriendly;

enum MyEnum: string
{
  use EnumFriendly;

  case ADMIN = 'admin';
  case USER = 'user';
}
```

#### Available Methods

| Method | Description | Example Output |
|--------|-------------|----------------|
| `values()` | Get all enum values (or names for unbacked) | `['active', 'inactive']` |
| `keys()` | Get all enum case names | `['ACTIVE', 'INACTIVE']` |
| `readable()` | Get case names in human-readable format | `['Active', 'Inactive']` |
| `implode(string $glue = ',')` | Implode the enum values | `'active,inactive'` |
| `toOptionsArray()` | Get enum as form select options array | `[['value' => 'active', 'label' => 'Active'], ...]` |
| `toreadable()` | Get enum as value => readable label mapping | `['active' => 'Active', 'inactive' => 'Inactive']` |
| `toArray()` | Get enum as value => case name mapping | `['active' => 'ACTIVE', 'inactive' => 'INACTIVE']` |
| `toJsonArray()` | Get enum as case name => value mapping | `['ACTIVE' => 'active', 'INACTIVE' => 'inactive']` |
| `random()` | Get a random enum value | `'active'` or `'inactive'` |
| `randomCase()` | Get a random enum case instance | `MyEnum::ACTIVE` or `MyEnum::INACTIVE` |
| `coerceEnum($value)` | Safely convert value to enum instance | `MyEnum::ACTIVE` or `null` |
| `coerceValue($value)` | Safely convert value to enum value | `'active'` or `null` |
| `hasValue($value)` | Check if value exists in enum | `true` or `false` |
| `only($cases)` | Filter enum cases by names | `[MyEnum::ACTIVE]` |
| `onlyValues($values)` | Filter enum cases by values | `[MyEnum::ACTIVE]` |
| `except($cases)` | Exclude enum cases by names | `[MyEnum::INACTIVE]` |
| `exceptValues($values)` | Exclude enum cases by values | `[MyEnum::INACTIVE]` |
| `count()` | Get total number of enum cases | `2` |
| `isBacked()` | Check if enum is backed | `true` or `false` |
| `toTypeScript()` | Make enum TypeScript-friendly | `['type' => 'MyEnum', 'values' => [...]]` |
| `comment($prefix)` | Generate descriptive comment | `'possible values: active, inactive'` |
| `toJson($options)` | Convert to JSON string | `'{"active":"ACTIVE","inactive":"INACTIVE"}'` |
| `label()` | Get human-readable label for instance | `'Active'` (when called on enum instance) |
| `description()` | Get description if implemented | Custom description or `null` |
| `is($value)` | Compare enum instance with value | `true` or `false` |
| `in($values)` | Check if enum instance is in array | `true` or `false` |

### TypeScript Integration

Generate TypeScript-compatible type definitions:

```php
UserStatus::toTypeScript();
// Returns:
// [
//   'type' => 'UserStatus',
//   'values' => ['active', 'inactive', 'pending', 'suspended']
// ]
```

### Form Integration

Create select options for your forms easily:

```php
UserStatus::toOptionsArray();
// Returns:
// [
//   ['value' => 'active', 'label' => 'Active', 'name' => 'Active'],
//   ['value' => 'inactive', 'label' => 'Inactive', 'name' => 'Inactive'],
//   ['value' => 'pending', 'label' => 'Pending', 'name' => 'Pending'],
//   ['value' => 'suspended', 'label' => 'Suspended', 'name' => 'Suspended']
// ]
```

### Safe Value Coercion

Safely convert unknown values to enum instances:

```php
// Safe conversion - returns enum instance or null
$status = UserStatus::coerceEnum('active'); // Returns UserStatus::ACTIVE
$invalid = UserStatus::coerceEnum('invalid'); // Returns null

// With fallback
$status = UserStatus::fromValueOr('invalid', UserStatus::PENDING); // Returns UserStatus::PENDING

// Check if value exists
UserStatus::hasValue('active'); // Returns true
UserStatus::hasValue('invalid'); // Returns false
```

### Working with Collections

Filter and manipulate enum cases:

```php
// Get only specific cases
$activeCases = UserStatus::only(['ACTIVE', 'PENDING']);

// Exclude specific cases  
$nonSuspendedCases = UserStatus::except(['SUSPENDED']);

// Filter by values
$validStatuses = UserStatus::onlyValues(['active', 'pending']);

// Get random values for testing
$randomStatus = UserStatus::random(); // Returns a random value
$randomCase = UserStatus::randomCase(); // Returns a random enum instance
```

## Why Choose EnumFriendly Core?

### 🚀 **Zero Dependencies**
Unlike other enum packages, EnumFriendly Core has **zero dependencies**. It works with any PHP 8.1+ project without requiring Laravel, Symfony, or any other framework.

### 🔧 **Framework Agnostic**
Use it anywhere:
- **Laravel/Symfony** applications
- **API-only** projects  
- **Legacy PHP** codebases
- **Microservices**
- **CLI tools**

### 📦 **Lightweight & Fast**
- Minimal footprint in your project
- No heavy framework dependencies to slow down your application
- Pure PHP implementation optimized for performance

### 🎯 **Developer Experience**
- **Intuitive API** - Methods named exactly what they do
- **Comprehensive documentation** - Every method documented with examples
- **IDE friendly** - Full type hints and auto-completion
- **Extensive test coverage** - 25+ tests covering all functionality

### 🔄 **Migration Friendly**
Easy to integrate into existing projects:
```php
// Before
$values = array_column(MyEnum::cases(), 'value');

// After  
$values = MyEnum::values();
```

## Testing

Run the test suite:

```bash
composer test
```

Generate test coverage report:

```bash
composer test:coverage
```

## Contributing

Contributions are welcome! Feel free to:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [EmilienKopp](https://github.com/emilienkopp)
- [All Contributors](../../contributors)
