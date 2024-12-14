# [`MockeryIntersectionTypedPropertyFromStrictSetUpRector`](../src/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector.php)

The `MockeryIntersectionTypedPropertyFromStrictSetUpRector` is a custom Rector rule designed to enhance the type definitions of Mockery-based properties in PHPUnit
`TestCase` classes. It ensures that properties initialized in the `setUp()` method with Mockery are assigned intersection types, improving type safety and code clarity.

## Key Features

- **Intersection Type Support**: Adds intersection types (e.g., `\App\User&MockInterface`) to properties assigned Mockery mocks in the `setUp()` method.
- **Modern Type Handling**: Ensures that `MockInterface` is used instead of `LegacyMockInterface`.
- **PHP 8.1+ Required**: This rule leverages PHP 8.1's intersection types and will not work on earlier PHP versions.
- **Customizable Behavior**: Offers configuration options to tailor its behavior to specific coding styles and preferences.

## How this rule works

1. **Scans for `setUp()` Methods**: The rule processes classes that define a `setUp()` method, which is commonly used in PHPUnit to initialize properties.
2. **Identifies Mockery Assignments**: It identifies properties assigned using `Mockery::mock()` and determines their types based on the arguments passed to `mock()`.
3. **Adds Intersection Types**: For eligible properties, it assigns an intersection type that combines the class being mocked and `MockInterface`.
4. **Restricts to Test Classes**: This rule only applies to classes that extend from the PHPUnit `TestCase` class.
5. **Respects Property Visibility**: Only non-private properties are modified to avoid unexpected behavior with external usage.

## Situations when this rule would not be applied

- **Not a Test Class**: The rule skips classes that do not extend from the PHPUnit `TestCase` class via inheritance.
- **No `setUp()` Method**: The rule skips property assignments not within a `setUp()` method.
- **Existing Type Definitions**: If a property already has a type defined, it will not be modified. (You can override this via configuration)
- **Private Properties**: Private properties are excluded, as their usage outside the class cannot be guaranteed. (You can override this via configuration)
- **Non-Mockery Assignments**: Properties not assigned using `Mockery::mock()` are ignored.

## PHP Version Compatibility

This rule requires **PHP 8.1 or higher** due to its reliance on intersection types. It will not run on projects using earlier PHP versions.

## Benefits of Using This Rule

- **Improved Type Safety**: Intersection types ensure that properties are explicitly typed as both the mocked class and `MockInterface`.
- **Modern Mockery Support**: Replaces legacy types with the recommended `MockInterface`.
- **Cleaner Code**: Enhances code readability and maintainability by ensuring consistent and precise type annotations.

## Configuration Options

The behavior of this Rector rule can be customized through the [`MockeryIntersectionTypedPropertyFromStrictSetUp`](/src/Rector/Property/PHPUnit/ValueObject/MockeryIntersectionTypedPropertyFromStrictSetUp.php) configuration object:

### `useShortImports`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Determines whether short imports should be used when generating or modifying code.
  - When set to `true`, the class will prefer short import statements.
  - When set to `false`, fully qualified names will be used.

### `replaceExistingType`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Controls whether existing types on properties should be replaced.
  - When set to `true`, any existing type annotations on the property will be replaced.
  - When set to `false`, existing type annotations will remain untouched.

### `includeNonPrivateProperties`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Specifies whether non-private properties should be included in the processing.
  - When set to `true`, properties with visibility other than `private` will be included.
  - When set to `false`, only `private` properties will be considered.

:wrench: **configure it!**
