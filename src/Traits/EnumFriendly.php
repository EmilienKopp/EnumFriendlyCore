<?php

namespace Splitstack\EnumFriendly\Traits;

use BackedEnum;
use UnitEnum;

/**
 * EnumFriendly Trait
 * 
 * Provides additional functionality for PHP enums including conversion utilities,
 * validation helpers, and Laravel-specific integrations.
 * 
 * @author Emilien Kopp
 * @package Splitstack\EnumFriendly\Traits
 */
trait EnumFriendly
{
  /**
   * Get a human-readable label for the enum case.
   * 
   * Converts the enum case name to a title-cased, space-separated string.
   * For example: 'PENDING_APPROVAL' becomes 'Pending Approval'.
   *
   * @return string The human-readable label
   */
  public function label(): string
  {
    return self::toReadable($this->name);
  }

  /**
   * Coerce a value into an enum instance.
   * 
   * Attempts to convert various input types (strings, integers, or existing enum instances)
   * into a valid enum case. Supports both backed and unbacked enums.
   *
   * @param UnitEnum|BackedEnum|string|int|null $value The value to coerce
   * @return UnitEnum|BackedEnum|null The matching enum case or null if no match found
   * 
   * @example
   * // For a backed enum with string values
   * UserStatus::coerceEnum('active') // Returns UserStatus::ACTIVE
   * 
   * // For an unbacked enum
   * Color::coerceEnum('RED') // Returns Color::RED
   */
  public static function coerceEnum(UnitEnum|BackedEnum|string|int|null $value): UnitEnum|BackedEnum|null
  {
    if ($value instanceof BackedEnum) {
      return $value;
    }
    if ($value instanceof UnitEnum) {
      return $value;
    }
    foreach (self::cases() as $case) {
      // Handle unbacked enums
      if (!property_exists($case, 'value')) {
        if ($case->name === $value) {
          return $case;
        }
        continue;
      }

      if ($case->value === $value) {
        return $case;
      }

    }
    return null;
  }

  /**
   * Coerce a value into its underlying enum value or name.
   * 
   * For backed enums, returns the underlying value. For unbacked enums,
   * returns the case name. Returns null if coercion fails.
   *
   * @param UnitEnum|BackedEnum|string|int|null $value The value to coerce
   * @return string|int|null The underlying value/name or null if coercion fails
   * 
   * @example
   * // For a backed enum
   * UserStatus::coerceValue($option) // Returns 'active' (the enum's value) regardless of $option being a string|int or a case (enum instance)
   * 
   * // For an unbacked enum  
   * Color::coerceValue($option) // Returns 'RED' (the enum's name) regardless of $option being a string or a case (enum instance)
   */
  public static function coerceValue(UnitEnum|BackedEnum|string|int|null $value): string|int|null
  {
    if ($value instanceof BackedEnum) {
      return $value->value;
    }
    if ($value instanceof UnitEnum) {
      return $value->name;
    }
    return self::coerceEnum($value)?->value ?? self::coerceEnum($value)?->name;
  }

  /**
   * Get all enum values as an array.
   * 
   * Returns the underlying values for backed enums, or the case names
   * for unbacked enums.
   *
   * @return array<string|int> Array of enum values or names
   * 
   * @example
   * // For backed enum: UserStatus with values ['active', 'inactive', 'pending']
   * UserStatus::values() // Returns ['active', 'inactive', 'pending']
   * 
   * // For unbacked enum: Color with cases RED, GREEN, BLUE
   * Color::values() // Returns ['RED', 'GREEN', 'BLUE']
   */
  public static function values(): array
  {
    $cases = self::cases();
    $columns = array_column($cases, 'value');
    if (empty($columns)) {
      return array_column(self::cases(), 'name');
    }
    return array_column(self::cases(), 'value');
  }

  /**
   * Join enum values into a string.
   * 
   * Concatenates all enum values using the specified glue string.
   * Useful for creating comma-separated lists or other delimited formats.
   *
   * @param string $glue The string to use as separator between values
   * @return string The imploded string of enum values
   * 
   * @example
   * UserStatus::implode() // Returns 'active,inactive,pending'
   * UserStatus::implode(' | ') // Returns 'active | inactive | pending'
   */
  public static function implode(string $glue = ','): string
  {
    return implode($glue, self::values());
  }

