# Changelog
All Notable changes to `flipboxdigital\link` will be documented in this file

## 1.0.0-beta.6 - 2017-09-26
### Fixed
- Issue when used in a repeatable field type (such as 'Meta') and the same link type is used, the result would be 
duplicated

## 1.0.0-beta.5 - 2017-07-30
### Added
- Flexibility when setting link text on a link type

## 1.0.0-beta.4 - 2017-07-24
### Fixed
- Conflict with a Link Type's text property when accessed via twig template

## 1.0.0-beta.3 - 2017-07-21
### Added
- Admin panel icon

### Fixed
- Field input error when no value is present
- Redundant type logic for native Url

## 1.0.0-beta.2 - 2017-07-21
### Added
- The ability to add multiple link types (of the same kind).
- The concept of type identifiers to uniquely identify a link type (vs the class name)

### Changed
- Urls are now stored in the database using an identifier (although class names are still supported).
- The field configuration UI is more intuitive
- The format in which field settings are stored (breaking change).

## 1.0.0-beta.1 - 2017-07-19
### Fixed
- Issue when multiple link fields were present via matrix fields
- Entry link type input when an entry was not set
- Supporting invalid raw link value (when a field type has changed with existing data)

## 1.0.0-beta - 2017-06-08
### Added
- Initial release!