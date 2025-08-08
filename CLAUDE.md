# Claude Memory - SDC Styleguide Project

## Project Overview
This is a Drupal 11 development environment for working on the SDC Styleguide module. The project uses:
- **Framework**: Drupal 11 with DDev
- **Frontend**: Tailwind CSS v4.1.11 (latest version)
- **Components**: Single Directory Components (SDC)
- **Theme**: Custom "tester" theme at `web/themes/custom/tester/`
- **Module**: SDC Styleguide at `web/modules/custom/sdc_styleguide/`

## Recent Session Summary (2025-07-11)

### Initial Task
User requested updates to the card SDC component to include:
- `title` property (string)
- `description` property (string) 
- `url` property (string)
- `button_label` property (string)

### Issues Encountered & Resolved

#### 1. Array Type Support in SDC Properties
**Problem**: Card component used `type: [string, number]` for title property, causing TypeError in `SDCStyleguideStoryPropertyTypePluginManager::callPluginMethodByHandledType()`

**Root Cause**: Method expected string but received array when properties had multiple types

**Solution Applied**: 
- Modified `callPluginMethodByHandledType()` in `SDCStyleguideStoryPropertyTypePluginManager.php:75`
- Added array type handling: uses first type or defaults to 'string'
- Updated parameter type annotation to accept `string|array`

```php
private function callPluginMethodByHandledType($type, string $method, $arguments) {
    // Handle array types - use the first type or default to 'string'
    if (is_array($type)) {
        $type = !empty($type) ? reset($type) : 'string';
    }
    // ... rest of method
}
```

#### 2. Html::escape() Type Validation
**Problem**: `Html::escape()` receiving arrays instead of strings in `SDCDemoForm.php:304`

**Solution Applied**:
- Added string validation before calling `Html::escape()` in `SDCDemoForm.php:304-307`
- Only escape values that are actually strings

```php
// Only escape if value is a string
if (is_string($value)) {
    $value = str_replace("\r\n", PHP_EOL, Html::escape($value));
}
```

#### 3. Explorer Page Array Type Issues
**Problem**: Similar array type issues in `SDCDemoManager.php` causing explorer page to fail

**Solutions Applied**:
- **Line 105-113**: Added proper array type checking for supported types validation
- **Line 120-127**: Added string conversion for error messages (`implode('|', $array)`)
- **Line 134-155**: Updated type processing logic to handle both single and array types

### Files Modified

1. **Card Component Files**:
   - `web/themes/custom/tester/components/card/card.component.yml` - Added props schema
   - `web/themes/custom/tester/components/card/card.twig` - Updated template with new properties
   - `web/themes/custom/tester/components/card/card.demo.sample.yml` - Added example data

2. **SDC Styleguide Module Fixes**:
   - `web/modules/custom/sdc_styleguide/src/SDCStyleguideStoryPropertyTypePluginManager.php`
   - `web/modules/custom/sdc_styleguide/src/Form/SDCDemoForm.php`
   - `web/modules/custom/sdc_styleguide/src/Service/SDCDemoManager.php`

### Test Results
✅ **All URLs Working**:
- Card component form: `/styleguide/form/tester:card` (200 OK)
- Styleguide explorer: `/styleguide/explorer` (200 OK)
- Card demo: `/styleguide/component/Content/tester%3Acard/sample` (200 OK)

### Final Component Structure

**Card Component Properties**:
```yaml
title:
  type: [number, string]  # Supports both types
  title: Title
  description: The card title
description:
  type: string
  title: Description  
  description: The card description text
url:
  type: string
  title: URL
  description: The link URL for the card button
button_label:
  type: string
  title: Button Label
  description: The text displayed on the button
```

**Template Features**:
- Conditional rendering with `{% if %}` statements
- Tailwind CSS styling with modern classes
- Responsive design with proper spacing
- Button with arrow icon when URL provided

## Development Workflow

### Cache Management
**Important**: Always clear Drupal cache after making changes to SDC module files:
```bash
ddev drush cr
```

### Available Routes
- `/styleguide/explorer` - Component explorer interface
- `/styleguide/form/{componentId}` - Individual component forms
- `/styleguide/component/{group}/{component}/{demo}` - Component demos
- `/styleguide/section/{section}` - Styleguide sections
- `/styleguide/welcome` - Welcome page

### Frontend Build Process
```bash
npm run dev  # Starts Tailwind CSS watch mode
```

## Technical Context for Future Sessions

### Key Architecture Patterns
1. **Array Type Handling**: SDC properties can have multiple types like `[string, number]` - always check for arrays and extract first supported type
2. **String Validation**: Always validate data types before passing to Drupal's Html::escape() or similar functions
3. **Plugin System**: SDC Styleguide uses plugin managers for property type handling - modifications may require updates to multiple plugin methods

### Common Debugging Steps
1. Check Drupal logs: `ddev drush watchdog:show`
2. Clear cache: `ddev drush cr`
3. Test individual URLs for HTTP status
4. Verify component YAML syntax and structure

### Development Environment
- **DDev URL**: https://sdc-dev.ddev.site
- **Database**: MariaDB 10.11
- **PHP**: 8.3
- **Node.js**: 22
- **Drupal**: 11.2.2

## Permissions & Capabilities Demonstrated
- File reading/writing across the codebase
- PHP code modification and debugging
- Drupal module development
- SDC component creation and configuration
- Frontend build tool usage (Tailwind CSS)
- Cache management
- URL testing and debugging
- Error analysis and systematic problem solving

## Future Considerations
- Consider adding validation for URL fields in card component
- May want to extend button styling options
- Could add image field support to card component
- Monitor for Tailwind v4 updates (version used is quite recent)