  /**
   * Convert enum cases to select option format.
   * 
   * Creates an array of arrays suitable for HTML select elements,
   * with each option containing value, label, and name properties.
   * The label is a human-readable version of the enum case name.
   *
   * @return array<int, array{value: string|int, label: string, name: string}> 
   *         Array of select options
   * 
   * @example
   * UserStatus::toSelectOptions()
   * // Returns:
   * // [
   * //   ['value' => 'active', 'label' => 'Active', 'name' => 'Active'],
   * //   ['value' => 'inactive', 'label' => 'Inactive', 'name' => 'Inactive']
   * // ]
   */
  public static function toOptionsArray(): array
  {
    $options = [];
    foreach (self::cases() as $case) {
      $name = $case->name;
      $value = property_exists($case, 'value') ? $case->value : $case->name;
      $readable = self::toReadable($name);
      
      $options[] = [
        'value' => $value,
        'label' => $readable,
        'name' => $readable,
      ];
    }
    return $options;
  }

  /**
   * Get all enum case names as an array.
   * 
   * Returns the names (identifiers) of all enum cases, regardless of
   * whether the enum is backed or unbacked.
   *
   * @return array<string> Array of enum case names
   * 
   * @example
   * // For any enum with cases ACTIVE, INACTIVE, PENDING
   * MyEnum::keys() // Returns ['ACTIVE', 'INACTIVE', 'PENDING']
   */
  public static function keys(): array
  {
    return array_map(function ($case) {
      return $case->name;
    }, self::cases());
  }

  /**
   * Get human-readable labels for all enum cases.
   * 
   * Converts all enum case names to title-cased, space-separated strings.
   * Maps over the keys and applies the toReadable transformation.
   *
   * @return array<string> Array of human-readable enum labels
   * 
   * @example
   * // For enum with cases PENDING_APPROVAL, ACTIVE, REJECTED
   * MyEnum::readable() // Returns ['Pending Approval', 'Active', 'Rejected']
   */
  public static function readable(): array
  {
    return array_map(function ($key) {
      return self::toReadable($key);
    }, self::keys());
  }

  /**
   * Get a random enum value.
   * 
   * Returns a randomly selected value from the enum's available values.
   * Uses PHP's array_rand() for randomization.
   *
   * @return string|int A random enum value
   * 
   * @example
   * UserStatus::random() // Returns one of: 'active', 'inactive', 'pending'
   */
  public static function random(): string|int
  {
    $values = self::values();
    return $values[array_rand($values)];
  }

  /**
   * Convert enum case name to human-readable format.
   * 
   * Transforms snake_case or SCREAMING_SNAKE_CASE enum names into
   * title-cased, space-separated strings for display purposes.
   *
   * @param string $key The enum case name to convert
   * @return string The human-readable string
   * 
   * @example
   * toReadable('PENDING_APPROVAL') // Returns 'Pending Approval'
   * toReadable('user_active') // Returns 'User Active'
   */
  private static function toReadable(string $key): string
  {
    return ucwords(str_replace('_', ' ', strtolower($key)));
  }

  /**
   * Export enum data in TypeScript-friendly format.
   * 
   * Creates an array containing the enum type name and its values,
   * suitable for generating TypeScript definitions or frontend integration.
   *
   * @return array{type: string, values: array<string|int>} TypeScript-compatible enum data
   * 
   * @example
   * UserStatus::toTypeScript()
   * // Returns ['type' => 'UserStatus', 'values' => ['active', 'inactive', 'pending']]
   */
  public static function toTypeScript(): array
  {
    return [
      'type' => explode('\\', self::class)[count(explode('\\', self::class)) - 1],
      'values' => self::values(),
    ];
  }

  /**
   * Generate a descriptive comment listing all enum values.
   * 
   * Creates a formatted string that lists all possible enum values,
   * useful for database column comments, API documentation, or code comments.
   *
   * @param string $prefix Text to prepend to the values list
   * @return string Formatted comment string with all enum values
   * 
   * @example
   * UserStatus::comment() // Returns 'possible values: active, inactive, pending'
   * UserStatus::comment('Valid statuses: ') // Returns 'Valid statuses: active, inactive, pending'
   */
  public static function comment(string $prefix = 'possible values: '): string
  {
    return $prefix . self::implode(', ');
  }

  /**
   * Convert enum cases to an associative array of value => readable label.
   * 
   * Creates a key-value mapping where the keys are the enum values (or names for unbacked enums)
   * and the values are human-readable labels converted from value (for backed enums) or case name (for unbacked).
   *
   * @return array<string|int, string> Associative array mapping enum values to readable labels
   * 
   * @example
   * // For UserStatus enum with cases PENDING_APPROVAL, ACTIVE, INACTIVE
   * UserStatus::toReadableArray() 
   * // Returns ['pending_approval' => 'Pending Approval', 'active' => 'Active', 'inactive' => 'Inactive']
   */
  public static function toReadableArray(): array
  {
    $result = [];
    foreach (self::cases() as $case) {
      $value = property_exists($case, 'value') ? $case->value : $case->name;
      $result[$value] = self::toReadable($case->name);
    }
    return $result;
  }

