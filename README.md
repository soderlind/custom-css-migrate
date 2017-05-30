# Migrate custom CSS to WP Customizer Additional CSS

 **NOTE**, you **must** edit the `$ps_custom_css_data_array` array before you install and activate the plugin

```php
/**
 * Custom CSS settings, check the plugin / theme to find the values
 * @var array
 */
$ps_custom_css_data_array = array(
	/* Simple Custom CSS, https://wordpress.org/plugins/simple-custom-css/ */
	array(
		'type'    => 'option', // any other value will be considered as theme_mod
		'name'    => 'sccss_settings',
		'setting' => 'sccss-content',
		'strip'   => '/* Enter Your Custom CSS Here */',
	),
);
```

## Installation

Modify the source code, then upload and activate.

## Copyright and License

Migrate custom CSS to WP Customizer Additional CSS is copyright 2017 Per Soderlind

Migrate custom CSS to WP Customizer Additional CSS is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

Migrate custom CSS to WP Customizer Additional CSS is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with the Extension. If not, see http://www.gnu.org/licenses/.