  /**
   * Get enum as associative array [value => label].
   * 
   * Creates a key-value mapping where the keys are the enum values (or names for unbacked enums)
   * and the values are the original case names (not human-readable).
   *
   * @return array<string|int, string> Associative array mapping enum values to case names
   * 
   * @example
   * // For UserStatus enum with cases PENDING_APPROVAL, ACTIVE, INACTIVE
   * UserStatus::toArray() 
   * // Returns ['pending_approval' => 'PENDING_APPROVAL', 'active' => 'ACTIVE', 'inactive' => 'INACTIVE']
   */
  public static function toArray(): array
  {
    $result = [];
    foreach (self::cases() as $case) {
      $value = property_exists($case, 'value') ? $case->value : $case->name;
      $result[$value] = $case->name;
    }
    return $result;
  }

  /**
   * Returns an array of stringified enum names => values for JSON serialization.
   * 
   * This method is intended to provide a JSON-friendly representation of the enum,
   * mapping each enum case name to its corresponding value (or name for unbacked enums).
   * 
   * @return array<string, string|int> Associative array of enum names to values
   */
  public static function toJsonArray(): array
  {
    $result = [];
    foreach (self::cases() as $case) {
      $value = property_exists($case, 'value') ? $case->value : $case->name;
      $result[$case->name] = $value;
    }
    return $result;
  }

  /**
   * Get a random enum case instance.
   * 
   * Returns a randomly selected enum case instance (not just the value).
   * Useful when you need the full enum object rather than just its value.
   *
   * @return static A random enum case instance
   * 
   * @example
   * $randomStatus = UserStatus::randomCase(); // Returns UserStatus::ACTIVE, UserStatus::INACTIVE, etc.
   * $randomStatus->label(); // Uses the enum instance methods
   */
  public static function randomCase(): self
  {
    $cases = self::cases();
    return $cases[array_rand($cases)];
  }

  /**
   * Try to get an enum case from a value, with a fallback.
   * 
   * Attempts to coerce the given value into an enum case. If the coercion fails,
   * returns the provided default enum case instead of null.
   *
   * @param string|int $value The value to attempt to coerce
   * @param static $default The fallback enum case to return if coercion fails
   * @return static The coerced enum case or the default
   * 
   * @example
   * $status = UserStatus::fromValueOr('invalid', UserStatus::PENDING);
   * // Returns UserStatus::PENDING since 'invalid' is not a valid status
   */
  public static function fromValueOr(string|int $value, self $default): self
  {
    return self::coerceEnum($value) ?? $default;
  }

  /**
   * Check if a value exists in the enum.
   * 
   * Determines whether the given value matches any of the enum's values.
   * Supports both strict and loose comparison modes.
   *
   * @param string|int $value The value to check for existence
   * @param bool $strict Whether to use strict comparison (default: true)
   * @return bool True if the value exists in the enum, false otherwise
   * 
   * @example
   * UserStatus::hasValue('active') // Returns true if 'active' is a valid status
   * UserStatus::hasValue('invalid') // Returns false
   * UserStatus::hasValue(1, false) // Loose comparison, might match '1' string
   */
  public static function hasValue(string|int $value, bool $strict = true): bool
  {
    return in_array($value, self::values(), $strict);
  }

  /**
   * Get only specific enum cases by their names.
   * 
   * Filters the enum cases to return only those whose names are included
   * in the provided array. Useful for creating subsets of enum cases.
   *
   * @param array<string> $cases Array of case names to include
   * @return array<static> Array of filtered enum cases
   * 
   * @example
   * // Get only active and pending statuses
   * $activeStatuses = UserStatus::only(['ACTIVE', 'PENDING']);
   */
  public static function only(array $cases): array
  {
    return array_filter(self::cases(), fn($case) => in_array($case->name, $cases));
  }

  /**
   * Get only specific enum values by filtering.
   * 
   * Filters the enum values to return only those that are included
   * in the provided array. Supports both strict and loose comparison.
   *
   * @param array<string|int> $values Array of values to include
   * @param bool $strict Whether to use strict comparison (default: true)
   * @return array<string|int> Array of filtered enum values
   * 
   * @example
   * // Get only specific values
   * $specificValues = UserStatus::onlyValues(['active', 'pending']);
   * // Returns ['active', 'pending'] (only the values that exist in the enum)
   */
  public static function onlyValues(array $values, bool $strict = true): array
  {
    $enumValues = self::values();
    
    if ($strict) {
      return array_filter($enumValues, fn($enumValue) => in_array($enumValue, $values, true));
    }
    
    return array_intersect($enumValues, $values);
  }

  /**
   * Get all enum cases except specific ones by their names.
   * 
   * Filters the enum cases to exclude those whose names are included
   * in the provided array. Returns all other cases.
   *
   * @param array<string> $cases Array of case names to exclude
   * @return array<static> Array of remaining enum cases
   * 
   * @example
   * // Get all statuses except inactive ones
   * $activeStatuses = UserStatus::except(['INACTIVE', 'DELETED']);
   */
  public static function except(array $cases): array
  {
    return array_filter(self::cases(), fn($case) => !in_array($case->name, $cases));
  }

  /**
   * Get all enum values except specific ones.
   * 
   * Filters the enum values to exclude those that are included
   * in the provided array. Supports both strict and loose comparison.
   *
   * @param array<string|int> $values Array of values to exclude
   * @param bool $strict Whether to use strict comparison (default: true)
   * @return array<string|int> Array of remaining enum values
   * 
   * @example
   * // Get all values except specific ones
   * $allowedValues = UserStatus::exceptValues(['inactive', 'deleted']);
   * // Returns ['active', 'pending'] (all values except the excluded ones)
   */
  public static function exceptValues(array $values, bool $strict = true): array
  {
    $enumValues = self::values();
    
    if ($strict) {
      return array_filter($enumValues, fn($enumValue) => !in_array($enumValue, $values, true));
    }
    
    return array_diff($enumValues, $values);
  }

  /**
   * Get the total number of enum cases.
   * 
   * Returns the count of all available enum cases.
   * Useful for validation, statistics, or conditional logic.
   *
   * @return int The number of enum cases
   * 
   * @example
   * UserStatus::count() // Returns 3 if there are 3 enum cases
   */
  public static function count(): int
  {
    return count(self::cases());
  }

  /**
   * Convert to JSON-friendly format
   * 
   * Returns a JSON string representing the enum as an associative array
   * of value => name pairs.
   * 
   * @param int $options JSON encoding options
   * @return string The JSON-encoded enum representation
   * 
   * @example
   * UserStatus::toJson() // Returns '{"active":"ACTIVE","inactive":"INACTIVE","pending":"PENDING"}'
   */
  public static function toJson(int $options = 0): string
  {
    return json_encode(self::toArray(), $options);
  }

  /**
   * Check if the enum is backed (has underlying values).
   * 
   * Determines whether the enum is a backed enum (extends BackedEnum)
   * or an unbacked enum (only extends UnitEnum).
   *
   * @return bool True if the enum is backed, false if it's unbacked
   * 
   * @example
   * // For a backed enum like: enum Status: string { case ACTIVE = 'active'; }
   * Status::isBacked() // Returns true
   * 
   * // For an unbacked enum like: enum Color { case RED; case BLUE; }
   * Color::isBacked() // Returns false
   */
  public static function isBacked(): bool
  {
    return is_subclass_of(self::class, BackedEnum::class);
  }

  /**
   * Get a description for the enum case.
   * 
   * Returns a description if the enum case implements a getDescription() method,
   * otherwise returns null. This allows for optional extended descriptions
   * beyond the basic label functionality.
   *
   * @return string|null The description text or null if not available
   * 
   * @example
   * // If your enum case has: public function getDescription() { return 'User is active'; }
   * $status = UserStatus::ACTIVE;
   * $status->description() // Returns 'User is active' or null
   */
  public function description(): ?string
  {
    return method_exists($this, 'getDescription') ? $this->getDescription() : null;
  }

  /**
   * Compare the current enum case with another value.
   * 
   * Checks if the current enum case is equal to the provided value.
   * Handles comparison with other enum instances, strings, or integers
   * by using the coerceEnum method for type conversion.
   *
   * @param mixed $value The value to compare against
   * @return bool True if the values are equal, false otherwise
   * 
   * @example
   * $status = UserStatus::ACTIVE;
   * $status->is(UserStatus::ACTIVE) // Returns true
   * $status->is('active') // Returns true (if 'active' is the enum's value)
   * $status->is('inactive') // Returns false
   */
  public function is(mixed $value): bool
  {
    if ($value instanceof self) {
      return $this === $value;
    }
    return $this === self::coerceEnum($value);
  }

  /**
   * Check if the current enum case matches any of the given values.
   * 
   * Iterates through an array of values and returns true if the current
   * enum case matches any of them. Uses the is() method for comparison.
   *
   * @param array<mixed> $values Array of values to check against
   * @return bool True if the enum case matches any of the values, false otherwise
   * 
   * @example
   * $status = UserStatus::ACTIVE;
   * $status->in([UserStatus::ACTIVE, UserStatus::PENDING]) // Returns true
   * $status->in(['active', 'pending']) // Returns true (if values match)
   * $status->in([UserStatus::INACTIVE]) // Returns false
   */
  public function in(array $values): bool
  {
    foreach ($values as $value) {
      if ($this->is($value)) {
        return true;
      }
    }
    return false;
  }
}
